import 'package:flutter/material.dart';

import '../services/api_client.dart';
import '../services/auth_service.dart';
import '../services/nfc_uid_service.dart';

class LoginScreen extends StatefulWidget {
  const LoginScreen({super.key, required this.onLoggedIn});

  final VoidCallback onLoggedIn;

  @override
  State<LoginScreen> createState() => _LoginScreenState();
}

class _LoginScreenState extends State<LoginScreen>
    with SingleTickerProviderStateMixin {
  final _studentFormKey = GlobalKey<FormState>();
  final _teacherFormKey = GlobalKey<FormState>();
  final _usernameController = TextEditingController();
  final _passwordController = TextEditingController();
  final _nipController = TextEditingController();

  final _apiClient = ApiClient();
  final _authService = AuthService();

  DateTime? _teacherBirthDate;
  bool _loading = false;
  String _message = '';
  bool _messageOk = false;
  final _uidService = NfcUidService();

  @override
  void dispose() {
    _usernameController.dispose();
    _passwordController.dispose();
    _nipController.dispose();
    super.dispose();
  }

  Future<void> _loginStudent() async {
    if (!(_studentFormKey.currentState?.validate() ?? false)) {
      return;
    }

    setState(() {
      _loading = true;
      _message = '';
    });

    final result = await _apiClient.loginStudent(
      username: _usernameController.text.trim(),
      password: _passwordController.text,
    );

    if (!mounted) {
      return;
    }

    setState(() {
      _loading = false;
      _messageOk = result.ok;
      _message = result.message;
    });

    if (result.ok && result.data != null) {
      final uid =
          (result.data?['uid_kartu'] ?? result.data?['user']?['uid_kartu'])
              ?.toString();
      if (uid != null && uid.isNotEmpty) {
        await _uidService.saveUid(uid);
      }
      await _authService.saveSession(
        token: result.data?['token']?.toString() ?? '',
        role: result.data?['role']?.toString() ?? 'siswa',
        name: (result.data?['user']?['name'] ?? '-').toString(),
      );
      widget.onLoggedIn();
    }
  }

  Future<void> _loginTeacher() async {
    if (!(_teacherFormKey.currentState?.validate() ?? false)) {
      return;
    }

    if (_teacherBirthDate == null) {
      setState(() {
        _messageOk = false;
        _message = 'Tanggal lahir wajib dipilih.';
      });
      return;
    }

    setState(() {
      _loading = true;
      _message = '';
    });

    final result = await _apiClient.loginTeacher(
      nip: _nipController.text.trim(),
      birthDate: _formatDate(_teacherBirthDate!),
    );

    if (!mounted) {
      return;
    }

    setState(() {
      _loading = false;
      _messageOk = result.ok;
      _message = result.message;
    });

    if (result.ok && result.data != null) {
      await _authService.saveSession(
        token: result.data?['token']?.toString() ?? '',
        role: result.data?['role']?.toString() ?? 'guru',
        name: (result.data?['user']?['name'] ?? '-').toString(),
      );
      widget.onLoggedIn();
    }
  }

  Future<void> _pickTeacherBirthDate() async {
    final now = DateTime.now();
    final initial = DateTime(now.year - 30, 1, 1);
    final selected = await showDatePicker(
      context: context,
      initialDate: _teacherBirthDate ?? initial,
      firstDate: DateTime(now.year - 70),
      lastDate: DateTime(now.year - 15),
    );

    if (selected != null) {
      setState(() => _teacherBirthDate = selected);
    }
  }

  String _formatDate(DateTime date) {
    final month = date.month.toString().padLeft(2, '0');
    final day = date.day.toString().padLeft(2, '0');
    return '${date.year}-$month-$day';
  }

  @override
  Widget build(BuildContext context) {
    return DefaultTabController(
      length: 2,
      child: Scaffold(
        appBar: AppBar(
          title: const Text('Login Absensi NFC'),
          bottom: const TabBar(
            tabs: [
              Tab(text: 'Siswa'),
              Tab(text: 'Guru'),
            ],
          ),
        ),
        body: TabBarView(
          children: [_buildStudentLogin(), _buildTeacherLogin()],
        ),
      ),
    );
  }

  Widget _buildStudentLogin() {
    return ListView(
      padding: const EdgeInsets.all(20),
      children: [
        Text('Login Siswa', style: Theme.of(context).textTheme.headlineSmall),
        const SizedBox(height: 8),
        Text(
          'Masukkan username dan password dari admin TU.',
          style: Theme.of(context).textTheme.bodyMedium,
        ),
        const SizedBox(height: 20),
        Form(
          key: _studentFormKey,
          child: Column(
            children: [
              TextFormField(
                controller: _usernameController,
                decoration: const InputDecoration(
                  labelText: 'Username',
                  hintText: 'Contoh: rafa',
                ),
                validator: (value) {
                  if (value == null || value.trim().isEmpty) {
                    return 'Username wajib diisi.';
                  }
                  return null;
                },
              ),
              const SizedBox(height: 12),
              TextFormField(
                controller: _passwordController,
                obscureText: true,
                decoration: const InputDecoration(labelText: 'Password'),
                validator: (value) {
                  if (value == null || value.isEmpty) {
                    return 'Password wajib diisi.';
                  }
                  return null;
                },
              ),
              const SizedBox(height: 16),
              SizedBox(
                width: double.infinity,
                child: ElevatedButton(
                  onPressed: _loading ? null : _loginStudent,
                  child: _loading
                      ? const SizedBox(
                          width: 20,
                          height: 20,
                          child: CircularProgressIndicator(strokeWidth: 2),
                        )
                      : const Text('Masuk'),
                ),
              ),
            ],
          ),
        ),
        if (_message.isNotEmpty)
          Padding(
            padding: const EdgeInsets.only(top: 16),
            child: _ResultBanner(ok: _messageOk, message: _message),
          ),
      ],
    );
  }

  Widget _buildTeacherLogin() {
    return ListView(
      padding: const EdgeInsets.all(20),
      children: [
        Text('Login Guru', style: Theme.of(context).textTheme.headlineSmall),
        const SizedBox(height: 8),
        Text(
          'Masukkan NIP dan tanggal lahir untuk masuk.',
          style: Theme.of(context).textTheme.bodyMedium,
        ),
        const SizedBox(height: 20),
        Form(
          key: _teacherFormKey,
          child: Column(
            children: [
              TextFormField(
                controller: _nipController,
                decoration: const InputDecoration(labelText: 'NIP'),
                validator: (value) {
                  if (value == null || value.trim().isEmpty) {
                    return 'NIP wajib diisi.';
                  }
                  return null;
                },
              ),
              const SizedBox(height: 12),
              InkWell(
                onTap: _pickTeacherBirthDate,
                child: InputDecorator(
                  decoration: const InputDecoration(labelText: 'Tanggal Lahir'),
                  child: Align(
                    alignment: Alignment.centerLeft,
                    child: Text(
                      _teacherBirthDate == null
                          ? 'Pilih tanggal'
                          : _formatDate(_teacherBirthDate!),
                    ),
                  ),
                ),
              ),
              const SizedBox(height: 16),
              SizedBox(
                width: double.infinity,
                child: ElevatedButton(
                  onPressed: _loading ? null : _loginTeacher,
                  child: _loading
                      ? const SizedBox(
                          width: 20,
                          height: 20,
                          child: CircularProgressIndicator(strokeWidth: 2),
                        )
                      : const Text('Masuk'),
                ),
              ),
            ],
          ),
        ),
        if (_message.isNotEmpty)
          Padding(
            padding: const EdgeInsets.only(top: 16),
            child: _ResultBanner(ok: _messageOk, message: _message),
          ),
      ],
    );
  }
}

class _ResultBanner extends StatelessWidget {
  const _ResultBanner({required this.ok, required this.message});

  final bool ok;
  final String message;

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.all(12),
      decoration: BoxDecoration(
        color: ok
            ? Colors.green.withValues(alpha: 0.1)
            : Colors.red.withValues(alpha: 0.1),
        borderRadius: BorderRadius.circular(12),
      ),
      child: Text(message),
    );
  }
}

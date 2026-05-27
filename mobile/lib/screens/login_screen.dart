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

class _LoginScreenState extends State<LoginScreen> {
  final _formKey = GlobalKey<FormState>();
  final _emailController = TextEditingController();
  final _birthDateController = TextEditingController();

  final _apiClient = ApiClient();
  final _authService = AuthService();
  final _uidService = NfcUidService();

  bool _loading = false;
  String _message = '';
  bool _messageOk = false;

  @override
  void dispose() {
    _emailController.dispose();
    _birthDateController.dispose();
    super.dispose();
  }

  Future<ApiResult> _attemptStudentLogin(
    String email,
    String birthDate,
  ) async {
    final result = await _apiClient.loginStudent(
      email: email,
      birthDate: birthDate,
    );

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
    }

    return result;
  }

  Future<ApiResult> _attemptTeacherLogin(
    String email,
    String birthDate,
  ) async {
    final result = await _apiClient.loginTeacher(
      email: email,
      birthDate: birthDate,
    );

    if (result.ok && result.data != null) {
      await _authService.saveSession(
        token: result.data?['token']?.toString() ?? '',
        role: result.data?['role']?.toString() ?? 'guru',
        name: (result.data?['user']?['name'] ?? '-').toString(),
      );
    }

    return result;
  }

  Future<void> _submit() async {
    if (!(_formKey.currentState?.validate() ?? false)) {
      return;
    }

    setState(() {
      _loading = true;
      _message = '';
    });

    final email = _emailController.text.trim();
    final birthDate = _birthDateController.text.trim();

    var result = await _attemptStudentLogin(email, birthDate);
    if (!result.ok && result.statusCode == 401) {
      result = await _attemptTeacherLogin(email, birthDate);
    }

    if (!mounted) {
      return;
    }

    setState(() {
      _loading = false;
      _messageOk = result.ok;
      _message = result.message;
    });

    if (result.ok) {
      widget.onLoggedIn();
    }
  }

  @override
  Widget build(BuildContext context) {
    const helperText =
      'Gunakan email dan tanggal lahir (YYYY-MM-DD), contoh 2010-12-23.';

    return Scaffold(
      appBar: AppBar(
        title: const Text('Login Absensi NFC'),
      ),
      body: ListView(
        padding: const EdgeInsets.all(20),
        children: [
          Text('Login', style: Theme.of(context).textTheme.headlineSmall),
          const SizedBox(height: 8),
          Text(helperText, style: Theme.of(context).textTheme.bodyMedium),
          const SizedBox(height: 20),
          Form(
            key: _formKey,
            child: Column(
              children: [
                TextFormField(
                  controller: _emailController,
                  keyboardType: TextInputType.emailAddress,
                  decoration: const InputDecoration(
                    labelText: 'Email',
                    hintText: 'Contoh: nama@sekolah.id',
                  ),
                  validator: (value) {
                    final input = value?.trim() ?? '';
                    if (input.isEmpty) {
                      return 'Email wajib diisi.';
                    }
                    if (!input.contains('@')) {
                      return 'Email harus memakai @.';
                    }
                    return null;
                  },
                ),
                const SizedBox(height: 12),
                TextFormField(
                  controller: _birthDateController,
                  keyboardType: TextInputType.datetime,
                  decoration: const InputDecoration(
                    labelText: 'Tanggal Lahir',
                    hintText: 'Contoh: 2010-12-23',
                  ),
                  validator: (value) {
                    final input = value?.trim() ?? '';
                    if (input.isEmpty) {
                      return 'Tanggal lahir wajib diisi.';
                    }
                    final datePattern = RegExp(r'^\d{4}-\d{2}-\d{2}$');
                    if (!datePattern.hasMatch(input)) {
                      return 'Tanggal lahir gunakan format YYYY-MM-DD.';
                    }
                    return null;
                  },
                ),
                const SizedBox(height: 16),
                SizedBox(
                  width: double.infinity,
                  child: ElevatedButton(
                    onPressed: _loading ? null : _submit,
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
      ),
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

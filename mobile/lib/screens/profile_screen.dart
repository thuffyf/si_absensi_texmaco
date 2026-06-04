import 'package:flutter/material.dart';
import 'package:flutter/services.dart';

import '../services/api_client.dart';

class ProfileScreen extends StatefulWidget {
  const ProfileScreen({
    super.key,
    required this.authToken,
    required this.onLogout,
  });

  final String authToken;
  final VoidCallback onLogout;

  @override
  State<ProfileScreen> createState() => _ProfileScreenState();
}

class _ProfileScreenState extends State<ProfileScreen> {
  final _apiClient = ApiClient();

  bool _loading = true;
  String _message = '';
  Map<String, dynamic> _profile = {};

  @override
  void initState() {
    super.initState();
    _loadProfile();
  }

  Future<void> _loadProfile() async {
    setState(() {
      _loading = true;
      _message = '';
    });

    final result = await _apiClient.fetchStudentProfile(
      token: widget.authToken,
    );

    if (!mounted) {
      return;
    }

    final data = result.data ?? <String, dynamic>{};
    setState(() {
      _loading = false;
      _message = result.ok ? '' : result.message;
      _profile = Map<String, dynamic>.from(data['user'] ?? {});
    });
  }

  Future<void> _copyUid() async {
    final uid = _profile['uid_kartu']?.toString() ?? '';
    if (uid.isEmpty) {
      return;
    }
    await Clipboard.setData(ClipboardData(text: uid));
    if (!mounted) {
      return;
    }
    ScaffoldMessenger.of(
      context,
    ).showSnackBar(const SnackBar(content: Text('UID disalin.')));
  }

  @override
  Widget build(BuildContext context) {
    final name = (_profile['name']?.toString() ?? '').trim();
    final initial = (name.isNotEmpty ? name : 'S')
        .characters
        .first
        .toUpperCase();
    final uid = (_profile['uid_kartu']?.toString() ?? '').trim();
    final hasUid = uid.isNotEmpty;

    return RefreshIndicator(
      onRefresh: _loadProfile,
      child: ListView(
        padding: const EdgeInsets.all(20),
        children: [
          Text(
            'Profil Siswa',
            style: Theme.of(
              context,
            ).textTheme.headlineSmall?.copyWith(fontWeight: FontWeight.w700),
          ),
          const SizedBox(height: 20),
          if (_loading)
            const Center(child: CircularProgressIndicator())
          else if (_message.isNotEmpty)
            _InfoCard(message: _message)
          else ...[
            Card(
              elevation: 0,
              child: Padding(
                padding: const EdgeInsets.all(16),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Row(
                      children: [
                        CircleAvatar(
                          radius: 28,
                          child: Text(initial),
                        ),
                        const SizedBox(width: 14),
                        Expanded(
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Text(
                                name.isNotEmpty ? name : '-',
                                style: Theme.of(context).textTheme.titleLarge
                                    ?.copyWith(fontWeight: FontWeight.w800),
                              ),
                              Text('NIS ${_profile['nis'] ?? '-'}'),
                            ],
                          ),
                        ),
                      ],
                    ),
                    const SizedBox(height: 18),
                    _ProfileRow(
                      icon: Icons.school_outlined,
                      label: 'Kelas',
                      value:
                          '${_profile['class_name'] ?? '-'} ${_profile['major'] ?? ''}',
                    ),
                    _ProfileRow(
                      icon: Icons.email_outlined,
                      label: 'Email',
                      value: _profile['email']?.toString() ?? '-',
                    ),
                    _ProfileRow(
                      icon: Icons.phone_outlined,
                      label: 'Telepon',
                      value: _profile['phone']?.toString() ?? '-',
                    ),
                    _ProfileRow(
                      icon: Icons.cake_outlined,
                      label: 'Tanggal Lahir',
                      value: _profile['date_of_birth']?.toString() ?? '-',
                    ),
                  ],
                ),
              ),
            ),
            const SizedBox(height: 16),
            Card(
              elevation: 0,
              child: Padding(
                padding: const EdgeInsets.all(16),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      'UID Kartu/Stiker',
                      style: Theme.of(context).textTheme.titleMedium?.copyWith(
                        fontWeight: FontWeight.w700,
                      ),
                    ),
                    const SizedBox(height: 8),
                    SelectableText(
                      hasUid ? uid : 'UID belum diatur',
                      style: Theme.of(context).textTheme.bodyLarge?.copyWith(
                        fontFamily: 'monospace',
                        fontWeight: FontWeight.w700,
                      ),
                    ),
                    const SizedBox(height: 12),
                    OutlinedButton.icon(
                      onPressed: hasUid ? _copyUid : null,
                      icon: const Icon(Icons.copy_outlined),
                      label: const Text('Salin UID'),
                    ),
                  ],
                ),
              ),
            ),
            const SizedBox(height: 16),
            SizedBox(
              width: double.infinity,
              child: OutlinedButton.icon(
                onPressed: widget.onLogout,
                icon: const Icon(Icons.logout),
                label: const Text('Keluar'),
              ),
            ),
          ],
        ],
      ),
    );
  }
}

class _ProfileRow extends StatelessWidget {
  const _ProfileRow({
    required this.icon,
    required this.label,
    required this.value,
  });

  final IconData icon;
  final String label;
  final String value;

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 8),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Icon(icon, size: 20, color: Theme.of(context).colorScheme.primary),
          const SizedBox(width: 10),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(label, style: Theme.of(context).textTheme.bodySmall),
                Text(
                  value,
                  style: const TextStyle(fontWeight: FontWeight.w600),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }
}

class _InfoCard extends StatelessWidget {
  const _InfoCard({required this.message});

  final String message;

  @override
  Widget build(BuildContext context) {
    return Card(
      elevation: 0,
      child: Padding(
        padding: const EdgeInsets.all(18),
        child: Center(child: Text(message)),
      ),
    );
  }
}

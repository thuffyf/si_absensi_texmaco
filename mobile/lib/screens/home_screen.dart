import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:http/http.dart' as http;

import '../services/api_client.dart';
import '../services/nfc_uid_service.dart';

class HomeScreen extends StatefulWidget {
  const HomeScreen({super.key, required this.authToken});

  final String authToken;

  @override
  State<HomeScreen> createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  final _uidService = NfcUidService();
  final _apiClient = ApiClient();

  bool _loading = true;
  bool _summaryLoading = true;
  bool _uidRefreshing = false;
  String _uid = '';
  String _summaryPeriod = '';
  String _summaryMessage = '';
  Map<String, int> _summary = const {
    'hadir': 0,
    'izin': 0,
    'sakit': 0,
    'alfa': 0,
    'total': 0,
  };

  List<Map<String, dynamic>> _absensiRecords = [];

  @override
  void initState() {
    super.initState();
    _loadUid();
    _loadSummary();
  }

  Future<void> _loadUid() async {
    setState(() => _loading = true);
    final uid = await _uidService.getUid();
    if (!mounted) {
      return;
    }
    setState(() {
      _uid = uid ?? '';
      _loading = false;
    });
  }

  Future<void> _refreshUid({bool showMessage = true}) async {
    setState(() => _uidRefreshing = true);

    final result = await _apiClient.fetchStudentProfile(
      token: widget.authToken,
    );

    if (!mounted) {
      return;
    }

    if (result.ok && result.data != null) {
      final uid =
          (result.data?['uid_kartu'] ?? result.data?['user']?['uid_kartu'])
              ?.toString();
      if (uid != null && uid.isNotEmpty) {
        await _uidService.saveUid(uid);
      }
      setState(() {
        _uid = uid ?? '';
        _uidRefreshing = false;
      });
      if (showMessage) {
        ScaffoldMessenger.of(
          context,
        ).showSnackBar(const SnackBar(content: Text('UID diperbarui.')));
      }
      return;
    }

    setState(() => _uidRefreshing = false);
    if (showMessage) {
      ScaffoldMessenger.of(
        context,
      ).showSnackBar(SnackBar(content: Text(result.message)));
    }
  }

  Future<void> _loadSummary() async {
    setState(() {
      _summaryLoading = true;
      _summaryMessage = '';
    });

    final result = await _apiClient.fetchStudentSummary(
      token: widget.authToken,
    );

    if (!mounted) {
      return;
    }

    if (!result.ok || result.data == null) {
      setState(() {
        _summaryLoading = false;
        _summaryMessage = result.message;
      });
      return;
    }

    final data = result.data ?? <String, dynamic>{};
    final summary = Map<String, dynamic>.from(data['summary'] ?? {});
    final period = Map<String, dynamic>.from(data['period'] ?? {});

    setState(() {
      _summaryLoading = false;
      _summaryMessage = result.message;
      _summary = {
        'hadir': (summary['hadir'] ?? 0) as int,
        'izin': (summary['izin'] ?? 0) as int,
        'sakit': (summary['sakit'] ?? 0) as int,
        'alfa': (summary['alfa'] ?? 0) as int,
        'total': (summary['total'] ?? 0) as int,
      };
      final from = period['from']?.toString() ?? '';
      final until = period['until']?.toString() ?? '';
      _summaryPeriod = from.isNotEmpty && until.isNotEmpty
          ? '$from s/d $until'
          : '';
    });
  }

  Future<void> _loadAbsensi() async {
    final result = await _apiClient.fetchStudentAbsensi(
      token: widget.authToken,
    );

    if (!mounted) {
      return;
    }

    if (result.ok && result.data != null) {
      final data = result.data ?? <String, dynamic>{};
      final records = data['data'] as List<dynamic>? ?? [];
      setState(() {
        _absensiRecords = records
            .map((e) => Map<String, dynamic>.from(e as Map))
            .toList();
      });
    }
  }

  Future<void> _copyUid() async {
    if (_uid.isEmpty) {
      return;
    }
    await Clipboard.setData(ClipboardData(text: _uid));
    if (!mounted) {
      return;
    }
    ScaffoldMessenger.of(
      context,
    ).showSnackBar(const SnackBar(content: Text('UID disalin ke clipboard.')));
  }

  Future<void> _syncAbsensi() async {
    try {
      final response = await http.post(
        Uri.parse('http://localhost/absensi_api/absen.php'),
        body: {'action': 'sync'},
      );

      if (response.statusCode == 200) {
        if (!mounted) return;
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Sinkronisasi absensi berhasil.')),
        );
        await _loadAbsensi();
        await _loadSummary();
      } else {
        if (!mounted) return;
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Gagal sinkronisasi absensi.')),
        );
      }
    } catch (e) {
      if (!mounted) return;
      ScaffoldMessenger.of(
        context,
      ).showSnackBar(SnackBar(content: Text('Error: $e')));
    }
  }

  @override
  Widget build(BuildContext context) {
    return RefreshIndicator(
      onRefresh: () async {
        await _refreshUid(showMessage: false);
        await _loadSummary();
        await _loadAbsensi();
      },
      child: ListView(
        padding: const EdgeInsets.all(20),
        children: [
          Text(
            'Absensi NFC Siswa',
            style: Theme.of(context).textTheme.headlineSmall,
          ),
          const SizedBox(height: 8),
          Text(
            'UID perangkat ini akan dipakai untuk absensi NFC/HCE.',
            style: Theme.of(context).textTheme.bodyMedium,
          ),
          const SizedBox(height: 20),
          Card(
            child: Padding(
              padding: const EdgeInsets.all(16),
              child: _loading
                  ? const Center(child: CircularProgressIndicator())
                  : Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(
                          'UID NFC',
                          style: Theme.of(context).textTheme.titleMedium,
                        ),
                        const SizedBox(height: 8),
                        SelectableText(
                          _uid.isEmpty ? 'UID belum tersedia' : _uid,
                          style: Theme.of(context).textTheme.bodyLarge
                              ?.copyWith(fontFamily: 'monospace'),
                        ),
                        const SizedBox(height: 12),
                        Wrap(
                          spacing: 12,
                          runSpacing: 8,
                          children: [
                            ElevatedButton.icon(
                              onPressed: _uid.isEmpty ? null : _copyUid,
                              icon: const Icon(Icons.copy),
                              label: const Text('Salin'),
                            ),
                            OutlinedButton.icon(
                              onPressed: _uidRefreshing ? null : _refreshUid,
                              icon: _uidRefreshing
                                  ? const SizedBox(
                                      width: 16,
                                      height: 16,
                                      child: CircularProgressIndicator(
                                        strokeWidth: 2,
                                      ),
                                    )
                                  : const Icon(Icons.refresh),
                              label: const Text('Refresh UID'),
                            ),
                          ],
                        ),
                        if (_uid.isEmpty)
                          Padding(
                            padding: const EdgeInsets.only(top: 12),
                            child: Text(
                              'UID belum diatur. Hubungi admin TU untuk mengisi UID siswa.',
                              style: Theme.of(context).textTheme.bodySmall,
                            ),
                          ),
                      ],
                    ),
            ),
          ),
          const SizedBox(height: 16),
          Card(
            child: Padding(
              padding: const EdgeInsets.all(16),
              child: _summaryLoading
                  ? const Center(child: CircularProgressIndicator())
                  : Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(
                          'Ringkasan Kehadiran',
                          style: Theme.of(context).textTheme.titleMedium,
                        ),
                        if (_summaryPeriod.isNotEmpty)
                          Padding(
                            padding: const EdgeInsets.only(top: 4),
                            child: Text(
                              _summaryPeriod,
                              style: Theme.of(context).textTheme.bodySmall,
                            ),
                          ),
                        if (_summaryMessage.isNotEmpty)
                          Padding(
                            padding: const EdgeInsets.only(top: 8),
                            child: Text(
                              _summaryMessage,
                              style: Theme.of(context).textTheme.bodySmall,
                            ),
                          ),
                        const SizedBox(height: 12),
                        Wrap(
                          spacing: 12,
                          runSpacing: 12,
                          children: [
                            _SummaryChip(
                              label: 'Hadir',
                              value: _summary['hadir'] ?? 0,
                              color: Colors.green,
                            ),
                            _SummaryChip(
                              label: 'Izin',
                              value: _summary['izin'] ?? 0,
                              color: Colors.orange,
                            ),
                            _SummaryChip(
                              label: 'Sakit',
                              value: _summary['sakit'] ?? 0,
                              color: Colors.amber,
                            ),
                            _SummaryChip(
                              label: 'Alfa',
                              value: _summary['alfa'] ?? 0,
                              color: Colors.red,
                            ),
                          ],
                        ),
                        const SizedBox(height: 12),
                        Text(
                          'Total catatan: ${_summary['total'] ?? 0}',
                          style: Theme.of(context).textTheme.bodyMedium,
                        ),
                      ],
                    ),
            ),
          ),
          const SizedBox(height: 16),
          Card(
            child: Padding(
              padding: const EdgeInsets.all(16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    'Sinkronisasi Absensi',
                    style: Theme.of(context).textTheme.titleMedium,
                  ),
                  const SizedBox(height: 8),
                  Text(
                    'Sinkronkan data absensi dengan API eksternal.',
                    style: Theme.of(context).textTheme.bodySmall,
                  ),
                  const SizedBox(height: 12),
                  ElevatedButton.icon(
                    onPressed: _syncAbsensi,
                    icon: const Icon(Icons.sync),
                    label: const Text('Sinkronisasi Sekarang'),
                  ),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }
}

class _SummaryChip extends StatelessWidget {
  const _SummaryChip({
    required this.label,
    required this.value,
    required this.color,
  });

  final String label;
  final int value;
  final Color color;

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 10),
      decoration: BoxDecoration(
        color: color.withOpacity(0.12),
        borderRadius: BorderRadius.circular(12),
      ),
      child: Row(
        mainAxisSize: MainAxisSize.min,
        children: [
          Container(
            width: 8,
            height: 8,
            decoration: BoxDecoration(color: color, shape: BoxShape.circle),
          ),
          const SizedBox(width: 6),
          Text('$label: $value'),
        ],
      ),
    );
  }
}

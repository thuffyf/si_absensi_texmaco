import 'package:flutter/material.dart';

import '../services/api_client.dart';

class AttendanceHistoryScreen extends StatefulWidget {
  const AttendanceHistoryScreen({super.key, required this.authToken});

  final String authToken;

  @override
  State<AttendanceHistoryScreen> createState() =>
      _AttendanceHistoryScreenState();
}

class _AttendanceHistoryScreenState extends State<AttendanceHistoryScreen> {
  final _apiClient = ApiClient();

  bool _loading = true;
  String _message = '';
  String _period = '';
  List<Map<String, dynamic>> _records = [];

  @override
  void initState() {
    super.initState();
    _loadRecords();
  }

  Future<void> _loadRecords() async {
    setState(() {
      _loading = true;
      _message = '';
    });

    final result = await _apiClient.fetchStudentAbsensi(
      token: widget.authToken,
    );

    if (!mounted) {
      return;
    }

    final data = result.data ?? <String, dynamic>{};
    final rows = data['data'] as List<dynamic>? ?? [];
    final period = Map<String, dynamic>.from(data['period'] ?? {});
    final from = period['from']?.toString() ?? '';
    final until = period['until']?.toString() ?? '';

    setState(() {
      _loading = false;
      _message = result.ok ? '' : result.message;
      _period = from.isNotEmpty && until.isNotEmpty
          ? '$from sampai $until'
          : '';
      _records = rows
          .map((item) => Map<String, dynamic>.from(item as Map))
          .toList();
    });
  }

  @override
  Widget build(BuildContext context) {
    return RefreshIndicator(
      onRefresh: _loadRecords,
      child: ListView(
        padding: const EdgeInsets.all(20),
        children: [
          Text(
            'Riwayat Absensi',
            style: Theme.of(
              context,
            ).textTheme.headlineSmall?.copyWith(fontWeight: FontWeight.w700),
          ),
          if (_period.isNotEmpty) ...[
            const SizedBox(height: 6),
            Text(_period, style: Theme.of(context).textTheme.bodyMedium),
          ],
          const SizedBox(height: 20),
          if (_loading)
            const Center(child: CircularProgressIndicator())
          else if (_message.isNotEmpty)
            _MessageCard(message: _message)
          else if (_records.isEmpty)
            const _MessageCard(message: 'Belum ada data absensi bulan ini.')
          else
            ..._records.map((record) => _AttendanceCard(record: record)),
        ],
      ),
    );
  }
}

class _AttendanceCard extends StatelessWidget {
  const _AttendanceCard({required this.record});

  final Map<String, dynamic> record;

  @override
  Widget build(BuildContext context) {
    final status = record['status']?.toString() ?? '';
    final color = _statusColor(status);
    final note = record['keterangan']?.toString() ?? '';

    return Card(
      elevation: 0,
      child: ListTile(
        leading: CircleAvatar(
          backgroundColor: color.withValues(alpha: 0.12),
          foregroundColor: color,
          child: Icon(_statusIcon(status)),
        ),
        title: Text(
          _statusLabel(status),
          style: const TextStyle(fontWeight: FontWeight.w700),
        ),
        subtitle: Text(
          [
            record['tanggal']?.toString() ?? '-',
            if ((record['waktu']?.toString() ?? '').isNotEmpty)
              record['waktu'].toString(),
            if (note.isNotEmpty) note,
          ].join(' | '),
        ),
      ),
    );
  }
}

class _MessageCard extends StatelessWidget {
  const _MessageCard({required this.message});

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

String _statusLabel(String status) {
  switch (status) {
    case 'hadir':
      return 'Hadir';
    case 'izin':
      return 'Izin';
    case 'sakit':
      return 'Sakit';
    case 'alpha':
    case 'alfa':
      return 'Alfa';
    default:
      return status.isEmpty ? '-' : status;
  }
}

IconData _statusIcon(String status) {
  switch (status) {
    case 'hadir':
      return Icons.check_circle_outline;
    case 'izin':
      return Icons.event_busy_outlined;
    case 'sakit':
      return Icons.medical_services_outlined;
    default:
      return Icons.info_outline;
  }
}

Color _statusColor(String status) {
  switch (status) {
    case 'hadir':
      return Colors.green;
    case 'izin':
      return Colors.orange;
    case 'sakit':
      return Colors.red;
    default:
      return Colors.blueGrey;
  }
}

import 'package:flutter/material.dart';

import '../services/api_client.dart';

class HomeScreen extends StatefulWidget {
  const HomeScreen({super.key, required this.authToken, required this.name});

  final String authToken;
  final String name;

  @override
  State<HomeScreen> createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  final _apiClient = ApiClient();

  bool _loading = true;
  String _message = '';
  String _period = '';
  Map<String, int> _summary = const {
    'hadir': 0,
    'izin': 0,
    'sakit': 0,
    'alpa': 0,
    'total': 0,
  };
  List<Map<String, dynamic>> _latestRecords = [];
  List<Map<String, dynamic>> _latestRequests = [];

  @override
  void initState() {
    super.initState();
    _loadDashboard();
  }

  Future<void> _loadDashboard() async {
    setState(() {
      _loading = true;
      _message = '';
    });

    final results = await Future.wait<ApiResult>([
      _apiClient.fetchStudentSummary(token: widget.authToken),
      _apiClient.fetchStudentAbsensi(token: widget.authToken),
      _apiClient.fetchStudentLeaveRequests(token: widget.authToken),
    ]);
    final summaryResult = results[0];
    final absensiResult = results[1];
    final leaveResult = results[2];

    if (!mounted) {
      return;
    }

    if (!summaryResult.ok) {
      setState(() {
        _loading = false;
        _message = summaryResult.message;
      });
      return;
    }

    final errors = <String>[];
    if (!absensiResult.ok) {
      errors.add('Absensi: ${absensiResult.message}');
    }
    if (!leaveResult.ok) {
      errors.add('Izin/Sakit: ${leaveResult.message}');
    }

    final summaryData = summaryResult.data ?? <String, dynamic>{};
    final summary = Map<String, dynamic>.from(summaryData['summary'] ?? {});
    final period = Map<String, dynamic>.from(summaryData['period'] ?? {});
    final absensiData = absensiResult.data ?? <String, dynamic>{};
    final leaveData = leaveResult.data ?? <String, dynamic>{};
    final records = absensiData['data'] as List<dynamic>? ?? [];
    final requests = leaveData['data'] as List<dynamic>? ?? [];

    setState(() {
      _loading = false;
      _message = errors.join(' ');
      _summary = {
        'hadir': int.tryParse('${summary['hadir'] ?? 0}') ?? 0,
        'izin': int.tryParse('${summary['izin'] ?? 0}') ?? 0,
        'sakit': int.tryParse('${summary['sakit'] ?? 0}') ?? 0,
        'alpa': int.tryParse('${summary['alpa'] ?? 0}') ?? 0,
        'total': int.tryParse('${summary['total'] ?? 0}') ?? 0,
      };
      final from = period['from']?.toString() ?? '';
      final until = period['until']?.toString() ?? '';
      _period = from.isNotEmpty && until.isNotEmpty
          ? '$from sampai $until'
          : '';
      _latestRecords = records
          .take(4)
          .map((item) => Map<String, dynamic>.from(item as Map))
          .toList();
      _latestRequests = requests
          .take(3)
          .map((item) => Map<String, dynamic>.from(item as Map))
          .toList();
    });
  }

  @override
  Widget build(BuildContext context) {
    return RefreshIndicator(
      onRefresh: _loadDashboard,
      child: ListView(
        padding: const EdgeInsets.all(20),
        children: [
          Text(
            'Halo, ${widget.name}',
            style: Theme.of(
              context,
            ).textTheme.headlineSmall?.copyWith(fontWeight: FontWeight.w700),
          ),
          const SizedBox(height: 6),
          Text(
            'Ringkasan absensi dan pengajuan bulan ini.',
            style: Theme.of(context).textTheme.bodyMedium,
          ),
          const SizedBox(height: 20),
          if (_loading)
            const Padding(
              padding: EdgeInsets.only(top: 80),
              child: Center(child: CircularProgressIndicator()),
            )
          else ...[
            if (_message.isNotEmpty)
              _InfoBanner(message: _message, color: Colors.red),
            _SummaryCard(summary: _summary, period: _period),
            const SizedBox(height: 16),
            _SectionCard(
              title: 'Riwayat Terbaru',
              emptyText: 'Belum ada absensi bulan ini.',
              children: _latestRecords
                  .map(
                    (record) => _TimelineRow(
                      title: _statusLabel(record['status']),
                      subtitle:
                          '${record['tanggal'] ?? '-'}  ${record['waktu'] ?? ''}',
                      color: _statusColor(record['status']),
                      trailing: record['keterangan']?.toString(),
                    ),
                  )
                  .toList(),
            ),
            const SizedBox(height: 16),
            _SectionCard(
              title: 'Pengajuan Izin/Sakit',
              emptyText: 'Belum ada pengajuan.',
              children: _latestRequests
                  .map(
                    (request) => _TimelineRow(
                      title:
                          '${_typeLabel(request['type'])} - ${_requestStatusLabel(request['status'])}',
                      subtitle:
                          '${request['start_date'] ?? '-'}  ${request['reason'] ?? ''}',
                      color: _requestStatusColor(request['status']),
                    ),
                  )
                  .toList(),
            ),
          ],
        ],
      ),
    );
  }
}

class _SummaryCard extends StatelessWidget {
  const _SummaryCard({required this.summary, required this.period});

  final Map<String, int> summary;
  final String period;

  @override
  Widget build(BuildContext context) {
    return Card(
      elevation: 0,
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              'Kehadiran',
              style: Theme.of(
                context,
              ).textTheme.titleMedium?.copyWith(fontWeight: FontWeight.w700),
            ),
            if (period.isNotEmpty) ...[
              const SizedBox(height: 4),
              Text(period, style: Theme.of(context).textTheme.bodySmall),
            ],
            const SizedBox(height: 16),
            LayoutBuilder(
              builder: (context, constraints) {
                final columns = constraints.maxWidth < 340 ? 1 : 2;
                return GridView.count(
                  crossAxisCount: columns,
                  mainAxisSpacing: 10,
                  crossAxisSpacing: 10,
                  childAspectRatio: columns == 1 ? 5 : 2.4,
                  shrinkWrap: true,
                  physics: const NeverScrollableScrollPhysics(),
                  children: [
                    _MetricTile('Hadir', summary['hadir'] ?? 0, Colors.green),
                    _MetricTile('Izin', summary['izin'] ?? 0, Colors.orange),
                    _MetricTile('Sakit', summary['sakit'] ?? 0, Colors.red),
                    _MetricTile('Alpa', summary['alpa'] ?? 0, Colors.blueGrey),
                  ],
                );
              },
            ),
          ],
        ),
      ),
    );
  }
}

class _MetricTile extends StatelessWidget {
  const _MetricTile(this.label, this.value, this.color);

  final String label;
  final int value;
  final Color color;

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 10),
      decoration: BoxDecoration(
        borderRadius: BorderRadius.circular(8),
        color: color.withValues(alpha: 0.12),
      ),
      child: Row(
        children: [
          Icon(Icons.circle, size: 10, color: color),
          const SizedBox(width: 8),
          Expanded(child: Text(label)),
          Text(
            '$value',
            style: Theme.of(context).textTheme.titleMedium?.copyWith(
              fontWeight: FontWeight.w800,
              color: color,
            ),
          ),
        ],
      ),
    );
  }
}

class _SectionCard extends StatelessWidget {
  const _SectionCard({
    required this.title,
    required this.emptyText,
    required this.children,
  });

  final String title;
  final String emptyText;
  final List<Widget> children;

  @override
  Widget build(BuildContext context) {
    return Card(
      elevation: 0,
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              title,
              style: Theme.of(
                context,
              ).textTheme.titleMedium?.copyWith(fontWeight: FontWeight.w700),
            ),
            const SizedBox(height: 10),
            if (children.isEmpty)
              Text(emptyText, style: Theme.of(context).textTheme.bodySmall)
            else
              ...children,
          ],
        ),
      ),
    );
  }
}

class _TimelineRow extends StatelessWidget {
  const _TimelineRow({
    required this.title,
    required this.subtitle,
    required this.color,
    this.trailing,
  });

  final String title;
  final String subtitle;
  final Color color;
  final String? trailing;

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 8),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Container(
            width: 10,
            height: 10,
            margin: const EdgeInsets.only(top: 5),
            decoration: BoxDecoration(color: color, shape: BoxShape.circle),
          ),
          const SizedBox(width: 10),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  title,
                  style: const TextStyle(fontWeight: FontWeight.w700),
                ),
                const SizedBox(height: 2),
                Text(subtitle, style: Theme.of(context).textTheme.bodySmall),
                if (trailing != null && trailing!.isNotEmpty)
                  Text(trailing!, style: Theme.of(context).textTheme.bodySmall),
              ],
            ),
          ),
        ],
      ),
    );
  }
}

class _InfoBanner extends StatelessWidget {
  const _InfoBanner({required this.message, required this.color});

  final String message;
  final Color color;

  @override
  Widget build(BuildContext context) {
    return Container(
      margin: const EdgeInsets.only(bottom: 16),
      padding: const EdgeInsets.all(12),
      decoration: BoxDecoration(
        color: color.withValues(alpha: 0.1),
        borderRadius: BorderRadius.circular(8),
      ),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Icon(Icons.info_outline, color: color),
          const SizedBox(width: 10),
          Expanded(child: Text(message)),
        ],
      ),
    );
  }
}

String _statusLabel(Object? status) {
  switch (status?.toString()) {
    case 'hadir':
      return 'Hadir';
    case 'izin':
      return 'Izin';
    case 'sakit':
      return 'Sakit';
    case 'alpha':
    case 'alfa':
    case 'alpa':
      return 'Alpa';
    default:
      return status?.toString() ?? '-';
  }
}

Color _statusColor(Object? status) {
  switch (status?.toString()) {
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

String _typeLabel(Object? type) {
  return type?.toString() == 'sakit' ? 'Sakit' : 'Izin';
}

String _requestStatusLabel(Object? status) {
  switch (status?.toString()) {
    case 'pending_teacher':
      return 'Menunggu Guru';
    case 'pending_admin':
      return 'Menunggu TU';
    case 'approved':
      return 'Disetujui';
    case 'rejected':
      return 'Ditolak';
    default:
      return status?.toString() ?? '-';
  }
}

Color _requestStatusColor(Object? status) {
  switch (status?.toString()) {
    case 'approved':
      return Colors.green;
    case 'rejected':
      return Colors.red;
    case 'pending_admin':
      return Colors.orange;
    default:
      return Colors.blueGrey;
  }
}

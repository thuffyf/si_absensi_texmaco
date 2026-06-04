import 'package:flutter/material.dart';

import '../services/api_client.dart';

class TeacherAbsenceScreen extends StatefulWidget {
  const TeacherAbsenceScreen({super.key, required this.token});

  final String token;

  @override
  State<TeacherAbsenceScreen> createState() => _TeacherAbsenceScreenState();
}

class _TeacherAbsenceScreenState extends State<TeacherAbsenceScreen> {
  final _apiClient = ApiClient();

  bool _loading = true;
  String _message = '';
  String _selectedDate = '';
  String _selectedClass = '';
  String _selectedScheduleId = '';
  String _dayName = '';
  String _view = 'hadir';
  Map<String, int> _summary = const {
    'total_students': 0,
    'hadir': 0,
    'izin': 0,
    'sakit': 0,
    'alpa': 0,
    'belum_absen': 0,
  };
  List<String> _classes = [];
  List<Map<String, dynamic>> _schedules = [];
  List<Map<String, dynamic>> _present = [];
  List<Map<String, dynamic>> _absences = [];
  List<Map<String, dynamic>> _notRecorded = [];

  @override
  void initState() {
    super.initState();
    _selectedDate = _today();
    _load();
  }

  Future<void> _load() async {
    setState(() {
      _loading = true;
      _message = '';
    });

    final result = await _apiClient.fetchTeacherAbsences(
      token: widget.token,
      date: _selectedDate,
      className: _selectedClass,
      scheduleId: _selectedScheduleId,
    );

    if (!mounted) {
      return;
    }

    final data = result.data ?? <String, dynamic>{};
    final summary = Map<String, dynamic>.from(data['summary'] ?? {});
    final classes = (data['classes'] as List<dynamic>? ?? [])
        .map((item) => item.toString())
        .where((item) => item.isNotEmpty)
        .toList();
    final schedules = _mapsFrom(data['schedules']);
    final scheduleIds = schedules
        .map((item) => item['id']?.toString() ?? '')
        .where((id) => id.isNotEmpty)
        .toSet();

    setState(() {
      _loading = false;
      _message = result.ok ? '' : result.message;
      _dayName = data['day_name']?.toString() ?? '';
      _classes = classes;
      if (_selectedClass.isNotEmpty && !_classes.contains(_selectedClass)) {
        _selectedClass = '';
      }
      if (_selectedScheduleId.isNotEmpty &&
          !scheduleIds.contains(_selectedScheduleId)) {
        _selectedScheduleId = '';
      }
      if (_selectedScheduleId.isNotEmpty) {
        final selected = schedules.firstWhere(
          (item) => item['id']?.toString() == _selectedScheduleId,
          orElse: () => <String, dynamic>{},
        );
        final className = selected['class_name']?.toString() ?? '';
        if (className.isNotEmpty) {
          _selectedClass = className;
        }
      }
      _summary = {
        'total_students': _intValue(summary['total_students']),
        'hadir': _intValue(summary['hadir']),
        'izin': _intValue(summary['izin']),
        'sakit': _intValue(summary['sakit']),
        'alpa': _intValue(summary['alpa']),
        'belum_absen': _intValue(summary['belum_absen']),
      };
      _schedules = schedules;
      _present = _mapsFrom(data['present']);
      _absences = _mapsFrom(data['absences']);
      _notRecorded = _mapsFrom(data['not_recorded']);
    });
  }

  int _intValue(Object? value) => int.tryParse('${value ?? 0}') ?? 0;

  List<Map<String, dynamic>> _mapsFrom(Object? value) {
    final rows = value as List<dynamic>? ?? [];
    return rows.map((item) => Map<String, dynamic>.from(item as Map)).toList();
  }

  Future<void> _pickDate() async {
    final now = DateTime.now();
    final current = DateTime.parse(_selectedDate);

    final picked = await showDatePicker(
      context: context,
      initialDate: current,
      firstDate: DateTime(now.year - 1),
      lastDate: DateTime(now.year + 1),
    );

    if (picked != null) {
      setState(() => _selectedDate = _formatDate(picked));
      await _load();
    }
  }

  List<Map<String, dynamic>> get _activeItems {
    switch (_view) {
      case 'tidak_hadir':
        return _absences;
      case 'belum_absen':
        return _notRecorded;
      default:
        return _present;
    }
  }

  String get _activeEmptyText {
    switch (_view) {
      case 'tidak_hadir':
        return 'Tidak ada siswa izin, sakit, atau alpa pada tanggal ini.';
      case 'belum_absen':
        return 'Semua siswa di kelas ini sudah punya catatan absensi.';
      default:
        return 'Belum ada siswa hadir yang tercatat pada tanggal ini.';
    }
  }

  String _today() => _formatDate(DateTime.now());

  String _formatDate(DateTime date) {
    final month = date.month.toString().padLeft(2, '0');
    final day = date.day.toString().padLeft(2, '0');
    return '${date.year}-$month-$day';
  }

  @override
  Widget build(BuildContext context) {
    return RefreshIndicator(
      onRefresh: _load,
      child: ListView(
        padding: const EdgeInsets.all(20),
        children: [
          Row(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      'Kehadiran Siswa',
                      style: Theme.of(context).textTheme.headlineSmall
                          ?.copyWith(fontWeight: FontWeight.w800),
                    ),
                    const SizedBox(height: 4),
                    Text(
                      _dayName.isEmpty
                          ? 'Pantau siswa hadir dari data tap NFC.'
                          : '$_dayName, $_selectedDate',
                      style: Theme.of(context).textTheme.bodyMedium,
                    ),
                  ],
                ),
              ),
              TextButton.icon(
                onPressed: _pickDate,
                icon: const Icon(Icons.date_range_outlined),
                label: Text(_selectedDate),
              ),
            ],
          ),
          const SizedBox(height: 16),
          _ClassFilter(
            value: _selectedClass,
            classes: _classes,
            onChanged: (value) async {
              setState(() {
                _selectedClass = value ?? '';
                _selectedScheduleId = '';
              });
              await _load();
            },
          ),
          const SizedBox(height: 12),
          _ScheduleFilter(
            value: _selectedScheduleId,
            schedules: _schedules,
            onChanged: (value) async {
              setState(() {
                _selectedScheduleId = value ?? '';
                if (_selectedScheduleId.isNotEmpty) {
                  final selected = _schedules.firstWhere(
                    (item) => item['id']?.toString() == _selectedScheduleId,
                    orElse: () => <String, dynamic>{},
                  );
                  final className = selected['class_name']?.toString() ?? '';
                  if (className.isNotEmpty) {
                    _selectedClass = className;
                  }
                }
              });
              await _load();
            },
          ),
          const SizedBox(height: 16),
          if (_loading)
            const Padding(
              padding: EdgeInsets.only(top: 80),
              child: Center(child: CircularProgressIndicator()),
            )
          else ...[
            if (_message.isNotEmpty)
              Padding(
                padding: const EdgeInsets.only(bottom: 12),
                child: Text(
                  _message,
                  style: Theme.of(context).textTheme.bodySmall,
                ),
              ),
            _ScheduleCard(schedules: _schedules),
            const SizedBox(height: 14),
            _SummaryGrid(summary: _summary),
            const SizedBox(height: 16),
            SegmentedButton<String>(
              segments: [
                ButtonSegment(
                  value: 'hadir',
                  icon: const Icon(Icons.check_circle_outline),
                  label: Text('Hadir (${_summary['hadir'] ?? 0})'),
                ),
                ButtonSegment(
                  value: 'tidak_hadir',
                  icon: const Icon(Icons.warning_amber_outlined),
                  label: Text(
                    'Tidak (${(_summary['izin'] ?? 0) + (_summary['sakit'] ?? 0) + (_summary['alpa'] ?? 0)})',
                  ),
                ),
                ButtonSegment(
                  value: 'belum_absen',
                  icon: const Icon(Icons.hourglass_empty_outlined),
                  label: Text('Belum (${_summary['belum_absen'] ?? 0})'),
                ),
              ],
              selected: {_view},
              onSelectionChanged: (value) {
                setState(() => _view = value.first);
              },
            ),
            const SizedBox(height: 14),
            if (_activeItems.isEmpty)
              _EmptyState(text: _activeEmptyText)
            else
              ..._activeItems.map((item) => _AttendanceTile(
                    item: item,
                    onTap: () => _showEditDialog(item),
                  )),
          ],
        ],
      ),
    );
  }
  Future<void> _showEditDialog(Map<String, dynamic> item) async {
    final statusOptions = ['hadir', 'izin', 'sakit', 'alpa'];
    String selectedStatus = item['status']?.toString() ?? 'belum_absen';
    if (!statusOptions.contains(selectedStatus)) {
      selectedStatus = 'hadir';
    }
    final noteController =
        TextEditingController(text: item['note']?.toString() ?? '');

    final result = await showModalBottomSheet<bool>(
      context: context,
      isScrollControlled: true,
      builder: (context) {
        return Padding(
          padding: EdgeInsets.only(
            bottom: MediaQuery.of(context).viewInsets.bottom,
          ),
          child: StatefulBuilder(
            builder: (context, setSheetState) {
              return Padding(
                padding: const EdgeInsets.all(20),
                child: Column(
                  mainAxisSize: MainAxisSize.min,
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      'Edit Absensi: ${item['student_name']}',
                      style: Theme.of(
                        context,
                      ).textTheme.titleLarge?.copyWith(fontWeight: FontWeight.bold),
                    ),
                    const SizedBox(height: 16),
                    RadioGroup<String>(
                      groupValue: selectedStatus,
                      onChanged: (value) {
                        if (value == null) {
                          return;
                        }
                        setSheetState(() => selectedStatus = value);
                      },
                      child: Column(
                        children: statusOptions
                            .map(
                              (status) => RadioListTile<String>(
                                title: Text(_statusLabel(status)),
                                value: status,
                              ),
                            )
                            .toList(),
                      ),
                    ),
                    const SizedBox(height: 12),
                    TextField(
                      controller: noteController,
                      decoration: const InputDecoration(
                        labelText: 'Keterangan (opsional)',
                        border: OutlineInputBorder(),
                      ),
                    ),
                    const SizedBox(height: 16),
                    SizedBox(
                      width: double.infinity,
                      child: FilledButton(
                        onPressed: () => Navigator.pop(context, true),
                        child: const Text('Simpan'),
                      ),
                    ),
                  ],
                ),
              );
            },
          ),
        );
      },
    );

    if (result == true && mounted) {
      setState(() {
        _loading = true;
      });
      final res = await _apiClient.updateStudentAttendance(
        token: widget.token,
        nis: item['nis']?.toString() ?? '',
        date: _selectedDate,
        status: selectedStatus,
        note: noteController.text.trim(),
      );
      if (!res.ok && mounted) {
        ScaffoldMessenger.of(
          context,
        ).showSnackBar(SnackBar(content: Text(res.message)));
      }
      await _load();
    }
  }
}

class _ClassFilter extends StatelessWidget {
  const _ClassFilter({
    required this.value,
    required this.classes,
    required this.onChanged,
  });

  final String value;
  final List<String> classes;
  final ValueChanged<String?> onChanged;

  @override
  Widget build(BuildContext context) {
    return DropdownButtonFormField<String>(
      key: ValueKey(value),
      initialValue: value,
      decoration: const InputDecoration(
        labelText: 'Kelas',
        prefixIcon: Icon(Icons.school_outlined),
      ),
      items: [
        const DropdownMenuItem(value: '', child: Text('Sesuai jadwal')),
        ...classes.map(
          (className) =>
              DropdownMenuItem(value: className, child: Text(className)),
        ),
      ],
      onChanged: onChanged,
    );
  }
}

class _ScheduleCard extends StatelessWidget {
  const _ScheduleCard({required this.schedules});

  final List<Map<String, dynamic>> schedules;

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
              'Jadwal Hari Ini',
              style: Theme.of(
                context,
              ).textTheme.titleMedium?.copyWith(fontWeight: FontWeight.w700),
            ),
            const SizedBox(height: 10),
            if (schedules.isEmpty)
              Text(
                'Tidak ada jadwal di tanggal ini.',
                style: Theme.of(context).textTheme.bodySmall,
              )
            else
              ...schedules.map((schedule) {
                final time =
                    '${schedule['start_time'] ?? '-'} - ${schedule['end_time'] ?? '-'}';
                return Padding(
                  padding: const EdgeInsets.only(bottom: 8),
                  child: Row(
                    children: [
                      const Icon(Icons.menu_book_outlined, size: 20),
                      const SizedBox(width: 10),
                      Expanded(
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text(
                              schedule['subject']?.toString() ?? '-',
                              style: const TextStyle(
                                fontWeight: FontWeight.w700,
                              ),
                            ),
                            Text(
                              '${schedule['class_name'] ?? '-'} | $time',
                              style: Theme.of(context).textTheme.bodySmall,
                            ),
                          ],
                        ),
                      ),
                    ],
                  ),
                );
              }),
          ],
        ),
      ),
    );
  }
}

class _ScheduleFilter extends StatelessWidget {
  const _ScheduleFilter({
    required this.value,
    required this.schedules,
    required this.onChanged,
  });

  final String value;
  final List<Map<String, dynamic>> schedules;
  final ValueChanged<String?> onChanged;

  String _labelFor(Map<String, dynamic> schedule) {
    final className = schedule['class_name']?.toString() ?? '-';
    final subject = schedule['subject']?.toString() ?? '-';
    final start = schedule['start_time']?.toString() ?? '-';
    final end = schedule['end_time']?.toString() ?? '-';
    return '$className | $subject | $start-$end';
  }

  @override
  Widget build(BuildContext context) {
    return DropdownButtonFormField<String>(
      key: ValueKey(value),
      initialValue: value,
      decoration: const InputDecoration(
        labelText: 'Jadwal',
        prefixIcon: Icon(Icons.menu_book_outlined),
      ),
      items: [
        const DropdownMenuItem(value: '', child: Text('Semua jadwal hari ini')),
        ...schedules.map(
          (schedule) => DropdownMenuItem(
            value: schedule['id']?.toString() ?? '',
            child: Text(_labelFor(schedule)),
          ),
        ),
      ],
      onChanged: onChanged,
    );
  }
}

class _SummaryGrid extends StatelessWidget {
  const _SummaryGrid({required this.summary});

  final Map<String, int> summary;

  @override
  Widget build(BuildContext context) {
    return GridView.count(
      crossAxisCount: 2,
      mainAxisSpacing: 10,
      crossAxisSpacing: 10,
      childAspectRatio: 2.25,
      shrinkWrap: true,
      physics: const NeverScrollableScrollPhysics(),
      children: [
        _MetricTile('Hadir', summary['hadir'] ?? 0, Colors.green),
        _MetricTile('Izin', summary['izin'] ?? 0, Colors.orange),
        _MetricTile('Sakit', summary['sakit'] ?? 0, Colors.red),
        _MetricTile('Belum', summary['belum_absen'] ?? 0, Colors.blueGrey),
      ],
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

class _AttendanceTile extends StatelessWidget {
  const _AttendanceTile({required this.item, this.onTap});

  final Map<String, dynamic> item;
  final VoidCallback? onTap;

  @override
  Widget build(BuildContext context) {
    final status = item['status']?.toString() ?? '';
    final time = item['time']?.toString() ?? '';
    final note = item['note']?.toString() ?? '';

    return Card(
      elevation: 0,
      margin: const EdgeInsets.only(bottom: 10),
      child: ListTile(
        onTap: onTap,
        leading: CircleAvatar(
          backgroundColor: _statusColor(status).withValues(alpha: 0.12),
          foregroundColor: _statusColor(status),
          child: Icon(_statusIcon(status)),
        ),
        title: Text(
          item['student_name']?.toString() ?? '-',
          style: const TextStyle(fontWeight: FontWeight.w700),
        ),
        subtitle: Text(
          [
            item['classroom']?.toString() ?? '-',
            'NIS ${item['nis'] ?? '-'}',
            if (time.isNotEmpty) time,
            if (note.isNotEmpty) note,
          ].join(' | '),
        ),
        trailing: _StatusBadge(status: status),
      ),
    );
  }
}

class _StatusBadge extends StatelessWidget {
  const _StatusBadge({required this.status});

  final String status;

  @override
  Widget build(BuildContext context) {
    final color = _statusColor(status);

    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 6),
      decoration: BoxDecoration(
        color: color.withValues(alpha: 0.15),
        borderRadius: BorderRadius.circular(8),
      ),
      child: Text(
        _statusLabel(status),
        style: TextStyle(color: color, fontWeight: FontWeight.w700),
      ),
    );
  }
}

class _EmptyState extends StatelessWidget {
  const _EmptyState({required this.text});

  final String text;

  @override
  Widget build(BuildContext context) {
    return Card(
      elevation: 0,
      child: Padding(
        padding: const EdgeInsets.all(18),
        child: Center(child: Text(text)),
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
    case 'alfa':
    case 'alpha':
    case 'alpa':
      return 'Alpa';
    case 'belum_absen':
      return 'Belum';
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
    case 'alfa':
    case 'alpha':
    case 'alpa':
      return Icons.cancel_outlined;
    default:
      return Icons.hourglass_empty_outlined;
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
    case 'alfa':
    case 'alpha':
    case 'alpa':
      return Colors.redAccent;
    default:
      return Colors.blueGrey;
  }
}

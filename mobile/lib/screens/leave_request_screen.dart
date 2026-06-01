import 'package:flutter/material.dart';

import '../services/api_client.dart';

class LeaveRequestScreen extends StatefulWidget {
  const LeaveRequestScreen({super.key, required this.authToken});

  final String authToken;

  @override
  State<LeaveRequestScreen> createState() => _LeaveRequestScreenState();
}

class _LeaveRequestScreenState extends State<LeaveRequestScreen> {
  final _apiClient = ApiClient();
  final _formKey = GlobalKey<FormState>();
  final _reasonController = TextEditingController();

  bool _loading = true;
  bool _submitting = false;
  String _type = 'izin';
  DateTime _startDate = DateTime.now();
  DateTime _endDate = DateTime.now();
  String _message = '';
  bool _messageOk = false;
  List<Map<String, dynamic>> _requests = [];

  @override
  void initState() {
    super.initState();
    _loadRequests();
  }

  @override
  void dispose() {
    _reasonController.dispose();
    super.dispose();
  }

  Future<void> _loadRequests() async {
    setState(() {
      _loading = true;
      _message = '';
    });

    final result = await _apiClient.fetchStudentLeaveRequests(
      token: widget.authToken,
    );

    if (!mounted) {
      return;
    }

    final data = result.data ?? <String, dynamic>{};
    final rows = data['data'] as List<dynamic>? ?? [];
    setState(() {
      _loading = false;
      _message = result.ok ? '' : result.message;
      _messageOk = result.ok;
      _requests = rows
          .map((item) => Map<String, dynamic>.from(item as Map))
          .toList();
    });
  }

  Future<void> _submit() async {
    if (!(_formKey.currentState?.validate() ?? false)) {
      return;
    }

    setState(() {
      _submitting = true;
      _message = '';
    });

    final result = await _apiClient.submitStudentLeaveRequest(
      token: widget.authToken,
      type: _type,
      startDate: _formatDate(_startDate),
      endDate: _formatDate(_endDate),
      reason: _reasonController.text.trim(),
    );

    if (!mounted) {
      return;
    }

    setState(() {
      _submitting = false;
      _message = result.message;
      _messageOk = result.ok;
    });

    if (result.ok) {
      _reasonController.clear();
      await _loadRequests();
    }
  }

  Future<void> _pickDate({required bool start}) async {
    final initial = start ? _startDate : _endDate;
    final selected = await showDatePicker(
      context: context,
      initialDate: initial,
      firstDate: DateTime.now().subtract(const Duration(days: 30)),
      lastDate: DateTime.now().add(const Duration(days: 60)),
    );

    if (selected == null) {
      return;
    }

    setState(() {
      if (start) {
        _startDate = selected;
        if (_endDate.isBefore(_startDate)) {
          _endDate = selected;
        }
      } else {
        _endDate = selected.isBefore(_startDate) ? _startDate : selected;
      }
    });
  }

  String _formatDate(DateTime date) {
    final month = date.month.toString().padLeft(2, '0');
    final day = date.day.toString().padLeft(2, '0');
    return '${date.year}-$month-$day';
  }

  @override
  Widget build(BuildContext context) {
    return RefreshIndicator(
      onRefresh: _loadRequests,
      child: ListView(
        padding: const EdgeInsets.all(20),
        children: [
          Text(
            'Izin & Sakit',
            style: Theme.of(
              context,
            ).textTheme.headlineSmall?.copyWith(fontWeight: FontWeight.w700),
          ),
          const SizedBox(height: 6),
          Text(
            'Pengajuan dikirim langsung ke halaman persetujuan TU.',
            style: Theme.of(context).textTheme.bodyMedium,
          ),
          const SizedBox(height: 20),
          Card(
            elevation: 0,
            child: Padding(
              padding: const EdgeInsets.all(16),
              child: Form(
                key: _formKey,
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    SegmentedButton<String>(
                      segments: const [
                        ButtonSegment(
                          value: 'izin',
                          icon: Icon(Icons.event_busy_outlined),
                          label: Text('Izin'),
                        ),
                        ButtonSegment(
                          value: 'sakit',
                          icon: Icon(Icons.medical_services_outlined),
                          label: Text('Sakit'),
                        ),
                      ],
                      selected: {_type},
                      onSelectionChanged: (value) {
                        setState(() => _type = value.first);
                      },
                    ),
                    const SizedBox(height: 14),
                    Row(
                      children: [
                        Expanded(
                          child: _DateField(
                            label: 'Mulai',
                            value: _formatDate(_startDate),
                            onTap: () => _pickDate(start: true),
                          ),
                        ),
                        const SizedBox(width: 12),
                        Expanded(
                          child: _DateField(
                            label: 'Sampai',
                            value: _formatDate(_endDate),
                            onTap: () => _pickDate(start: false),
                          ),
                        ),
                      ],
                    ),
                    const SizedBox(height: 14),
                    TextFormField(
                      controller: _reasonController,
                      maxLines: 4,
                      decoration: const InputDecoration(
                        labelText: 'Alasan',
                        alignLabelWithHint: true,
                      ),
                      validator: (value) {
                        if (value == null || value.trim().isEmpty) {
                          return 'Alasan wajib diisi.';
                        }
                        if (value.trim().length < 6) {
                          return 'Alasan terlalu singkat.';
                        }
                        return null;
                      },
                    ),
                    const SizedBox(height: 16),
                    SizedBox(
                      width: double.infinity,
                      child: FilledButton.icon(
                        onPressed: _submitting ? null : _submit,
                        icon: _submitting
                            ? const SizedBox(
                                width: 18,
                                height: 18,
                                child: CircularProgressIndicator(
                                  strokeWidth: 2,
                                ),
                              )
                            : const Icon(Icons.send_outlined),
                        label: const Text('Kirim ke TU'),
                      ),
                    ),
                  ],
                ),
              ),
            ),
          ),
          if (_message.isNotEmpty) ...[
            const SizedBox(height: 12),
            _MessageBanner(message: _message, ok: _messageOk),
          ],
          const SizedBox(height: 20),
          Text(
            'Riwayat Pengajuan',
            style: Theme.of(
              context,
            ).textTheme.titleMedium?.copyWith(fontWeight: FontWeight.w700),
          ),
          const SizedBox(height: 10),
          if (_loading)
            const Center(child: CircularProgressIndicator())
          else if (_requests.isEmpty)
            const _EmptyState(text: 'Belum ada pengajuan izin/sakit.')
          else
            ..._requests.map((request) => _RequestCard(request: request)),
        ],
      ),
    );
  }
}

class _DateField extends StatelessWidget {
  const _DateField({
    required this.label,
    required this.value,
    required this.onTap,
  });

  final String label;
  final String value;
  final VoidCallback onTap;

  @override
  Widget build(BuildContext context) {
    return InkWell(
      onTap: onTap,
      borderRadius: BorderRadius.circular(8),
      child: InputDecorator(
        decoration: InputDecoration(
          labelText: label,
          suffixIcon: const Icon(Icons.calendar_month_outlined),
        ),
        child: Text(value),
      ),
    );
  }
}

class _RequestCard extends StatelessWidget {
  const _RequestCard({required this.request});

  final Map<String, dynamic> request;

  @override
  Widget build(BuildContext context) {
    final status = request['status']?.toString() ?? '';
    final color = _statusColor(status);
    return Card(
      elevation: 0,
      child: Padding(
        padding: const EdgeInsets.all(14),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              children: [
                Icon(
                  request['type'] == 'sakit'
                      ? Icons.medical_services_outlined
                      : Icons.event_busy_outlined,
                  color: color,
                ),
                const SizedBox(width: 10),
                Expanded(
                  child: Text(
                    request['type'] == 'sakit' ? 'Sakit' : 'Izin',
                    style: const TextStyle(fontWeight: FontWeight.w700),
                  ),
                ),
                Chip(
                  label: Text(_statusLabel(status)),
                  backgroundColor: color.withValues(alpha: 0.12),
                  side: BorderSide.none,
                ),
              ],
            ),
            const SizedBox(height: 8),
            Text(
              '${request['start_date'] ?? '-'} sampai ${request['end_date'] ?? '-'}',
            ),
            const SizedBox(height: 6),
            Text(request['reason']?.toString() ?? '-'),
            if ((request['rejection_reason']?.toString() ?? '').isNotEmpty)
              Padding(
                padding: const EdgeInsets.only(top: 6),
                child: Text(
                  'Alasan ditolak: ${request['rejection_reason']}',
                  style: TextStyle(color: Theme.of(context).colorScheme.error),
                ),
              ),
            if ((request['response_note']?.toString() ?? '').isNotEmpty)
              Padding(
                padding: const EdgeInsets.only(top: 6),
                child: Text('Catatan: ${request['response_note']}'),
              ),
          ],
        ),
      ),
    );
  }
}

class _MessageBanner extends StatelessWidget {
  const _MessageBanner({required this.message, required this.ok});

  final String message;
  final bool ok;

  @override
  Widget build(BuildContext context) {
    final color = ok ? Colors.green : Colors.red;
    return Container(
      padding: const EdgeInsets.all(12),
      decoration: BoxDecoration(
        color: color.withValues(alpha: 0.1),
        borderRadius: BorderRadius.circular(8),
      ),
      child: Text(message),
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
    case 'pending_teacher':
      return 'Menunggu Guru';
    case 'pending_admin':
      return 'Menunggu TU';
    case 'approved':
      return 'Disetujui';
    case 'rejected':
      return 'Ditolak';
    default:
      return status;
  }
}

Color _statusColor(String status) {
  switch (status) {
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

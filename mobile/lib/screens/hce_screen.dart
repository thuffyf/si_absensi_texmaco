import 'package:flutter/material.dart';

import '../services/hce_service.dart';
import '../services/nfc_uid_service.dart';

class HceScreen extends StatefulWidget {
  const HceScreen({super.key});

  @override
  State<HceScreen> createState() => _HceScreenState();
}

class _HceScreenState extends State<HceScreen> with WidgetsBindingObserver {
  final _uidService = NfcUidService();
  final _hceService = HceService();

  bool _enabled = false;
  bool _working = false;
  bool _nfcEnabled = true;
  bool _nfcChecking = false;
  String _uid = '';

  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addObserver(this);
    _loadUid();
    _checkNfc();
  }

  Future<void> _loadUid() async {
    final uid = await _uidService.getUid();
    if (!mounted) {
      return;
    }
    setState(() => _uid = uid ?? '');
  }

  @override
  void dispose() {
    _hceService.setEnabled(false);
    WidgetsBinding.instance.removeObserver(this);
    super.dispose();
  }

  @override
  void didChangeAppLifecycleState(AppLifecycleState state) {
    if (state != AppLifecycleState.resumed && _enabled) {
      _hceService.setEnabled(false);
      if (mounted) {
        setState(() => _enabled = false);
      }
    } else if (state == AppLifecycleState.resumed) {
      _checkNfc();
    }
  }

  Future<void> _checkNfc() async {
    setState(() => _nfcChecking = true);
    final enabled = await _hceService.isNfcEnabled();
    if (!mounted) {
      return;
    }
    setState(() {
      _nfcEnabled = enabled;
      _nfcChecking = false;
      if (!enabled) {
        _enabled = false;
      }
    });
  }

  Future<void> _promptEnableNfc() async {
    if (!mounted) {
      return;
    }
    await showDialog<void>(
      context: context,
      builder: (context) => AlertDialog(
        title: const Text('NFC belum aktif'),
        content: const Text(
          'Aktifkan NFC di pengaturan agar HCE bisa dipakai.',
        ),
        actions: [
          TextButton(
            onPressed: () => Navigator.of(context).pop(),
            child: const Text('Batal'),
          ),
          ElevatedButton(
            onPressed: () {
              _hceService.openNfcSettings();
              Navigator.of(context).pop();
            },
            child: const Text('Buka Pengaturan'),
          ),
        ],
      ),
    );
  }

  Future<void> _toggle(bool value) async {
    setState(() => _working = true);
    try {
      if (_uid.isEmpty) {
        if (mounted) {
          ScaffoldMessenger.of(context).showSnackBar(
            const SnackBar(
              content: Text('UID belum tersedia. Hubungi admin TU.'),
            ),
          );
        }
        setState(() {
          _working = false;
          _enabled = false;
        });
        return;
      }
      if (value) {
        final enabled = await _hceService.isNfcEnabled();
        if (!enabled) {
          if (mounted) {
            await _promptEnableNfc();
          }
          setState(() {
            _nfcEnabled = false;
            _working = false;
            _enabled = false;
          });
          return;
        }
      }
      await _hceService.setToken(_uid);
      await _hceService.setEnabled(value);
    } catch (error) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Gagal mengaktifkan HCE: $error')),
        );
      }
    }
    if (!mounted) {
      return;
    }
    setState(() {
      _enabled = value;
      _working = false;
    });
  }

  @override
  Widget build(BuildContext context) {
    return ListView(
      padding: const EdgeInsets.all(20),
      children: [
        Text('Mode NFC/HCE', style: Theme.of(context).textTheme.headlineSmall),
        const SizedBox(height: 8),
        Text(
          'Aktifkan agar HP bisa ditap ke NFC reader admin.',
          style: Theme.of(context).textTheme.bodyMedium,
        ),
        const SizedBox(height: 20),
        if (_nfcChecking)
          Text(
            'Memeriksa status NFC...',
            style: Theme.of(context).textTheme.bodySmall,
          )
        else
          Row(
            children: [
              Icon(
                Icons.nfc,
                size: 18,
                color: _nfcEnabled ? Colors.green : Colors.red,
              ),
              const SizedBox(width: 8),
              Expanded(
                child: Text(
                  _nfcEnabled
                      ? 'NFC aktif di perangkat.'
                      : 'NFC nonaktif. Aktifkan di pengaturan.',
                  style: Theme.of(context).textTheme.bodySmall,
                ),
              ),
              if (!_nfcEnabled)
                TextButton(
                  onPressed: _promptEnableNfc,
                  child: const Text('Aktifkan'),
                ),
            ],
          ),
        const SizedBox(height: 12),
        SwitchListTile(
          value: _enabled,
          onChanged: _working ? null : _toggle,
          title: const Text('Aktifkan NFC HCE'),
          subtitle: Text(_enabled ? 'Siap ditap ke reader.' : 'Belum aktif.'),
        ),
        const SizedBox(height: 20),
        Card(
          child: Padding(
            padding: const EdgeInsets.all(16),
            child: Text(
              _uid.isEmpty
                  ? 'UID belum diatur. Hubungi admin TU agar UID NFC kamu diset.'
                  : 'Langkah: 1) Aktifkan toggle. 2) Buka layar ini. 3) Tempelkan HP ke reader NFC admin sampai berhasil terdeteksi.',
              style: Theme.of(context).textTheme.bodySmall,
            ),
          ),
        ),
      ],
    );
  }
}

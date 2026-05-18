import 'package:flutter_secure_storage/flutter_secure_storage.dart';

class NfcUidService {
  static const _uidKey = 'student_uid';
  static const _storage = FlutterSecureStorage();

  Future<String?> getUid() async {
    final uid = await _storage.read(key: _uidKey);
    return uid != null && uid.isNotEmpty ? uid : null;
  }

  Future<void> saveUid(String uid) async {
    await _storage.write(key: _uidKey, value: uid);
  }

  Future<void> clearUid() async {
    await _storage.delete(key: _uidKey);
  }
}

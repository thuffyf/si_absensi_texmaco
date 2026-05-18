# Aplikasi Siswa (Flutter)

Aplikasi ini dipakai siswa untuk absensi NFC/HCE. UID NFC ditetapkan oleh admin TU di backend dan disimpan di HP setelah login.

## Konsep Utama

- UID NFC berasal dari backend (field `uid_kartu` pada data siswa).
- HP mengemulasikan kartu NFC lewat HCE hanya saat halaman Mode NFC dibuka.
- Tidak ada pendaftaran token dari HP atau generate UID acak.

## Struktur Flow

1. Admin TU mengisi UID di data siswa (web).
2. Siswa login dengan username + password.
3. Aplikasi menyimpan UID dari respons login.
4. Siswa membuka tab **Mode NFC** untuk mengaktifkan HCE.
5. Tap ke reader untuk absensi normal.

## Login Mobile

- **Siswa** login dengan **username + password**.
- **Guru** login dengan **NIP + tanggal lahir**.
- Setelah login, aplikasi menampilkan menu sesuai role:
  - Siswa: Beranda, Mode NFC.
  - Guru: daftar siswa yang tidak hadir.

## Ringkasan Kehadiran Siswa

Aplikasi siswa menampilkan ringkasan kehadiran per bulan dari endpoint:

```
GET /api/mobile/student/summary
Authorization: Bearer <token>
```

Respons berisi jumlah hadir/izin/sakit/alfa dalam periode aktif.

## Konfigurasi

Ubah file `mobile/.env`:

```
API_BASE_URL=http://10.0.2.2:8000/api
API_TIMEOUT_SECONDS=10
```

Gunakan IP LAN server Laravel jika memakai HP fisik.

## Pengelolaan UID

- UID harus unik dan diisi oleh admin TU.
- Jika UID kosong, Mode NFC menampilkan peringatan.

## Menjalankan

```
cd mobile
flutter pub get
flutter run
```

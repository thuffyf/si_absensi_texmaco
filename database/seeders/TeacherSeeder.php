<?php

namespace Database\Seeders;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TeacherSeeder extends Seeder
{
    public function run(): void
    {
        $normalizeWhitespace = function (?string $value): ?string {
            if ($value === null) {
                return null;
            }

            $value = preg_replace('/\s+/', ' ', trim($value));

            return $value === '' ? null : $value;
        };

        $normalizeNip = function (?string $nip) use ($normalizeWhitespace): ?string {
            $nip = $normalizeWhitespace($nip);

            return $nip ? strtoupper($nip) : null;
        };

        $parseTanggalLahir = function (?string $ttl): ?string {
            if (empty($ttl)) {
                return null;
            }

            $ttl = preg_replace('/\s*,\s*/', ', ', trim($ttl));
            $ttl = preg_replace('/\s+/', ' ', $ttl);

            $bulanMap = [
                'JANUARI' => '01',
                'FEBRUARI' => '02',
                'MARET' => '03',
                'APRIL' => '04',
                'MEI' => '05',
                'JUNI' => '06',
                'JULI' => '07',
                'AGUSTUS' => '08',
                'SEPTEMBER' => '09',
                'OKTOBER' => '10',
                'NOVEMBER' => '11',
                'DESEMBER' => '12',
            ];

            // Contoh input: "KARAWANG, 16 MARET 1992"
            if (!preg_match('/(\d{1,2})\s+([A-Z]+)\s+(\d{4})/i', $ttl, $m)) {
                return null;
            }

            $hari = str_pad($m[1], 2, '0', STR_PAD_LEFT);
            $bulanNama = strtoupper(trim($m[2]));
            $tahun = $m[3];

            if (!isset($bulanMap[$bulanNama])) {
                return null;
            }

            return $tahun . '-' . $bulanMap[$bulanNama] . '-' . $hari;
        };

        $autoCounter = 1;
        $autoNip = function () use (&$autoCounter): string {
            $nip = 'AUTO' . str_pad((string) $autoCounter, 3, '0', STR_PAD_LEFT);
            $autoCounter++;
            return $nip;
        };

        $teachers = [
            [
                'nip' => 'PO 500 217 005',
                'name' => 'RIDWAN KUSUMAH WIJAYA, S.Pd,.M.Pd',
                'ttl' => 'KARAWANG, 16 MARET 1992',
                'subject' => 'PAI',
                'role' => 'Waka Bid. Kurikulum',
            ],
            [
                'nip' => 'PO 500 219 039',
                'name' => 'MUHAMAD JALALUDIN, SPd',
                'ttl' => 'KARAWANG, 16 JUNI 1992',
                'subject' => 'MATEMATIKA',
                'role' => 'Waka Bid Kesiswaan',
            ],
            [
                'nip' => 'PO 500 221 070',
                'name' => 'MUHAMAD GOFUR, S.T., M.T',
                'ttl' => 'INDRAMAYU, 21 JUNI 1987',
                'subject' => 'PRODUKTIF TMI',
                'role' => 'Waka Bid Hubinmas',
            ],
            [
                'nip' => 'PO 500 219 022',
                'name' => 'NURUL MILLATINA KAMILAH, S.Pd',
                'ttl' => 'KARAWANG, 18 MEI 1997',
                'subject' => 'B. INGGRIS',
                'role' => 'Staff Kurikulum',
            ],
            [
                'nip' => 'PO 500 219 051',
                'name' => 'BAKTI KURNIAWAN, ST',
                'ttl' => 'KEDIRI, 16 JULI 1971',
                'subject' => 'PRODUKTIF TEI',
                'role' => 'KKK TEI',
            ],
            [
                'nip' => 'PO 500 219 019',
                'name' => 'SURADHI SOTAN SOBARI, S.T',
                'ttl' => 'KARAWANG, 15 APRIL 1993',
                'subject' => 'PRODUKTIF TMI',
                'role' => 'KKK TMI',
            ],
            [
                'nip' => 'PO 500 219 031',
                'name' => 'MUHAMMAD ILHAM BINTANG S.KOM',
                'ttl' => 'SURABAYA, 4 DESEMBER 1995',
                'subject' => 'PRODUKTIF TKJ',
                'role' => 'KKK TKJ',
            ],
            [
                'nip' => 'PO 500 219 033',
                'name' => 'ALFI ARISANDY, S.E',
                'ttl' => 'KARAWANG, 18 JULI 1996',
                'subject' => 'PRODUKTIF OTKP',
                'role' => 'KKK OTKP',
            ],
            [
                'nip' => 'PO 500 419 038',
                'name' => 'FIRMANSYAH SOLEHUDIN',
                'ttl' => 'KARAWANG, 25 AGUSTUS 1994',
                'subject' => 'PRODUKTIF TBSM',
                'role' => 'Guru',
            ],
            [
                'nip' => 'PO 500 419 042',
                'name' => 'ANDRI WIBOWO, S.T',
                'ttl' => 'KARAWANG ,17 MARET 1992',
                'subject' => 'PRODUKTIF TBSM',
                'role' => 'Guru',
            ],
            [
                'nip' => 'PO 500 220 058',
                'name' => 'NURUL HIDAYAT, S.T',
                'ttl' => 'KARAWANG, 14 APRIL 1999',
                'subject' => 'PRODUKTIF TBSM',
                'role' => 'KKK TBSM',
            ],
            [
                'nip' => 'PO 500 419 043',
                'name' => 'YOPPI SYAFRUDIN, S.T',
                'ttl' => 'KARAWANG, 01 AGUSTUS 1977',
                'subject' => 'PRODUKTIF TBSM',
                'role' => 'Guru',
            ],
            [
                'nip' => 'PO 500 419 046',
                'name' => 'FAUZIAH WIDYASTUTI, S.Pd',
                'ttl' => 'JAKARTA, 31 AGUSTUS 1996',
                'subject' => 'KIMIA',
                'role' => 'Guru',
            ],
            [
                'nip' => 'PO 500 220 061',
                'name' => 'ALFI KHOERUDIN YUSUF, ST',
                'ttl' => 'KARAWANG, 31 OKTOBER 1992',
                'subject' => 'PRODUKTIF TKJ',
                'role' => 'Staff Kesiswaan',
            ],
            [
                'nip' => 'PO 500 420 060',
                'name' => 'ARJUN ALIVI KASUGI , S.T',
                'ttl' => 'BEKASI, 25 JUNI 1996',
                'subject' => 'PRODUKTIF TMI',
                'role' => 'Staff Sarana dan kepegawaian',
            ],
            [
                'nip' => 'PO 500 220 067',
                'name' => 'FITRIA NURLAELA, S.Pd',
                'ttl' => 'JAKATA, 25 SEPTEMBER 1976',
                'subject' => 'PRODUKTIF OTKP',
                'role' => 'Staff Hubinmas',
            ],
            [
                'nip' => 'PO 500 421 073',
                'name' => 'YOGA GANDARA MULYANA,  S.Pd.,M.Pd',
                'ttl' => 'KARAWANG,29 OKTOBER 1996',
                'subject' => 'B.INDONESIA',
                'role' => 'Staff Kesiswaan',
            ],
            [
                'nip' => 'PO 500 221 075',
                'name' => 'YANI RUBIANTINI, S.Pd',
                'ttl' => 'SUBANG, 13 NOVEMBER 1979',
                'subject' => 'B.INDONESIA',
                'role' => 'Staff Kurikulum',
            ],
            [
                'nip' => 'PO 500 421 086',
                'name' => 'YUHANI, S.Kom',
                'ttl' => 'KARAWANG, 16 OKTOBER 1994',
                'subject' => 'SIMDIG',
                'role' => 'Anggota Stp2k',
            ],
            [
                'nip' => 'PO 500 421 089',
                'name' => 'UBAEDA CITRADIANI S.Pd',
                'ttl' => 'KARAWANG, 24 AGUSTUS 1999',
                'subject' => 'SEJARAH INDONESIA',
                'role' => 'Guru',
            ],
            [
                'nip' => 'PO 500 221 092',
                'name' => 'MARISA SETIANINGSIH, S.Pd',
                'ttl' => 'KARAWANG, 17 OKTOBER 1996',
                'subject' => 'PAI',
                'role' => 'Guru',
            ],
            [
                'nip' => 'PO 500 422 095',
                'name' => 'IRFAN ABDUL MAJID, S.T',
                'ttl' => 'PURWAKARTA, 15 NOVEMBER 1997',
                'subject' => 'PRODUKTIF TBSM',
                'role' => 'Anggota Stp2k',
            ],
            [
                'nip' => 'PO 500 422 097',
                'name' => 'NURUL IZNI SYAHIDA , S.Kom',
                'ttl' => 'KARAWANG, 27 OKTOBER 1997',
                'subject' => 'PRODUKTIF TKJ',
                'role' => 'Guru',
            ],
            [
                'nip' => 'PO 500 423 101',
                'name' => 'TEGUH EDIANSYAH, S.T',
                'ttl' => 'KARAWANG, 12 JANUARI 1995',
                'subject' => 'PRODUKTIF TBSM',
                'role' => 'Guru',
            ],
            [
                'nip' => 'PO 500 423 102',
                'name' => 'ULFAH KHARIYAH , S.Kom',
                'ttl' => 'KARAWANG, 12 DESEMBER 1994',
                'subject' => 'PRODUKTIF TKJ',
                'role' => 'Guru',
            ],
            [
                'nip' => 'PO 500 423 103',
                'name' => 'SUSI SUSYANTI HATIMAH, S.T',
                'ttl' => 'BANDUNG, 27 DESEMBER 1999',
                'subject' => 'PRODUKTIF  TEI',
                'role' => 'Guru',
            ],
            [
                'nip' => 'PO 500 423 104',
                'name' => 'NAJIB HARDIANSYAH, S.T',
                'ttl' => 'BANDUNG, 02 JULI 2001',
                'subject' => 'PRODUKTIF TEI',
                'role' => 'Guru',
            ],
            [
                'nip' => 'PO 500 423 107',
                'name' => 'ROLASTUAN',
                'ttl' => 'JAKARTA, 08 AGUSTUS 2002',
                'subject' => 'PRODUKTIF TKJ',
                'role' => 'Guru',
            ],
            [
                'nip' => 'PO 500 424 109',
                'name' => 'BELLA SAADAH, S.Pd',
                'ttl' => 'SUBANG, 11 APRIL 2024',
                'subject' => 'BIMBINGAN KONSELING',
                'role' => 'Guru BK',
            ],
            [
                'nip' => 'PO 500 424 110',
                'name' => 'WIJI ASTUTI, S.T',
                'ttl' => 'KEBUMEN, 14 SEPTEMBER 1998',
                'subject' => 'PRODUKTIF TMI',
                'role' => 'Guru Produktif',
            ],
            [
                'nip' => 'PO 500 424 111',
                'name' => 'SANTIKO DWI KARUNIA SUCI, S.Si',
                'ttl' => 'TEGAL, 21  JANUARI 1997',
                'subject' => 'MATEMATIKA',
                'role' => 'Guru',
            ],
            [
                'nip' => 'PO 500 424 112',
                'name' => 'EKA OCTAVIANI, S.T',
                'ttl' => 'JAKARTA, 30 OKTOBER 1982',
                'subject' => 'PRODUKTIF TKJ',
                'role' => 'Guru',
            ],
            [
                'nip' => $autoNip(),
                'name' => 'APRI NURROHMAT, S.T',
                'ttl' => 'KARAWANG,21 APRIL 1994',
                'subject' => 'PRODUKTIF TBSM',
                'role' => 'Guru Produktif',
            ],
            [
                'nip' => $autoNip(),
                'name' => 'MUHAMMAD ABDUL ROJAQ, S.Pd',
                'ttl' => 'GRESIK, 18 AGUSTUS 1998',
                'subject' => 'PJOK',
                'role' => 'Guru',
            ],
            [
                'nip' => $autoNip(),
                'name' => 'VICA HASNA  SYAFIYYAH, S.Pd',
                'ttl' => 'TEGAL, 12 JANUARI 2002',
                'subject' => 'BAHASA INGGRIS',
                'role' => 'Guru',
            ],
            [
                'nip' => $autoNip(),
                'name' => 'FIRLY MASTUROH, S.M',
                'ttl' => 'KARAWANG ,21 MARET 2001',
                'subject' => 'KWU',
                'role' => 'Guru',
            ],
            [
                'nip' => $autoNip(),
                'name' => 'ANISA WIDIASTUTI',
                'ttl' => 'PURWAKARTA, 17 AGUSTUS 2000',
                'subject' => 'BAHASA JAPA',
                'role' => 'Guru',
            ],
        ];

        foreach ($teachers as $teacher) {
            $teacher['nip'] = $normalizeNip($teacher['nip'] ?? null);
            $teacher['name'] = $normalizeWhitespace($teacher['name'] ?? null);
            $teacher['subject'] = $normalizeWhitespace($teacher['subject'] ?? null);
            $teacher['role'] = $normalizeWhitespace($teacher['role'] ?? null);
            $teacher['date_of_birth'] = $parseTanggalLahir($teacher['ttl'] ?? null);
            unset($teacher['ttl']);

            // Email harus valid (tanpa spasi/karakter aneh)
            $emailPrefix = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $teacher['nip']));
            $teacher['email'] = !empty($emailPrefix) ? ($emailPrefix . '@texmaco.sch.id') : null;

            if (!empty($teacher['date_of_birth'])) {
                // Password default = tanggal lahir (YYYY-MM-DD) agar mudah reset awal
                $teacher['password'] = Hash::make($teacher['date_of_birth']);
            } else {
                $teacher['password'] = null;
            }

            $teacher['phone'] = $teacher['phone'] ?? null;
            $teacher['status'] = $teacher['status'] ?? 'aktif';

            $teacherModel = Teacher::updateOrCreate(
                ['nip' => $teacher['nip']],
                $teacher
            );

            if (! empty($teacherModel->email)) {
                $existingUser = User::query()->where('email', $teacherModel->email)->first();

                // Jangan menimpa akun admin/TU jika ada email yang sama.
                if (! $existingUser || ! in_array($existingUser->role, ['admin', 'tata_usaha'], true)) {
                    User::updateOrCreate(
                        ['email' => $teacherModel->email],
                        [
                            'name' => $teacherModel->name,
                            'role' => 'guru',
                            'password' => $teacherModel->date_of_birth?->toDateString() ?? Str::random(40),
                        ]
                    );
                }
            }
        }
    }
}

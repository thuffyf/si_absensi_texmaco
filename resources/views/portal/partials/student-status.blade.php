@php
    if (! function_exists('portalStatusLabel')) {
        function portalStatusLabel(?string $status): string
        {
            return match ($status) {
                'hadir' => 'Hadir',
                'izin' => 'Izin',
                'sakit' => 'Sakit',
                'alpha', 'alfa', 'alpa' => 'Alpa',
                default => $status ?: '-',
            };
        }
    }

    if (! function_exists('portalStatusBadge')) {
        function portalStatusBadge(?string $status): string
        {
            return match ($status) {
                'hadir' => 'bg-emerald-100 text-emerald-700 ring-emerald-200',
                'izin' => 'bg-amber-100 text-amber-700 ring-amber-200',
                'sakit' => 'bg-rose-100 text-rose-700 ring-rose-200',
                'alpha', 'alfa', 'alpa' => 'bg-slate-200 text-slate-700 ring-slate-300',
                default => 'bg-slate-100 text-slate-500 ring-slate-200',
            };
        }
    }

    if (! function_exists('portalStatusDot')) {
        function portalStatusDot(?string $status): string
        {
            return match ($status) {
                'hadir' => 'bg-emerald-500',
                'izin' => 'bg-amber-500',
                'sakit' => 'bg-rose-500',
                'alpha', 'alfa', 'alpa' => 'bg-slate-500',
                default => 'bg-slate-400',
            };
        }
    }

    if (! function_exists('portalRequestLabel')) {
        function portalRequestLabel(?string $status): string
        {
            return match ($status) {
                'pending_teacher' => 'Menunggu Guru',
                'pending_admin' => 'Menunggu TU',
                'approved' => 'Disetujui',
                'rejected' => 'Ditolak',
                default => $status ?: '-',
            };
        }
    }

    if (! function_exists('portalRequestBadge')) {
        function portalRequestBadge(?string $status): string
        {
            return match ($status) {
                'approved' => 'bg-emerald-100 text-emerald-700 ring-emerald-200',
                'rejected' => 'bg-rose-100 text-rose-700 ring-rose-200',
                'pending_teacher', 'pending_admin' => 'bg-amber-100 text-amber-700 ring-amber-200',
                default => 'bg-slate-100 text-slate-500 ring-slate-200',
            };
        }
    }

    if (! function_exists('portalFormatDate')) {
        function portalFormatDate($date, string $format = 'd M Y'): string
        {
            if (! $date) {
                return '-';
            }

            return $date->locale('id')->translatedFormat($format);
        }
    }

    if (! function_exists('portalStorageUrl')) {
        /**
         * Menghasilkan URL yang benar untuk file di storage.
         * Mendukung cPanel hosting dengan folder storage_public di public_html.
         */
        function portalStorageUrl(?string $path): ?string
        {
            if (! $path) {
                return null;
            }

            return \App\Support\PublicStorageUrl::storageUrl($path);
        }
    }
@endphp

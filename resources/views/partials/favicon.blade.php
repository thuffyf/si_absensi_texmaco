@php
    $faviconVersion = is_file(public_path('favicon.ico'))
        ? (string) filemtime(public_path('favicon.ico'))
        : '1';
@endphp
<link rel="icon" href="{{ asset('favicon.ico') }}?v={{ $faviconVersion }}" type="image/x-icon">

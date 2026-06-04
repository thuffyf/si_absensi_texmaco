@php
    $faviconVersion = is_file(public_path('favicon.ico'))
        ? (string) filemtime(public_path('favicon.ico'))
        : '1';
    $faviconQuery = '?v=' . $faviconVersion;
@endphp
<link rel="shortcut icon" href="{{ asset('favicon.ico') }}{{ $faviconQuery }}" type="image/x-icon">
<link rel="icon" href="{{ asset('favicon.ico') }}{{ $faviconQuery }}" type="image/x-icon">
<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32.png') }}{{ $faviconQuery }}">
<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16.png') }}{{ $faviconQuery }}">
<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}{{ $faviconQuery }}">

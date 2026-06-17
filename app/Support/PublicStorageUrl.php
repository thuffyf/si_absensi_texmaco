<?php

namespace App\Support;

class PublicStorageUrl
{
    public static function storageUrl(?string $path): ?string
    {
        $normalizedPath = self::normalizePath($path);

        if (! $normalizedPath) {
            return null;
        }

        return self::appBaseUrl() . '/' . self::publicDirectory() . '/' . $normalizedPath;
    }

    public static function normalizePath(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        $normalizedPath = str_replace('\\', '/', trim($path));
        $normalizedPath = ltrim($normalizedPath, '/');
        $normalizedPath = preg_replace('#^(storage_public|storage|public)/#', '', $normalizedPath) ?? $normalizedPath;

        return $normalizedPath !== '' ? $normalizedPath : null;
    }

    public static function appBaseUrl(): string
    {
        if (app()->bound('request')) {
            $request = request();

            if ($request && $request->getSchemeAndHttpHost()) {
                return rtrim($request->getSchemeAndHttpHost(), '/');
            }
        }

        $configuredUrl = config('app.url', env('APP_URL', 'http://localhost'));
        $configuredUrl = trim((string) $configuredUrl, " \t\n\r\0\x0B`'\"");

        return rtrim($configuredUrl, '/');
    }

    public static function publicDirectory(): string
    {
        // Use config instead of env for cache compatibility
        $storagePublicDirectory = config('filesystems.storage_public_directory');

        if (!$storagePublicDirectory || $storagePublicDirectory === '') {
            return 'storage';
        }

        return $storagePublicDirectory;
    }
}

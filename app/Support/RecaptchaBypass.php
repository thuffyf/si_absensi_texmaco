<?php

namespace App\Support;

use Illuminate\Http\Request;

class RecaptchaBypass
{
    public static function enabled(?Request $request = null): bool
    {
        if (! config('services.recaptcha.bypass_on_local', true)) {
            return false;
        }

        if (app()->environment('local')) {
            return true;
        }

        $request ??= request();

        if (! $request) {
            return false;
        }

        $host = strtolower((string) $request->getHost());

        if (in_array($host, ['localhost', '127.0.0.1', '[::1]'], true)) {
            return true;
        }

        // Laragon / local dev umumnya memakai domain *.test, *.local, atau *.localhost.
        return str_ends_with($host, '.test')
            || str_ends_with($host, '.local')
            || str_ends_with($host, '.localhost');
    }
}

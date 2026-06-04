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

        return in_array($request->getHost(), ['localhost', '127.0.0.1', '[::1]'], true);
    }
}

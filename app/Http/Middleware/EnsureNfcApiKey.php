<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureNfcApiKey
{
    public function handle(Request $request, Closure $next): Response
    {
        $expected = config('services.nfc.api_key');
        $provided = $request->header('X-NFC-API-KEY') ?: $request->bearerToken();

        if (!$expected || !$provided || !hash_equals((string) $expected, (string) $provided)) {
            return response()->json(['message' => 'API key alat NFC tidak valid.'], 401);
        }

        return $next($request);
    }
}

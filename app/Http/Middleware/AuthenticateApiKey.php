<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\ApiKey;
use Illuminate\Http\Request;

class AuthenticateApiKey
{
    public function handle(Request $request, Closure $next)
    {
        $apiKey = $request->header('X-API-KEY');
        if (!$apiKey) {
            return response()->json(['message' => 'API key is missing.'], 401);
        }

        $apiKeyModel = ApiKey::where('api_key', $apiKey)->first();

        if (!$apiKeyModel) {
            return response()->json(['message' => 'Invalid API key.'], 401);
        }

        if ($apiKeyModel->status !== 'active') {
            return response()->json(['message' => 'API key is inactive.'], 401);
        }

        return $next($request);
    }
}

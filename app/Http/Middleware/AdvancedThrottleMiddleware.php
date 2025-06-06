<?php
namespace App\Http\Middleware;

use Illuminate\Routing\Middleware\ThrottleRequests;

class AdvancedThrottleMiddleware extends ThrottleRequests
{
    protected function resolveRequestSignature($request)
    {
        $userAgent = $request->header('User-Agent') ?: 'unknown-user-agent';
        $ip = $request->ip() ?: 'unknown-ip';
        $sessionId = $request->session()->getId() ?: 'unknown-session';
        $userId = $request->user() ? $request->user()->id : 'guest';

        return $userAgent . '|' . $ip . '|' . $sessionId . '|' . $userId;
    }
}
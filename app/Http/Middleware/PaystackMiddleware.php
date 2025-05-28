<?php

namespace App\Http\Middleware;

use Closure;
use RuntimeException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class PaystackMiddleware
{
    protected $whitelistedIPs = [
        '52.31.139.75',
        '52.49.173.169',
        '52.214.14.220',
    ];
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $isValidIp = $this->shouldProcess($request);

        if (!$isValidIp) {
            Log::warning('Rejected request from IP: ' . $request->ip());
            return response()->json(['error' => 'Invalid IP'], 403);
        }

        $isValidSignature = $this->validateSignature($request);

        if (!$isValidSignature) {
            return response()->json(['error' => 'Invalid Signature'], 403);
        }

        return $next($request);
    }


    public function shouldProcess(Request $request): bool
    {
        // Get the IP address from the request

        $requestIp = $request->ip();

        // Check if the request IP is in the list of whitelisted IPs
        return in_array($requestIp, $this->whitelistedIPs);
    }

    public function validateSignature(Request $request): bool
    {
        $signature = $request->header('x-paystack-signature');
        if (!$signature) {
            Log::warning('Invalid signature header from IP: ' . $request->ip());
            return false;
        }
        $signingSecret = config('services.paystack.live_key2');
        if (empty($signingSecret)) {
            Log::critical('Paystack signing secret is not configured.');
            throw new RuntimeException('Paystack signing secret is not configured.');
        }
        $computedSignature = hash_hmac('sha512', $request->getContent(), $signingSecret);
        return hash_equals($signature, $computedSignature);
    }
}

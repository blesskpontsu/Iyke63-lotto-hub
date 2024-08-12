<?php

namespace App\Handler;

use App\Models\Ip;
use Illuminate\Http\Request;
use Spatie\WebhookClient\WebhookProfile\WebhookProfile;

class ShouldProcessCallHandler implements WebhookProfile
{
    protected $whitelistedIPs = [
        '52.31.139.75',
        '52.49.173.169',
        '52.214.14.220',
    ];

    public function shouldProcess(Request $request): bool
    {
        // Get the IP address from the request
        $forwardedIp = $request->header('X-Forwarded-For');
        $requestIP = $forwardedIp;

        // Check if the request IP is in the list of whitelisted IPs
        return in_array($requestIP, $this->whitelistedIPs);
    }
}

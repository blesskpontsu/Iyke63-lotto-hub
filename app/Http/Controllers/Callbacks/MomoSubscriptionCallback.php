<?php

namespace App\Http\Controllers\Callbacks;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MomoSubscriptionCallback extends Controller
{
    public function handle(Request $request)
    {
        $data = $request->json()->all();

        Log::info('data', $data);
        Log::info('request', $request->all());
    }
}

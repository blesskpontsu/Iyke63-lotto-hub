<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Redirect;
use App\Interfaces\MustVerifySubscription;
use Symfony\Component\HttpFoundation\Response;

class ActiveSubscriptionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $redirectToRoute = null): Response
    {
        if (
            !$request->user() ||
            ($request->user() instanceof MustVerifySubscription &&
                !$request->user()->hasActiveSubscription())
        ) {
            return $request->expectsJson()
                ? abort(403, 'You haven\'t subscribed yet or your subscription has expired. Please Subscribe!')
                : tap(Redirect::guest(URL::route($redirectToRoute ?: 'plans')), function () {
                    session()->forget('url.intended');
                });
        }

        return $next($request);
    }
}

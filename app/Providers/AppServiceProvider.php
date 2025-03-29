<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->response(function (Request $request, array $headers) {
                return response('API calling over limit...', 429, $headers);
            })->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('non_member', function (Request $request) {
            return Limit::perHour(5)->response(function (Request $request, array $headers) {
                return response('API calling over limit...', 429, $headers);
            })->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('member', function (Request $request) {
            return $request->user()->isVip()
                ? Limit::none()
                : Limit::perMinute(100)->by($request->ip());
        });
    }
}

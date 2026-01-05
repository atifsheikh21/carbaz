<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;
use Modules\GeneralSetting\Entities\Setting;

class Timezone
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $setting = Cache::remember('global.setting', 60, function () {
            return Setting::first();
        });
        if ($setting && $setting->timezone) {
            config(['app.timezone' => $setting->timezone]);
            date_default_timezone_set($setting->timezone);
        } else {
            config(['app.timezone' => 'UTC']);
            date_default_timezone_set('UTC');
        }

        return $next($request);
    }
}

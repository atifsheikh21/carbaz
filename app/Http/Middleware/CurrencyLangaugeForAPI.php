<?php

namespace App\Http\Middleware;

use Closure;
use Session, Config;
use Illuminate\Http\Request;
use Modules\Language\Entities\Language;
use Symfony\Component\HttpFoundation\Response;
use Log;
class CurrencyLangaugeForAPI
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $lang_code = 'en';

        if($request->lang_code){
            $is_exist = Language::where('lang_code', $request->lang_code)->first();
            if($is_exist){
                $lang_code = $request->lang_code;
            }
        }

        Session::put('front_lang', $lang_code);

        app()->setLocale($lang_code);

        return $next($request);
    }
}

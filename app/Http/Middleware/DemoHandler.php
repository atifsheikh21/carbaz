<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Route, Log;

class DemoHandler
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(Route::is('user-login') || Route::is('logout') || Route::is('admin.store-login') || Route::is('admin.logout') || Route::is('get-available-schedule')){
            return $next($request);
         }else{
            if(env('APP_MODE') == 'DEMO'){
                if(Route::is('user.add-to-wishlist')){
                     $notification = trans('translate.This Is Demo Version. You Can Not Change Anything');
                        $notification=array('messege'=>$notification,'alert-type'=>'error');
                        return redirect()->back()->with($notification);
                }
                
                if($request->isMethod('post') || $request->isMethod('delete') || $request->isMethod('put') || $request->isMethod('patch')){

                    if ($request->ajax()) {
                        return response()->json(['message' => 'This Is Demo Version. You Can Not Change Anything'],403);
                    } else {

                        $notification = trans('translate.This Is Demo Version. You Can Not Change Anything');
                        $notification=array('messege'=>$notification,'alert-type'=>'error');
                        return redirect()->back()->with($notification);
                    }
                }
            }
         }
        return $next($request);
    }
}

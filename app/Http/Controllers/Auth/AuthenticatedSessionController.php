<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\User;
use Hash, Session, Str, Config;
use Socialite;

use Modules\GeneralSetting\Entities\SocialLoginInfo;


class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        $social_login = SocialLoginInfo::first();

        return view('auth.login', ['social_login' => $social_login]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {

        $credential=[
            'email'=> $request->email,
            'password'=> $request->password
        ];

        $user = User::where('email',$request->email)->first();

        if($user){
            if($user->status == 'enable'){
                if($user->is_banned == 'no'){

                    if($user->email_verified_at == null){
                        $notification = trans('translate.Please verify your email');
                        $notification = array('messege'=>$notification,'alert-type'=>'error');
                        return redirect()->back()->with($notification);
                    }

                    if(Hash::check($request->password,$user->password)){
                        if(Auth::guard('web')->attempt($credential,$request->remember)){

                            $notification= trans('translate.Login Successfully');
                            $notification=array('messege'=>$notification,'alert-type'=>'success');

                            return redirect()->route('user.dashboard')->with($notification);
                        }
                    }else{
                        $notification= trans('translate.Invalid Password');
                        $notification=array('messege'=>$notification,'alert-type'=>'error');
                        return redirect()->back()->with($notification);
                    }
                }else{
                    $notification= trans('translate.Inactive account');
                    $notification=array('messege'=>$notification,'alert-type'=>'error');
                    return redirect()->back()->with($notification);
                }

            }else{
                $notification= trans('translate.Inactive account');
                $notification=array('messege'=>$notification,'alert-type'=>'error');
                return redirect()->back()->with($notification);
            }
        }else{
            $notification= trans('translate.Invalid Email');
            $notification=array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }

    }


    public function redirect_to_google(){
        $login_info = SocialLoginInfo::first();

        \Config::set('services.google.client_id', $login_info->gmail_client_id);
        \Config::set('services.google.client_secret', $login_info->gmail_secret_id);
        \Config::set('services.google.redirect', $login_info->gmail_redirect_url);

        return Socialite::driver('google')->redirect();

    }

    public function google_callback(){

        $login_info = SocialLoginInfo::first();

        \Config::set('services.google.client_id', $login_info->gmail_client_id);
        \Config::set('services.google.client_secret', $login_info->gmail_secret_id);
        \Config::set('services.google.redirect', $login_info->gmail_redirect_url);

        $user = Socialite::driver('google')->user();
        $user = $this->create_user($user,'google');
        auth()->login($user);

        $notification= trans('translate.Login Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');

        return redirect()->route('user.dashboard')->with($notification);

    }

    public function redirect_to_facebook(){
        $login_info = SocialLoginInfo::first();

        \Config::set('services.facebook.client_id', $login_info->facebook_client_id);
        \Config::set('services.facebook.client_secret', $login_info->facebook_secret_id);
        \Config::set('services.facebook.redirect', $login_info->facebook_redirect_url);

        return Socialite::driver('facebook')->redirect();
    }

    public function facebook_callback(){

        $login_info = SocialLoginInfo::first();

        \Config::set('services.facebook.client_id', $login_info->facebook_client_id);
        \Config::set('services.facebook.client_secret', $login_info->facebook_secret_id);
        \Config::set('services.facebook.redirect', $login_info->facebook_redirect_url);

        $user = Socialite::driver('facebook')->user();
        $user = $this->create_user($user,'facebook');
        auth()->login($user);

        $notification= trans('translate.Login Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');

        return redirect()->route('user.dashboard')->with($notification);

    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $notification= trans('translate.Logout Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('login')->with($notification);

    }

    public function create_user($get_info, $provider){
        $user = User::where('email', $get_info->email)->first();
        if (!$user) {

            $user = User::create([
                'name'     => $get_info->name,
                'username' => Str::slug($get_info->name).'-'.date('Ymdhis'),
                'email'    => $get_info->email,
                'provider' => $provider,
                'provider_id' => $get_info->id,
                'status' => 'enable',
                'is_banned' => 'no',
                'email_verified_at' => date('Y-m-d H:i:s'),
                'verification_token' => null,
            ]);

        }
        return $user;
    }
}

<?php

namespace App\Http\Controllers\API\Auth;

use Exception;
use App\Models\User;
use App\Rules\Captcha;
use Auth, Hash, Str, Mail;
use App\Helper\EmailHelper;
use App\Helpers\MailHelper;
use Illuminate\Http\Request;
use App\Mail\UserForgetPassword;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Modules\GeneralSetting\Entities\EmailTemplate;
use Modules\GlobalSetting\App\Models\GlobalSetting;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/buyer/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:api')->except('userLogout');
    }

    public function custom_login_page(){

        return view('auth.login');
    }

    public function store_login(Request $request){

        $rules = [
            'email' => 'required',
            'password' => 'required',
            'g-recaptcha-response'=>new Captcha()
        ];

        $custom_error = [
            'email.required' => trans('translate.Email is required'),
            'password.required' => trans('translate.Password is required'),
        ];

        $this->validate($request, $rules, $custom_error);


        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        $user = User::where('email', $request->email)->first();

        if($user){
            if($user->status == $user::STATUS_ACTIVE && $user->is_banned == $user::BANNED_INACTIVE){
                if($user->email_verified_at != null){
                    if($user->provider){
                        $notify_message = trans('translate.Please try to login with social media');
                        return response()->json([
                            'message' => $notify_message
                        ],403);
                    }

                    if($user->feez_status == 1){
                        $notify_message = trans('translate.Your account is in freeze mode. Please contact the admin.');
                        return response()->json([
                            'message' => $notify_message
                        ],403);
                    }

                    if(Hash::check($request->password, $user->password)){
                        if($token = Auth::guard('api')->attempt($credentials)){


                            $user = User::select('id', 'username', 'name', 'image', 'status', 'is_banned', 'is_dealer', 'designation', 'address', 'phone', 'kyc_status')->where('id', $user->id)->first();

                            if($user->is_dealer == 1){
                                return $this->respondWithToken($token, $user, 'dealer');

                            }else{
                                return $this->respondWithToken($token, $user, 'user');
                            }



                        }else{
                            return response()->json(['message' => 'Unauthorized'], 401);
                        }
                    }else{
                        $notify_message = trans('translate.Credential does not match');
                        return response()->json([
                            'message' => $notify_message
                        ],403);
                    }
                }else{
                    $notify_message = trans('translate.Please verify your email');
                    return response()->json([
                        'message' => $notify_message
                    ],403);

                }

            }else{
                $notify_message = trans('translate.Inactive your account');
                return response()->json([
                    'message' => $notify_message
                ],403);
            }
        }else{
            $notify_message = trans('translate.Email not found');
            return response()->json([
                'message' => $notify_message
            ],403);
        }
    }

    public function userLogout(){

        Auth::guard('api')->logout();

        $notification= trans('translate.Logout Successfully');

        return response()->json([
            'message' => $notification,
        ]);

    }

    protected function respondWithToken($token,$user, $user_type)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user' => $user,
            'user_type' => $user_type,
        ]);
    }

    public function send_custom_forget_pass(Request $request){

        $rules = [
            'email' => 'required',
            'g-recaptcha-response'=>new Captcha()
        ];

        $custom_error = [
            'email.required' => trans('translate.Email is required'),
        ];

        $this->validate($request, $rules, $custom_error);

        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        $user = User::where('email', $request->email)->first();

        if($user){

            MailHelper::setMailConfig();

            $user->forget_password_otp = random_int(100000, 999999);
            $user->save();

            try{

                $template = EmailTemplate::where('id', 13)->first();

                if($template){
                    $subject = $template->subject;
                    $message = $template->description;
                    $message = str_replace('{{user_name}}',$user->name,$message);
                    $message = str_replace('{{reset_otp}}',$user->forget_password_otp,$message);
                    Mail::to($user->email)->send(new UserForgetPassword($message,$subject,$user));
                }
            }catch(Exception $ex){
                Log::info($ex->getMessage());
            }

            $notify_message= trans('translate.A password reset OTP has been send to your mail');

            return response()->json([
                'message' => $notify_message
            ]);

        }else{
            $notify_message = trans('translate.Email not found');
            return response()->json([
                'message' => $notify_message
            ],403);
        }
    }

    public function verify_custom_reset_password(Request $request){

        $rules = [
            'email'=>'required',
            'otp'=>'required',
        ];
        $customMessages = [
            'email.required' => trans('translate.Email is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $user = User::select('id','name','email','forget_password_otp')->where('forget_password_otp', $request->otp)->where('email', $request->email)->first();


        if(!$user){
            $notify_message = trans('translate.Invalid token, please try again');
            return response()->json([
                'message' => $notify_message
            ],403);
        }

        $notify_message = trans('translate.OTP verified');
        return response()->json([
            'message' => $notify_message
        ]);

    }

    public function store_reset_password(Request $request){

        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'confirmed', 'min:4', 'max:100'],
            'otp' => ['required'],
            'g-recaptcha-response'=>new Captcha()

        ],[
            'email.required' => trans('translate.Email is required'),
            'email.unique' => trans('translate.Email already exist'),
            'password.required' => trans('translate.Password is required'),
            'password.confirmed' => trans('translate.Confirm password does not match'),
            'password.min' => trans('translate.You have to provide minimum 4 character password'),
        ]);


        $user = User::select('id','name','email','forget_password_otp')->where('forget_password_otp', $request->otp)->where('email', $request->email)->first();

        if(!$user){
            $notify_message = trans('translate.Invalid token, please try again');
            return response()->json([
                'message' => $notify_message
            ],403);
        }

        $user->password = Hash::make($request->password);
        $user->forget_password_otp = null;
        $user->save();

        $notify_message= trans('translate.Password reset successfully');
        return response()->json([
            'message' => $notify_message
        ]);
    }


    public function redirect_to_google(){

        $gmail_client_id = GlobalSetting::where('key', 'gmail_client_id')->first();
        $gmail_secret_id = GlobalSetting::where('key', 'gmail_secret_id')->first();
        $gmail_redirect_url = GlobalSetting::where('key', 'gmail_redirect_url')->first();


        \Config::set('services.google.client_id', $gmail_client_id->value);
        \Config::set('services.google.client_secret', $gmail_secret_id->value);
        \Config::set('services.google.redirect', $gmail_redirect_url->value);

        return Socialite::driver('google')->redirect();

    }

    public function google_callback(){

        $gmail_client_id = GlobalSetting::where('key', 'gmail_client_id')->first();
        $gmail_secret_id = GlobalSetting::where('key', 'gmail_secret_id')->first();
        $gmail_redirect_url = GlobalSetting::where('key', 'gmail_redirect_url')->first();


        \Config::set('services.google.client_id', $gmail_client_id->value);
        \Config::set('services.google.client_secret', $gmail_secret_id->value);
        \Config::set('services.google.redirect', $gmail_redirect_url->value);

        $user = Socialite::driver('google')->user();
        $user = $this->create_user($user,'google');

        auth()->login($user);

        $notify_message= trans('translate.Login Successfully');
        $notify_message=array('message'=>$notify_message,'alert-type'=>'success');

        return redirect()->route('buyer.dashboard')->with($notify_message);

    }

    public function redirect_to_facebook(){

        $facebook_client_id = GlobalSetting::where('key', 'facebook_client_id')->first();
        $facebook_secret_id = GlobalSetting::where('key', 'facebook_secret_id')->first();
        $facebook_redirect_url = GlobalSetting::where('key', 'facebook_redirect_url')->first();

        \Config::set('services.facebook.client_id', $facebook_client_id->value);
        \Config::set('services.facebook.client_secret', $facebook_secret_id->value);
        \Config::set('services.facebook.redirect', $facebook_redirect_url->value);

        return Socialite::driver('facebook')->redirect();
    }

    public function facebook_callback(){

        $facebook_client_id = GlobalSetting::where('key', 'facebook_client_id')->first();
        $facebook_secret_id = GlobalSetting::where('key', 'facebook_secret_id')->first();
        $facebook_redirect_url = GlobalSetting::where('key', 'facebook_redirect_url')->first();

        \Config::set('services.facebook.client_id', $facebook_client_id->value);
        \Config::set('services.facebook.client_secret', $facebook_secret_id->value);
        \Config::set('services.facebook.redirect', $facebook_redirect_url->value);

        $user = Socialite::driver('facebook')->user();
        $user = $this->create_user($user,'facebook');
        auth()->login($user);

        $notify_message= trans('translate.Login Successfully');
        $notify_message=array('message'=>$notify_message,'alert-type'=>'success');

        return redirect()->route('buyer.dashboard')->with($notify_message);

    }

    public function create_user($get_info, $provider){
        $user = User::where('email', $get_info->email)->first();
        if (!$user) {

            $user = User::create([
                'name'     => $get_info->name,
                'username'     => Str::slug($get_info->name).'-'.date('Ymdhis'),
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

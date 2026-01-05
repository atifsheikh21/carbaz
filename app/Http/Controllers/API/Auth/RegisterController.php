<?php

namespace App\Http\Controllers\API\Auth;

use Exception;
use Mail, Str;
use App\Models\User;
use App\Rules\Captcha;
use App\Helper\EmailHelper;
use App\Helpers\MailHelper;
use Illuminate\Http\Request;
use App\Mail\UserRegistration;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Modules\GeneralSetting\Entities\EmailTemplate;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */


    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:api');
    }

    public function store_register(Request $request){

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', 'min:4', 'max:100']

        ],[
            'name.required' => trans('translate.Name is required'),
            'email.required' => trans('translate.Email is required'),
            'email.unique' => trans('translate.Email already exist'),
            'password.required' => trans('translate.Password is required'),
            'password.confirmed' => trans('translate.Confirm password does not match'),
            'password.min' => trans('translate.You have to provide minimum 4 character password'),
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => Str::slug($request->name).'-'.date('Ymdhis'),
            'status' => 'enable',
            'is_banned' => 'no',
            'password' => Hash::make($request->password),
            'verification_otp' => random_int(100000, 999999)
        ]);


        MailHelper::setMailConfig();

        try{
            $template = EmailTemplate::where('id', 12)->first();
            if($template){
                $subject=$template->subject;
                $message=$template->description;
                $message = str_replace('{{user_name}}',$request->name,$message);
                $message = str_replace('{{varification_otp}}',$user->verification_otp,$message);

                Mail::to($user->email)->send(new UserRegistration($message,$subject,$user));
            }

        }catch(Exception $ex){
            Log::info($ex->getMessage());
        }

        $notify_message = trans('translate.Account created successful, a verification OTP has been send to your mail, please verify it');

        return response()->json([
            'message' => $notify_message
        ]);


    }


    public function resend_register_code(Request $request){

        $rules = [
            'email'=>'required',
        ];
        $customMessages = [
            'email.required' => trans('translate.Email is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $user = User::where('email', $request->email)->first();
        if($user){
            if($user->email_verified_at == null){
                try{
                    MailHelper::setMailConfig();

                    $template = EmailTemplate::where('id', 12)->first();
                    if($template){
                        $subject=$template->subject;
                        $message=$template->description;
                        $message = str_replace('{{user_name}}',$user->name,$message);
                        $message = str_replace('{{varification_otp}}',$user->verification_otp,$message);

                        Mail::to($user->email)->send(new UserRegistration($message,$subject,$user));
                    }

                }catch(Exception $ex){
                    Log::info($ex->getMessage());
                }

                $notification = trans('translate.OTP resend successfully');
                return response()->json(['message' => $notification]);
            }else{
                $notification = trans('translate.Email already verified');
                return response()->json(['message' => $notification],403);
            }

        }else{
            $notification = trans('translate.Email does not exist');
            return response()->json(['message' => $notification],403);
        }
    }


    public function register_verification(Request $request){

        $rules = [
            'email'=>'required',
            'otp'=>'required',
        ];
        $customMessages = [
            'email.required' => trans('translate.Email is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $user = User::where('verification_otp',$request->otp)->where('email', $request->email)->first();
        if($user){

            if($user->email_verified_at != null){
                $notify_message = trans('translate.Email already verified');
                return response()->json(['message' => $notify_message],403);
            }

            $user->email_verified_at = date('Y-m-d H:i:s');
            $user->verification_token = null;
            $user->save();

            $notify_message = trans('translate.Verification Successfully');
            return response()->json(['message' => $notify_message]);
        }else{

            $notify_message = trans('translate.Invalid token or email');
            return response()->json(['message' => $notify_message],403);
        }
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
}

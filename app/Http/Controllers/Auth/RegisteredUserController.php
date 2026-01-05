<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Helpers\MailHelper;
use App\Rules\Captcha;
use App\Mail\UserRegistration;
use Modules\GeneralSetting\Entities\EmailTemplate;
use App\Http\Requests\RegisterRequest;
use Mail;
use Str;
use Session;

use Modules\GeneralSetting\Entities\SocialLoginInfo;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $social_login = SocialLoginInfo::first();

        return view('auth.register', ['social_login' => $social_login]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(RegisterRequest $request): RedirectResponse
    {

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => Str::slug($request->name).'-'.date('Ymdhis'),
            'status' => 'enable',
            'is_banned' => 'no',
            'password' => Hash::make($request->password),
            'verification_token' => Str::random(100),
        ]);

        try {
            MailHelper::setMailConfig();

            $verification_link = route('user-verification').'?verification_link='.$user->verification_token.'&email='.$user->email;
            $verification_link = '<a href="'.$verification_link.'">'.$verification_link.'</a>';

            $template=EmailTemplate::where('id',4)->first();
            $subject=$template->subject;
            $message=$template->description;
            $message = str_replace('{{user_name}}',$request->name,$message);
            $message = str_replace('{{varification_link}}',$verification_link,$message);

            Mail::to($user->email)->send(new UserRegistration($message,$subject,$user));

        } catch (\Exception $e) {
            \Log::error('Mail send error: ' . $e->getMessage());
        }
        $notification= trans('translate.A varification link has been send to your mail, please verify and enjoy our service');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);

    }

    public function custom_user_verification(Request $request){
        $user = User::where('verification_token',$request->verification_link)->where('email', $request->email)->first();
        if($user){

            if($user->email_verified_at != null){
                $notification = trans('translate.Email already verified');
                $notification = array('messege'=>$notification,'alert-type'=>'error');
                return redirect()->route('login')->with($notification);
            }

            $user->email_verified_at = date('Y-m-d H:i:s');
            $user->verification_token = null;
            $user->save();

            $notification = trans('translate.Verification Successfully');
            $notification = array('messege'=>$notification,'alert-type'=>'success');
            return redirect()->route('login')->with($notification);
        }else{
            $notification = trans('translate.Invalid token');
            $notification = array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->route('register')->with($notification);
        }
    }
}

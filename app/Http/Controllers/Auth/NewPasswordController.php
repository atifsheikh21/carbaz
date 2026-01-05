<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Models\User;
use App\Rules\Captcha;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request): View
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        return $status == Password::PASSWORD_RESET
                    ? redirect()->route('login')->with('status', __($status))
                    : back()->withInput($request->only('email'))
                            ->withErrors(['email' => __($status)]);
    }

    public function custom_reset_password_page(Request $request){

        $user = User::select('id','name','email','forget_password_token')->where('forget_password_token', $request->token)->where('email', $request->email)->first();

        if(!$user){
            $notification = trans('translate.Invalid token, please try again');
            $notification = array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->route('password.request')->with($notification);
        }


        return view('auth.reset-password', ['user' => $user, 'token' => $request->token]);
    }

    public function custom_reset_password_store(Request $request, $token){

        $rules = [
            'email'=>'required',
            'password'=>'required|min:4|confirmed',
            'g-recaptcha-response'=>new Captcha()
        ];
        $customMessages = [
            'email.required' => trans('translate.Email is required'),
            'password.required' => trans('translate.Password is required'),
            'password.min' => trans('translate.Password must be 4 characters'),
            'password.confirmed' => trans('translate.Confirm password does not match'),
        ];
        $this->validate($request, $rules,$customMessages);


        $user = User::select('id','name','email','forget_password_token')->where('forget_password_token', $token)->where('email', $request->email)->first();

        if(!$user){
            $notification = trans('translate.Invalid token, please try again');
            $notification = array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }

        $user->password=Hash::make($request->password);
        $user->forget_password_token=null;
        $user->save();

        $notification = trans('translate.Password Reset successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('login')->with($notification);

    }


}

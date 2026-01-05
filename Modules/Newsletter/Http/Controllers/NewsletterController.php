<?php

namespace Modules\Newsletter\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Newsletter\Entities\Subscriber;
use Modules\Newsletter\Http\Requests\NewsletterRequest;
use Str, Mail, Hash, Auth;
use App\Helpers\MailHelper;
use Modules\Newsletter\Emails\NewsletterVerification;
use Modules\GeneralSetting\Entities\EmailTemplate;

class NewsletterController extends Controller
{

    public function __construct()
    {
        $this->middleware('HtmlSpecialchars');
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function newsletter_request(NewsletterRequest $request)
    {

        $newsletter = new Subscriber();
        $newsletter->email = $request->email;
        $newsletter->verified_token = Str::random(25);
        $newsletter->save();

        MailHelper::setMailConfig();

        $verification_link = route('newsletter-verification').'?verification_link='.$newsletter->verified_token.'&email='.$newsletter->email;
        $verification_link = '<a href="'.$verification_link.'">'.$verification_link.'</a>';
        try {
            $template = EmailTemplate::find(3);
            $message = $template->description;
            $subject = $template->subject;
            $message = str_replace('{{verification_link}}',$verification_link,$message);


            Mail::to($newsletter->email)->send(new NewsletterVerification($message,$subject));

        } catch (\Exception $e) {
            \Log::error('Mail send error: ' . $e->getMessage());
        }
        $notification = trans('translate.A verification link has been send to your email, please verify and enjoy our newsletter');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);

    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function newsletter_verification(Request $request)
    {
        $newsletter = Subscriber::where(['email' => $request->email, 'verified_token' => $request->verification_link])->first();

        if($newsletter){
            $newsletter->verified_token = null;
            $newsletter->is_verified = 1;
            $newsletter->status = 1;
            $newsletter->save();

            $notification = trans('translate.Email verification successfully');
            $notification = array('messege'=>$notification,'alert-type'=>'success');
            return redirect()->route('home')->with($notification);
        }else{
            $notification = trans('translate.Something went wrong');
            $notification = array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->route('home')->with($notification);
        }
    }

}

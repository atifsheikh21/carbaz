<?php

namespace Modules\Newsletter\Http\Controllers\Admin;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Newsletter\Entities\Subscriber;

use App\Helpers\MailHelper;
use Modules\Newsletter\Emails\SubscirberSendMail;
use Modules\Newsletter\Http\Requests\SendNewsletterRequest;
use Str;
use Mail;
use Hash;
use Auth;

class NewsletterController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function subscriber_list()
    {
        $subscribers = Subscriber::latest()->get();

        return view('newsletter::admin.subscriber_list', compact('subscribers'));
    }

    public function subscriber_email()
    {
        return view('newsletter::admin.mail_box');
    }

    public function send_subscriber_email(SendNewsletterRequest $request)
    {
        $subscribers = Subscriber::where('is_verified',1)->get();
        if($subscribers->count() > 0){
            MailHelper::setMailConfig();
            foreach($subscribers as $index => $subscriber){
                try {
                    Mail::to($subscriber->email)->send(new SubscirberSendMail($request->subject,$request->message));
                } catch (\Exception $e) {
                    \Log::error('Mail send error: ' . $e->getMessage());
                }
            }

            $notification = trans('translate.Email Send Successfully');
            $notification = array('messege'=>$notification,'alert-type'=>'success');
            return redirect()->back()->with($notification);
        }else{

            $notification = trans('translate.Something Went Wrong');
            $notification = array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }
    }

    public function delete_subscriber($id){
        $subscriber = Subscriber::find($id);
        $subscriber->delete();

        $notification = trans('translate.Delete Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

}

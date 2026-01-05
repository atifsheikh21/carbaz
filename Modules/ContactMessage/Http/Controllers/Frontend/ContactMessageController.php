<?php

namespace Modules\ContactMessage\Http\Controllers\Frontend;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\ContactMessage\Entities\ContactMessage;
use Modules\GeneralSetting\Entities\Setting;
use Modules\GeneralSetting\Entities\EmailTemplate;
use Modules\ContactMessage\Http\Requests\ContactMessageRequest;
use App\Helpers\MailHelper;
use Modules\ContactMessage\Emails\SendContactMessage;
use Mail;

use App\Rules\Captcha;

class ContactMessageController extends Controller
{

    public function __construct()
    {
        $this->middleware('HtmlSpecialchars');
    }

    public function store_contact_message(ContactMessageRequest $request){

        $setting = Setting::first();

        $contact_message = new ContactMessage();
        $contact_message->name = $request->name;
        $contact_message->email = $request->email;
        $contact_message->phone = $request->phone;
        $contact_message->subject = $request->subject;
        $contact_message->message = $request->message;
        $contact_message->save();

        if($setting->send_contact_message == 'enable'){

            MailHelper::setMailConfig();

            $template = EmailTemplate::find(2);
            $message = $template->description;
            $subject = $template->subject;
            $message = str_replace('{{user_name}}',$request->name,$message);
            $message = str_replace('{{user_email}}',$request->email,$message);
            $message = str_replace('{{user_phone}}',$request->phone,$message);
            $message = str_replace('{{message_subject}}',$request->subject,$message);
            $message = str_replace('{{message}}',$request->message,$message);
            try {
                
                Mail::to($setting->contact_message_mail)->send(new SendContactMessage($message,$subject, $request->email, $request->name));

            } catch (\Exception $e) {
                \Log::error('Mail send error: ' . $e->getMessage());
            }

        }

        $notification= trans('translate.Your message has send successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);

    }

}

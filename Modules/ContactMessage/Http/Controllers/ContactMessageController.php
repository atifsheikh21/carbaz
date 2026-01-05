<?php

namespace Modules\ContactMessage\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\ContactMessage\Entities\ContactMessage;
use Modules\GeneralSetting\Entities\Setting;

class ContactMessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function contact_message(){

        $contact_messages = ContactMessage::orderBy('id','desc')->latest()->get();
        return view('contactmessage::contact_message', compact('contact_messages'));
    }

    public function show_message($id){

        $contact_message = ContactMessage::findOrFail($id);
        return view('contactmessage::show_contact_message', compact('contact_message'));
    }

    public function delete_message($id){

        $contact_message = ContactMessage::findOrFail($id);
        $contact_message->delete();

        $notification = trans('translate.Delete Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

    public function contact_message_setting(Request $request){
        $rules = [
            'contact_message_mail' => 'required',
        ];
        $customMessages = [
            'contact_message_mail.required' => trans('translate.Contact email is required'),
        ];
        $request->validate($rules,$customMessages);

        $setting = Setting::first();
        $setting->contact_message_mail = $request->contact_message_mail;
        $setting->send_contact_message = $request->send_contact_message;
        $setting->save_contact_message = $request->save_contact_message;
        $setting->save();

        $notification = trans('translate.Update Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }
}

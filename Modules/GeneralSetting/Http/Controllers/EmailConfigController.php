<?php

namespace Modules\GeneralSetting\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\GeneralSetting\Entities\EmailConfiguration;
use Modules\GeneralSetting\Entities\EmailTemplate;

use Modules\GeneralSetting\Http\Requests\EmailConfigurationRequest;
use Modules\GeneralSetting\Http\Requests\EmailTemplateRequest;

class EmailConfigController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function email_configuration(){

        $email_setting = EmailConfiguration::first();

        return view('generalsetting::email.email_configuration', compact('email_setting'));
    }

    public function update_email_configuration(EmailConfigurationRequest $request){

        $email_setting = EmailConfiguration::first();
        $email_setting->sender_name = $request->sender_name;
        $email_setting->mail_host = $request->mail_host;
        $email_setting->email = $request->email;
        $email_setting->smtp_username = $request->smtp_username;
        $email_setting->smtp_password = $request->smtp_password;
        $email_setting->mail_port = $request->mail_port;
        $email_setting->mail_encryption = $request->mail_encryption;
        $email_setting->save();

        $notification=  trans('translate.Update Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

    public function email_template(){
        $template_list = EmailTemplate::all();
        return view('generalsetting::email.template_list', compact('template_list'));
    }

    public function edit_email_template($id){

        $template_item = EmailTemplate::find($id);
        if($template_item){
            if($id == 1){
                return view('generalsetting::email.password_reset', compact('template_item'));
            }elseif($id == 2){
                return view('generalsetting::email.contact_message', compact('template_item'));
            }elseif($id == 3){
                return view('generalsetting::email.newsletter', compact('template_item'));
            }elseif($id == 4){
                return view('generalsetting::email.user_register', compact('template_item'));
            }

        }else{
            $notification= trans('translate.Something went wrong');
            $notification=array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->route('admin.email-template')->with($notification);
        }

    }


    public function update_email_template(EmailTemplateRequest $request, $id){

        $template = EmailTemplate::find($id);
        if($template){
            $template->subject = $request->subject;
            $template->description = $request->description;
            $template->save();
            $notification= trans('translate.Updated Successfully');
            $notification=array('messege'=>$notification,'alert-type'=>'success');
            return redirect()->back()->with($notification);
        }else{
            $notification= trans('translate.Something went wrong');
            $notification=array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->route('admin.email-template')->with($notification);
        }
    }


}

<?php

namespace Modules\Page\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Page\Entities\ContactUs;
use Modules\Page\Entities\ContactUsTranslation;

use Modules\Page\Http\Requests\ContactUsRequest;

class ContactUsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index(Request $request)
    {
        $contact_us = ContactUs::first();
        $translate = ContactUsTranslation::where('lang_code', $request->lang_code)->first();

        return view('page::contact_us', compact('contact_us','translate'));
    }

    public function update(ContactUsRequest $request)
    {

        if($request->lang_code == admin_lang()){
            $contact_us = ContactUs::first();
            $contact_us->email = $request->email;
            $contact_us->phone = $request->phone;
            $contact_us->map_code = $request->map_code;
            $contact_us->save();
        }

        $translate = ContactUsTranslation::where('lang_code', $request->lang_code)->first();
        $translate->title = $request->title;
        $translate->description = $request->description;
        $translate->address = $request->address;
        $translate->save();

        $notification = trans('translate.Updated Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

    public function assign_language($lang_code){
        $contact_translates = ContactUsTranslation::where('lang_code', admin_lang())->get();
        foreach($contact_translates as $contact_translate){
            $translate = new ContactUsTranslation();
            $translate->contact_us_id = $contact_translate->contact_us_id;
            $translate->lang_code = $lang_code;
            $translate->title = $contact_translate->title;
            $translate->description = $contact_translate->description;
            $translate->address = $contact_translate->address;
            $translate->save();
        }
    }
}

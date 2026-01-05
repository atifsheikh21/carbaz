<?php

namespace Modules\Page\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Page\Entities\Faq;
use Modules\Page\Entities\FaqTranslate;
use Modules\Language\Entities\Language;

use Modules\Page\Http\Requests\FaqRequest;

class FaqController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $faqs = Faq::with('translate')->get();

        return view('page::faq.index', compact('faqs'));
    }

    public function create()
    {
        return view('page::faq.create');
    }

    public function store(FaqRequest $request)
    {
        $faq = new Faq();
        $faq->save();

        $languages = Language::all();
        foreach($languages as $language){

            $faq_translate = new FaqTranslate();
            $faq_translate->faq_id = $faq->id;
            $faq_translate->lang_code = $language->lang_code;
            $faq_translate->question = $request->question;
            $faq_translate->answer = $request->answer;
            $faq_translate->save();
        }

        $notification = trans('translate.Created Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.faq.edit', ['faq' => $faq->id, 'lang_code' => admin_lang()])->with($notification);

    }

    public function edit(Request $request, $id)
    {
        $faq = Faq::findOrFail($id);
        $translate = FaqTranslate::where(['faq_id' => $id, 'lang_code' => $request->lang_code])->first();

        return view('page::faq.edit', compact('faq','translate'));
    }

    public function update(FaqRequest $request, $id)
    {
        $faq_translate = FaqTranslate::findOrFail($request->translate_id);
        $faq_translate->question = $request->question;
        $faq_translate->answer = $request->answer;
        $faq_translate->save();

        $notification = trans('translate.Updated Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);

    }

    public function destroy($id)
    {
        $faq = Faq::findOrFail($id);
        $faq->delete();

        FaqTranslate::where('faq_id', $id)->delete();

        $notification = trans('translate.Deleted Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);

    }


    public function assign_language($lang_code){
        $faq_translates = FaqTranslate::where('lang_code', admin_lang())->get();

        foreach($faq_translates as $faq_translate){
            $new_translate = new FaqTranslate();
            $new_translate->lang_code = $lang_code;
            $new_translate->faq_id = $faq_translate->faq_id;
            $new_translate->question = $faq_translate->question;
            $new_translate->answer = $faq_translate->answer;
            $new_translate->save();
        }
    }
}

<?php

namespace Modules\Page\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Page\Entities\CustomPage;
use Modules\Page\Entities\CustomPageTranslation;
use Modules\Language\Entities\Language;
use Str;

use Modules\Page\Http\Requests\CustomPageRequest;

class CustomPageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $custom_pages = CustomPage::with('translate')->get();
        return view('page::custom_page.index', compact('custom_pages'));
    }

    public function create()
    {
        return view('page::custom_page.create');
    }

    public function store(CustomPageRequest $request)
    {
        $custom_page = new CustomPage();
        $custom_page->slug = $request->slug;
        $custom_page->status = $request->status ? 1 : 0;
        $custom_page->save();

        $languages = Language::all();
        foreach($languages as $language){

            $page_translate = new CustomPageTranslation();
            $page_translate->custom_page_id = $custom_page->id;
            $page_translate->lang_code = $language->lang_code;
            $page_translate->description = $request->description;
            $page_translate->page_name = $request->page_name;
            $page_translate->save();
        }

        $notification = trans('translate.Created Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.custom-page.edit', ['custom_page' => $custom_page->id, 'lang_code' => admin_lang()])->with($notification);
    }


    public function edit(Request $request, $id)
    {
        $custom_page = CustomPage::findOrFail($id);
        $translate = CustomPageTranslation::where(['lang_code' => $request->lang_code, 'custom_page_id' => $id])->first();

        return view('page::custom_page.edit', compact('custom_page','translate'));
    }

    public function update(CustomPageRequest $request, $id)
    {
        $custom_page = CustomPage::findOrFail($id);

        if($request->lang_code == admin_lang()){
            $custom_page->slug = $request->slug;
            $custom_page->status = $request->status ? 1 : 0;
            $custom_page->save();
        }

        $page_translate = CustomPageTranslation::find($request->translate_id);
        $page_translate->description = $request->description;
        $page_translate->page_name = $request->page_name;
        $page_translate->save();

        $notification= trans('translate.Update Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

    public function destroy($id)
    {
        $custom_page = CustomPage::findOrFail($id);
        $custom_page->delete();

        CustomPageTranslation::where('custom_page_id', $id)->delete();

        $notification= trans('translate.Delete Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);

    }

    public function assign_language($lang_code){
        $custom_page_translates = CustomPageTranslation::where('lang_code', admin_lang())->get();
        foreach($custom_page_translates as $custom_page_translate){
            $page_translate = new CustomPageTranslation();
            $page_translate->lang_code = $lang_code;
            $page_translate->custom_page_id = $custom_page_translate->custom_page_id;
            $page_translate->description = $custom_page_translate->description;
            $page_translate->page_name = $custom_page_translate->page_name;
            $page_translate->save();
        }
    }

}

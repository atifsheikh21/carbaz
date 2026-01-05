<?php

namespace Modules\Page\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Page\Entities\TermAndCondition;

use Modules\Page\Http\Requests\TermAndConditionRequest;

class TermsConditionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index(Request $request)
    {
        $terms_conditions = TermAndCondition::where('lang_code', $request->lang_code)->first();

        return view('page::terms_conditions', compact('terms_conditions'));
    }


    public function update(TermAndConditionRequest $request)
    {
        $terms_conditions = TermAndCondition::findOrFail($request->translate_id);
        $terms_conditions->description = $request->description;
        $terms_conditions->save();

        $notification = trans('translate.Updated Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);

    }

    public function assign_language($lang_code){
        $terms_conditions_translates = TermAndCondition::where('lang_code', admin_lang())->first();

        $terms_conditions = new TermAndCondition();
        $terms_conditions->lang_code = $lang_code;
        $terms_conditions->description = $terms_conditions_translates->description;
        $terms_conditions->save();
    }

}

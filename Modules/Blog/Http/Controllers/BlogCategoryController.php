<?php

namespace Modules\Blog\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Blog\Entities\BlogCategory;
use Modules\Blog\Entities\Blog;
use Modules\Blog\Entities\BlogCategoryTranslation;
use Modules\Language\Entities\Language;
use Modules\Blog\Http\Requests\BlogCategoryRequest;
use Session;

class BlogCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $blog_categories = BlogCategory::with('translate')->latest()->get();

        return view('blog::blog_category',compact('blog_categories'));
    }

    public function create()
    {
        return view('blog::blog_category_create');
    }

    public function store(BlogCategoryRequest $request)
    {

        $category = new BlogCategory();
        $category->slug = $request->slug;
        $category->status = $request->status ? 1 : 0;
        $category->save();

        $languages = Language::all();
        foreach($languages as $language){
            $category_translation = new BlogCategoryTranslation();
            $category_translation->lang_code = $language->lang_code;
            $category_translation->blog_category_id = $category->id;
            $category_translation->name = $request->name;
            $category_translation->save();
        }

        $notification= trans('translate.Created Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.blog-category.edit', ['blog_category' => $category->id, 'lang_code' => admin_lang()])->with($notification);
    }

    public function edit(Request $request, $id)
    {
        $blog_category = BlogCategory::findOrFail($id);
        $cat_translate = BlogCategoryTranslation::where(['blog_category_id' => $id, 'lang_code' => $request->lang_code])->first();

        return view('blog::blog_category_edit',compact('blog_category','cat_translate'));
    }


    public function update(BlogCategoryRequest $request,$id)
    {
        $blog_category = BlogCategory::findOrFail($id);

        if($request->lang_code == admin_lang()){

            $blog_category = BlogCategory::find($id);
            $blog_category->slug = $request->slug;
            $blog_category->status = $request->status ? 1 : 0;
            $blog_category->save();

            $category_translation = BlogCategoryTranslation::findOrFail($request->translate_id);
            $category_translation->name = $request->name;
            $category_translation->save();

        }else{

            $category_translation = BlogCategoryTranslation::findOrFail($request->translate_id);
            $category_translation->name = $request->name;
            $category_translation->save();
        }

        $notification= trans('translate.Update Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

    public function destroy($id)
    {
        $blog_count = Blog::where('blog_category_id', $id)->count();
        if($blog_count > 0){
            $notification= trans('translate.You can not delete this category, multiple blog available under this category');
            $notification=array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->route('admin.blog-category.index')->with($notification);
        }

        $blog_category = BlogCategory::find($id);
        $blog_category->delete();

        BlogCategoryTranslation::where('blog_category_id', $id)->delete();

        $notification= trans('translate.Delete Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.blog-category.index')->with($notification);
    }

    public function assign_language($lang_code){
        $cat_translates = BlogCategoryTranslation::where('lang_code', admin_lang())->get();
        foreach($cat_translates as $cat_translate){
            $blog_cat_translate = new BlogCategoryTranslation();
            $blog_cat_translate->lang_code = $lang_code;
            $blog_cat_translate->blog_category_id = $cat_translate->blog_category_id;
            $blog_cat_translate->name = $cat_translate->name;
            $blog_cat_translate->save();
        }
    }

}

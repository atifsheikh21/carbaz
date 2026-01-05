<?php

namespace Modules\Blog\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Blog\Entities\BlogCategory;
use Modules\Blog\Entities\Blog;
use Modules\Blog\Entities\BlogTranslation;
use Modules\Blog\Entities\BlogCategoryTranslation;
use Modules\Blog\Entities\BlogComment;
use Modules\Language\Entities\Language;
use Session, Auth, Image, File, Str;

use Modules\Blog\Http\Requests\BlogRequest;

class BlogController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $blogs = Blog::with('translate','category')->orderBy('id','desc')->latest()->get();
        return view('blog::blog_list',compact('blogs'));
    }


    public function create()
    {
        $blog_categories = BlogCategory::with('translate')->get();

        return view('blog::blog_create',compact('blog_categories'));
    }


    public function store(BlogRequest $request)
    {

        $admin = Auth::guard('admin')->user();

        $blog = new Blog();

        if ($request->hasFile('image')) {
            $image_path = uploadFile($request->file('image'), 'uploads/custom-images');
            $blog->image = $image_path;
        }

        $blog->admin_id = $admin->id;
        $blog->slug = $request->slug;
        $blog->blog_category_id = $request->category;
        $blog->is_popular = $request->is_popular ? 'yes' : 'no';
        $blog->status = $request->status ? 1 : 0;
        $blog->tags =  $request->tags;
        $blog->save();

        $languages = Language::all();
        foreach($languages as $language){
            $blog_translate = new BlogTranslation();
            $blog_translate->lang_code = $language->lang_code;
            $blog_translate->blog_id = $blog->id;
            $blog_translate->title = $request->title;
            $blog_translate->description = $request->description;
            $blog_translate->seo_title = $request->seo_title ? $request->seo_title : $request->title;
            $blog_translate->seo_description = $request->seo_description ? $request->seo_description : $request->title;
            $blog_translate->save();
        }

        $notification= trans('translate.Created Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.blog.edit', ['blog' => $blog->id, 'lang_code' => admin_lang()])->with($notification);
    }

    public function edit(Request $request, $id)
    {
        $blog_categories = BlogCategory::with('translate')->get();
        $blog = Blog::with('translate')->findOrFail($id);

        $blog_translate = BlogTranslation::where(['blog_id' => $id, 'lang_code' => $request->lang_code])->first();

        return view('blog::blog_edit',compact('blog_categories','blog','blog_translate'));
    }


    public function update(BlogRequest $request,$id)
    {
        $blog = Blog::findOrFail($id);

        if($request->lang_code == admin_lang()){

            if ($request->hasFile('image')) {
                $image_path = uploadFile($request->file('image'), 'uploads/custom-images', $blog->image);
                $blog->image = $image_path;
                $blog->save();
            }

            $blog->slug = $request->slug;
            $blog->blog_category_id = $request->category;
            $blog->is_popular = $request->is_popular ? 'yes' : 'no';
            $blog->status = $request->status ? 1 : 0;
            $blog->tags =  $request->tags;
            $blog->save();
        }

        $blog_translate = BlogTranslation::findOrFail($request->translate_id);
        $blog_translate->title = $request->title;
        $blog_translate->description = $request->description;
        $blog_translate->seo_title = $request->seo_title ? $request->seo_title : $request->title;
        $blog_translate->seo_description = $request->seo_description ? $request->seo_description : $request->title;
        $blog_translate->save();

        $notification= trans('translate.Updated Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

    public function destroy($id)
    {
        $blog = Blog::find($id);
        $old_image = $blog->image;

        if($old_image){
            if(File::exists(public_path().'/'.$old_image))unlink(public_path().'/'.$old_image);
        }

        BlogComment::where('blog_id',$id)->delete();
        BlogTranslation::where('blog_id',$id)->delete();

        $blog->delete();

        $notification=  trans('translate.Delete Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.blog.index')->with($notification);
    }

    public function blog_comment(){
        $blog_comments = BlogComment::orderBy('id','desc')->get();

        return view('blog::blog_comment',compact('blog_comments'));
    }

    public function show_comment($id){

        $blog_comment = BlogComment::findOrFail($id);

        return view('blog::blog_comment_show',compact('blog_comment'));
    }



    public function destroy_comment($id)
    {
        $blog_comment = BlogComment::findOrFail($id);
        $blog_comment->delete();

        $notification= trans('translate.Delete Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.blog-comments')->with($notification);
    }

    public function blog_comment_status($id){
        $blog_comment = BlogComment::find($id);
        if($blog_comment->status == 1){
            $blog_comment->status = 0;
            $blog_comment->save();
            $message = trans('translate.Status Changed Successfully');
        }else{
            $blog_comment->status = 1;
            $blog_comment->save();
            $message = trans('translate.Status Changed Successfully');
        }
        return response()->json($message);
    }

    public function assign_language($lang_code){
        $blog_translates = BlogTranslation::where('lang_code', admin_lang())->get();

        foreach($blog_translates as $blog_translate){
            $new_blog = new BlogTranslation();
            $new_blog->lang_code = $lang_code;
            $new_blog->blog_id = $blog_translate->blog_id;
            $new_blog->title = $blog_translate->title;
            $new_blog->description = $blog_translate->description;
            $new_blog->seo_title = $blog_translate->seo_title;
            $new_blog->seo_description = $blog_translate->seo_description;
            $new_blog->save();
        }
    }

}

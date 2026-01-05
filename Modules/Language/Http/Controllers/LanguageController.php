<?php

namespace Modules\Language\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use Modules\Language\Entities\Language;

use Modules\Page\Entities\AboutUsTranslation;
use Modules\Page\Entities\CustomPageTranslation;
use Modules\Page\Entities\TermAndCondition;
use Modules\Page\Entities\PrivacyPolicy;
use Modules\Page\Entities\FaqTranslate;
use Modules\Page\Entities\HomePageTranslation;
use Modules\Page\Entities\ContactUsTranslation;

use Modules\Page\Http\Controllers\AboutUsController;
use Modules\Page\Http\Controllers\CustomPageController;
use Modules\Page\Http\Controllers\ContactUsController;
use Modules\Page\Http\Controllers\PrivacyPolicyController;
use Modules\Page\Http\Controllers\TermsConditionController;
use Modules\Page\Http\Controllers\FaqController;
use Modules\Page\Http\Controllers\IntroController;

use Modules\Feature\Http\Controllers\FeatureController;
use Modules\Feature\Entities\FeatureTranslation;

use Modules\Car\Http\Controllers\CarController;
use Modules\Car\Entities\CarTranslation;

use Modules\Blog\Entities\BlogCategoryTranslation;
use Modules\Blog\Entities\BlogTranslation;
use Modules\Blog\Http\Controllers\BlogCategoryController;
use Modules\Blog\Http\Controllers\BlogController;

use Modules\Testimonial\Http\Controllers\TestimonialController;
use Modules\Testimonial\Entities\TestimonialTranslation;

use Modules\Brand\Entities\BrandTranslation;
use Modules\Brand\Http\Controllers\BrandController;

use Modules\City\Entities\CityTranslation;
use Modules\City\Http\Controllers\CityController;

use Modules\Language\Http\Requests\LanguageRequest;

use DB, File;

class LanguageController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $languages = Language::all();

        return view('language::index', compact('languages'));
    }

    public function create()
    {
        return view('language::create');
    }


    public function store(LanguageRequest $request)
    {
        $language = new Language();

        if($request->is_default){
            DB::table('languages')->update(['is_default' => 'No']);
        }

        $language->lang_name = $request->lang_name;
        $language->lang_code = $request->lang_code;
        $language->is_default = $request->is_default ? 'Yes' : 'No';
        $language->lang_direction = $request->lang_direction;
        $language->status = $request->status ? 1 : 0;
        $language->save();

        /**blog translation*/

        $blog_translate = new BlogController();
        $blog_translate->assign_language($request->lang_code);

        /**blog translation*/

        /**blog category translation*/

        $blog_cat_translate = new BlogCategoryController();
        $blog_cat_translate->assign_language($request->lang_code);

        /**blog category translation*/


       /**terms condition translation*/

        $terms_cond_translate = new TermsConditionController();
        $terms_cond_translate->assign_language($request->lang_code);

        /**terms condition translation*/


        /** privacy page translation*/

        $privacy_translate = new PrivacyPolicyController();
        $privacy_translate->assign_language($request->lang_code);

        /** privacy page translation*/

        /** faq translation*/

        $faq_translate = new FaqController();
        $faq_translate->assign_language($request->lang_code);

        /** faq translation*/

        /** custom page translation*/

        $page_translate = new CustomPageController();
        $page_translate->assign_language($request->lang_code);

        /** custom page translation*/

        /** contact page translation*/

        $contact_us = new ContactUsController();
        $contact_us->assign_language($request->lang_code);

        /** contact page translation*/

        /** brand translation*/

        $brand = new BrandController();
        $brand->assign_language($request->lang_code);

        /** brand translation*/

        /** home page translation*/

        $intro = new IntroController();
        $intro->assign_language($request->lang_code);

        /** home page translation*/

        /** testimonial translation*/

        $testimonial = new TestimonialController();
        $testimonial->assign_language($request->lang_code);

        /** testimonial translation*/

        /** city translation*/

        $city = new CityController();
        $city->assign_language($request->lang_code);

        /** city translation*/

        /** start about us */

        $about_us_translate = new AboutUsController();
        $about_us_translate->assign_language($request->lang_code);

        /** end about us */


        /** feature translation*/

        $feature = new FeatureController();
        $feature->assign_language($request->lang_code);

        /** feature translation */

        /** car translation*/

        $car = new CarController();
        $car->assign_language($request->lang_code);

        /** car translation */

        /** generate local language */

        $path = base_path().'/lang'.'/'.$request->lang_code;

        if (! File::exists($path)) {
            File::makeDirectory($path);

            $sourcePath = base_path().'/lang/en';
            $destinationPath = $path;

            // Get all files from the source folder
            $files = File::allFiles($sourcePath);

            foreach ($files as $file) {
                $destinationFile = $destinationPath . '/' . $file->getRelativePathname();

                // Copy the file to the destination folder
                File::copy($file->getRealPath(), $destinationFile);
            }
        }

        /** end generate local language */

        $notification=trans('translate.Created Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.language.index')->with($notification);

    }

    public function edit($id)
    {
        $language = Language::findOrFail($id);
        return view('language::edit', compact('language'));
    }

    public function update(LanguageRequest $request, $id)
    {

        $language = Language::findOrFail($id);

        if($request->is_default){
            DB::table('languages')->update(['is_default' => 'No']);
        }

        if($language->is_default == 'Yes'){
            DB::table('languages')->where('id', 1)->update(['is_default' => 'Yes']);
        }

        $language->lang_name = $request->lang_name;
        $language->is_default = $request->is_default ? 'Yes' : 'No';
        $language->lang_direction = $request->lang_direction;
        $language->status = $request->status ? 1 : 0;
        $language->save();

        $notification=trans('translate.Updated Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.language.index')->with($notification);
    }

    public function destroy($id)
    {
        $language = Language::findOrFail($id);
        if($language->id == 1){
            $notification = trans('translate.You can not delete English language');
            $notification = array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }

        BlogCategoryTranslation::where('lang_code', $language->lang_code)->delete();
        BlogTranslation::where('lang_code', $language->lang_code)->delete();
        BrandTranslation::where('lang_code', $language->lang_code)->delete();
        AboutUsTranslation::where('lang_code', $language->lang_code)->delete();
        CustomPageTranslation::where('lang_code', $language->lang_code)->delete();
        ContactUsTranslation::where('lang_code', $language->lang_code)->delete();
        CityTranslation::where('lang_code', $language->lang_code)->delete();
        CarTranslation::where('lang_code', $language->lang_code)->delete();
        TermAndCondition::where('lang_code', $language->lang_code)->delete();
        PrivacyPolicy::where('lang_code', $language->lang_code)->delete();
        FaqTranslate::where('lang_code', $language->lang_code)->delete();
        FeatureTranslation::where('lang_code', $language->lang_code)->delete();
        TestimonialTranslation::where('lang_code', $language->lang_code)->delete();
        HomePageTranslation::where('lang_code', $language->lang_code)->delete();

        $path = base_path().'/lang'.'/'.$language->lang_code;

        if (File::exists($path)) {
            File::deleteDirectory($path);
        }

        $language->delete();

        $notification=trans('translate.Deleted Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.language.index')->with($notification);


    }

    public function theme_language(Request $request){

        include(lang_path($request->lang_code.'/translate.php'));

        if(File::exists(lang_path($request->lang_code.'/translate.php'))) {

            $data = include(lang_path($request->lang_code.'/translate.php'));

            return view('language::theme_language', ['data' => $data]);
        }else{
            $notification=trans('translate.Your requested language does not exist');
            $notification=array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->route('admin.language.index')->with($notification);
        }

    }

    public function update_theme_language(Request $request){

        $dataArray = [];
        foreach($request->values as $index => $value){
            $dataArray[$index] = $value;
        }

        file_put_contents(lang_path($request->lang_code.'/translate.php'), "");

        $dataArray = var_export($dataArray, true);


        file_put_contents(lang_path($request->lang_code.'/translate.php'), "<?php\n return {$dataArray};\n ?>");

        $notification= trans('translate.Update Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);

    }

}

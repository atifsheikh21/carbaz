<?php

namespace Modules\Car\Http\Controllers;

use App\Models\User;
use App\Models\Review;
use App\Models\Wishlist;
use Auth, Image, File, Str;
use Illuminate\Http\Request;
use Modules\Car\Entities\Car;

use Modules\City\Entities\City;
use Modules\Brand\Entities\Brand;
use Illuminate\Routing\Controller;
use Intervention\Image\ImageManager;
use Modules\Car\Entities\CarGallery;
use Modules\Country\Entities\Country;
use Modules\Feature\Entities\Feature;
use Modules\Language\Entities\Language;

use Modules\Car\Entities\CarTranslation;

use Modules\Car\Http\Requests\CarRequest;
use Illuminate\Contracts\Support\Renderable;
use Modules\Subscription\Entities\SubscriptionHistory;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $cars = Car::with('translate','dealer')->where('is_draft', 'disable')->latest()->get();

        return view('car::index', compact('cars'));
    }

    public function awaiting_car()
    {
        $cars = Car::with('translate','dealer')->where('approved_by_admin', 'pending')->where('is_draft', 'disable')->latest()->get();

        return view('car::awaiting_car', compact('cars'));
    }

    public function featured_car()
    {
        $cars = Car::with('translate','dealer')->where('is_featured', 'enable')->where('is_draft', 'disable')->latest()->get();

        return view('car::featured_car', compact('cars'));
    }


    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create(Request $request)
    {

        $countries = Country::latest()->get();

        if($request->purpose == 'Sale'){
            $brands = Brand::with('translate')->where('status', 'enable')->get();
            $cities = City::with('translate')->get();
            $features = Feature::with('translate')->get();
            $dealers = User::all();

            return view('car::create_sale_car', compact('brands', 'cities', 'features', 'dealers', 'countries'));
        }else{
            abort(404);
        };


    }

    public function select_car_purpose()
    {
        return view('car::select_purpose');
    }


    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(CarRequest $request)
    {

        $user = User::findOrFail($request->agent_id);

        $active_plan = SubscriptionHistory::where('user_id', $user->id)->latest()->first();

        if(!$active_plan){
            $notification=  trans('translate.User did not enrolled any plan yet.');
            $notification=array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->route('admin.select-car-purpose')->with($notification);
        }

        $expiration_date = $active_plan->expiration_date;

        if($expiration_date != 'lifetime'){
            if(date('Y-m-d') > $expiration_date){
                $notification = trans('translate.Your plan is expired, please renew or re-order');
                $notification = array('messege'=>$notification,'alert-type'=>'error');
                return redirect()->route('admin.select-car-purpose')->with($notification);
            }
        }

        $max_car = $active_plan->max_car;

        $total_car = Car::where('agent_id', $user->id)->count();

        if($total_car >= $max_car){
            $notification = trans('translate.Your car limitation has exceeded');
            $notification = array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->route('admin.select-car-purpose')->with($notification);
        }

        $car = new Car();


        if($request->thumb_image) {
            $image_path = uploadFile($request->thumb_image, 'uploads/custom-images');
            $car->thumb_image = $image_path;
        }


        if ($request->video_image) {
            $image_path = uploadFile($request->video_image, 'uploads/custom-images');
            $car->video_image = $image_path;
        }

        $car->agent_id = $request->agent_id;
        $car->brand_id = $request->brand_id;
        $car->city_id = $request->city_id;
        $car->country_id = $request->country_id;
        $car->slug = $request->slug;
        $car->features = json_encode($request->features);
        $car->purpose = $request->purpose;
        $car->rent_period = null;
        $car->condition = $request->condition;
        $car->regular_price = $request->regular_price;
        $car->offer_price = $request->offer_price;
        $car->video_id = $request->video_id;
        $car->google_map = $request->google_map;
        $car->body_type = $request->body_type;
        $car->engine_size = $request->engine_size;
        $car->drive = $request->drive;
        $car->interior_color = $request->interior_color;
        $car->exterior_color = $request->exterior_color;
        $car->year = $request->year;
        $car->mileage = $request->mileage;
        $car->number_of_owner = $request->number_of_owner;
        $car->fuel_type = $request->fuel_type;
        $car->transmission = $request->transmission;
        $car->car_model = $request->car_model;
        $car->seller_type = $request->seller_type;
        $car->approved_by_admin = 'approved';
        $car->status = 'enable';
        if($active_plan->expiration_date == 'lifetime'){
            $car->expired_date = null;
            $car->save();
        }else{
            $car->expired_date = $active_plan->expiration_date;
            $car->save();
        }
        $car->save();

        $languages = Language::all();
        foreach($languages as $language){
            $car_translate = new CarTranslation();
            $car_translate->lang_code = $language->lang_code;
            $car_translate->car_id = $car->id;
            $car_translate->title = $request->title;
            $car_translate->description = $request->description;
            $car_translate->video_description = $request->video_description;
            $car_translate->address = $request->address;
            $car_translate->seo_title = $request->seo_title ? $request->seo_title : $request->title;
            $car_translate->seo_description = $request->seo_description ? $request->seo_description : $request->title;
            $car_translate->save();
        }


        $notification= trans('translate.Created Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.car.edit', ['car' => $car->id, 'lang_code' => admin_lang()] )->with($notification);
    }


    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit(Request $request, $id)
    {

        $countries = Country::latest()->get();

        $car = Car::findOrFail($id);
        $car_translate = CarTranslation::where(['car_id' => $id, 'lang_code' => $request->lang_code])->first();

        if($car->purpose == 'Rent'){
            abort(404);

        }elseif($car->purpose == 'Sale'){

            $brands = Brand::with('translate')->where('status', 'enable')->get();
            $cities = City::with('translate')->where('country_id', $car->country_id)->get();
            $features = Feature::with('translate')->get();
            $dealers = User::all();

            $existing_features = array();
            if($car->features != 'null'){
                $existing_features = json_decode($car->features);
            }

            return view('car::edit_sale_car', compact('brands', 'cities', 'features', 'dealers', 'car', 'car_translate', 'existing_features', 'countries'));

        }else{
            abort(404);
        };
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(CarRequest $request, $id)
    {
        $car = Car::findOrFail($id);
        if($request->lang_code == admin_lang()){

            if($request->thumb_image) {
                $image_path = uploadFile($request->thumb_image, 'uploads/custom-images', $car->thumb_image);
                $car->thumb_image = $image_path;
                $car->save();
            }

            if ($request->video_image) {
                $image_path = uploadFile($request->video_image, 'uploads/custom-images', $car->video_image);
                $car->video_image = $image_path;
                $car->save();
            }

            $car->agent_id = $request->agent_id;
            $car->brand_id = $request->brand_id;
            $car->city_id = $request->city_id;
            $car->country_id = $request->country_id;
            $car->slug = $request->slug;
            $car->features = json_encode($request->features);
            $car->purpose = $request->purpose;
            $car->rent_period = null;
            $car->condition = $request->condition;
            $car->regular_price = $request->regular_price;
            $car->offer_price = $request->offer_price;
            $car->video_id = $request->video_id;
            $car->google_map = $request->google_map;
            $car->body_type = $request->body_type;
            $car->engine_size = $request->engine_size;
            $car->drive = $request->drive;
            $car->interior_color = $request->interior_color;
            $car->exterior_color = $request->exterior_color;
            $car->year = $request->year;
            $car->mileage = $request->mileage;
            $car->number_of_owner = $request->number_of_owner;
            $car->fuel_type = $request->fuel_type;
            $car->transmission = $request->transmission;
            $car->car_model = $request->car_model;
            $car->seller_type = $request->seller_type;
            $car->save();

        }

        $car_translate = CarTranslation::findOrFail($request->translate_id);
        $car_translate->title = $request->title;
        $car_translate->description = $request->description;
        $car_translate->video_description = $request->video_description;
        $car_translate->address = $request->address;
        $car_translate->seo_title = $request->seo_title ? $request->seo_title : $request->title;
        $car_translate->seo_description = $request->seo_description ? $request->seo_description : $request->title;
        $car_translate->save();

        $notification= trans('translate.Updated Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $car = Car::findOrFail($id);
        $old_image = $car->thumb_image;
        $old_video_image = $car->video_image;

        if($old_image){
            if(File::exists(public_path().'/'.$old_image))unlink(public_path().'/'.$old_image);
        }

        if($old_video_image){
            if(File::exists(public_path().'/'.$old_video_image))unlink(public_path().'/'.$old_video_image);
        }


        CarTranslation::where('car_id',$id)->delete();
        Review::where('car_id',$id)->delete();
        Wishlist::where('car_id',$id)->delete();

        $galleries = CarGallery::where('car_id', $id)->get();
        foreach($galleries as $gallery){
            $old_image = $gallery->image;

            if($old_image){
                if(File::exists(public_path().'/'.$old_image))unlink(public_path().'/'.$old_image);
            }

            $gallery->delete();
        }

        $car->delete();

        $notification=  trans('translate.Delete Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.car.index')->with($notification);
    }

    public function car_gallery($id){
        $car = Car::findOrFail($id);

        $galleries = CarGallery::where('car_id', $id)->get();

        return view('car::gallery', compact('car', 'galleries'));
    }

    public function upload_car_gallery(Request $request, $id){

        $car = Car::findOrFail($id);

        foreach ($request->file as $index => $image) {
            $gallery_image = new CarGallery();

            if($image) {

                $image_name = 'car-gallery'.date('-Y-m-d-h-i-s-').rand(999,9999).'.webp';
                $image_name = 'uploads/custom-images/'.$image_name;
                $manager = new ImageManager(['driver' => 'gd']);
                $image = $manager->make($image);

                $user = User::findOrFail($car->agent_id);

                $author_name = '©'. $user->name;

                $author_name = explode(' ', trim($author_name))[0];

                $image->text($author_name, $image->width() / 2, $image->height() - 50, function($font) {
                    $font->file(public_path('fonts/static/Quicksand-Bold.ttf'));
                    $font->size(40);
                    $font->color([255, 255, 255, 0.5]);
                    $font->align('center');
                    $font->valign('bottom');
                });

                $image->encode('webp', 80)->save(public_path().'/'.$image_name);

                $gallery_image->image = $image_name;

            }

            $gallery_image->car_id = $id;
            $gallery_image->save();
        }

        if ($gallery_image) {
            return response()->json([
                'message' => trans('translate.Images uploaded successfully'),
                'url' => route('admin.car-gallery', $id),
            ]);
        } else {
             return response()->json([
                'message' => trans('translate.Images uploaded Failed'),
                'url' => route('admin.car-gallery', $id),
            ]);
        }

    }

    public function delete_car_gallery($id){
        $gallery = CarGallery::findOrFail($id);
        $old_image = $gallery->image;

        if($old_image){
            if(File::exists(public_path().'/'.$old_image))unlink(public_path().'/'.$old_image);
        }

        $gallery->delete();

        $notification=  trans('translate.Delete Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);

    }

    public function assign_language($lang_code){
        $car_translates = CarTranslation::where('lang_code', admin_lang())->get();
        foreach($car_translates as $car_translate){
            $translate = new CarTranslation();
            $translate->car_id = $car_translate->car_id;
            $translate->lang_code = $lang_code;
            $translate->title = $car_translate->title;
            $translate->description = $car_translate->description;
            $translate->video_description = $car_translate->video_description;
            $translate->address = $car_translate->address;
            $translate->seo_title = $car_translate->seo_title;
            $translate->seo_description = $car_translate->seo_description;
            $translate->save();
        }
    }

    public function car_approval($id){

        $car = Car::findOrFail($id);
        $car->approved_by_admin = 'approved';
        $car->status = 'enable';
        $car->save();

        $notification=  trans('translate.Apporval Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

    public function car_featured($id){

        $car = Car::findOrFail($id);

        $active_plan = SubscriptionHistory::where('user_id', $car->agent_id)->latest()->first();

        if(!$active_plan){
            $notification=  trans('translate.User did not enrolled any plan yet.');
            $notification=array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->route('admin.select-car-purpose')->with($notification);
        }

        $featured_car = $active_plan->featured_car;

        $total_car = Car::where('agent_id', $car->agent_id)->where('is_featured', 'enable')->count();

        if($total_car >= $featured_car){
            $notification = trans('translate.Your car limitation has exceeded');
            $notification = array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->route('admin.select-car-purpose')->with($notification);
        }

        $car->is_featured = 'enable';
        $car->save();

        $notification=  trans('translate.Featured Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

    public function car_removed_featured($id){

        $car = Car::findOrFail($id);
        $car->is_featured = 'disable';
        $car->save();

        $notification=  trans('translate.Removed from featured successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }



    public function review_list(){

        $reviews = Review::with('user','car')->latest()->get();

        return view('car::reviews', ['reviews' => $reviews]);
    }

    public function review_detail($id){

        $review = Review::with('user','car')->findOrFail($id);

        return view('car::review_show', ['review' => $review]);
    }

    public function review_delete($id){

        $review = Review::with('user','car')->findOrFail($id);
        $review->delete();


        $notification=  trans('translate.Deleted Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.review-list')->with($notification);

    }

    public function review_approval($id){

        $review = Review::with('user','car')->findOrFail($id);
        $review->status = 'enable';
        $review->save();

        $notification=  trans('translate.Review approval successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }









}

<?php

namespace Modules\Car\Http\Controllers\API;

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
use Modules\Car\Http\Requests\CarBasicRequest;
use Modules\Car\Http\Requests\CarAddressRequest;
use Modules\Car\Http\Requests\CarKeyFeatureRequest;
use Modules\Subscription\Entities\SubscriptionHistory;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $user = Auth::guard('api')->user();

        $cars = Car::with('brand')->where('agent_id', $user->id)->paginate(15);

         return response()->json(['cars' => $cars]);
    }

    public function select_car_purpose()
    {
        return view('car::frontend.select_car_purpose');
    }



    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create(Request $request)
    {

        $user = Auth::guard('api')->user();

        $active_plan = SubscriptionHistory::where('user_id', $user->id)->latest()->first();

        if(!$active_plan){
            $notification=  trans('translate.Please enroll first');

            return response()->json(['message' => $notification], 403);
        }

        $expiration_date = $active_plan->expiration_date;

        if($expiration_date != 'lifetime'){
            if(date('Y-m-d') > $expiration_date){
                $notification = trans('translate.Your plan is expired, please renew or re-order');
                return response()->json(['message' => $notification], 403);
            }
        }

        $max_car = $active_plan->max_car;

        $total_car = Car::where('agent_id', $user->id)->count();

        if($total_car >= $max_car){
            $notification = trans('translate.Your car limitation has exceeded');
            return response()->json(['message' => $notification], 403);
        }

        $brands = Brand::where('status', 'enable')->get();
        $features = Feature::get();

        return response()->json([
            'brands' => $brands,
            'features' => $features
        ]);

    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(CarBasicRequest $request)
    {
        $car = new Car();

        $user = Auth::guard('api')->user();

        $car->agent_id = $user->id;
        $car->brand_id = $request->brand_id;
        // $car->city_id = $request->city_id;
        // $car->country_id = $request->country_id;
        $car->slug = $request->slug;
        $car->condition = $request->condition;
        $car->regular_price = $request->regular_price;
        $car->offer_price = $request->offer_price;
        $car->is_draft = 'enable';
        $car->purpose = $request->purpose;

        $active_plan = SubscriptionHistory::where('user_id', $user->id)->latest()->first();

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
            $car_translate->address = 'default';
            $car_translate->seo_title = $request->seo_title ? $request->seo_title : $request->title;
            $car_translate->seo_description = $request->seo_description ? $request->seo_description : $request->title;
            $car_translate->save();
        }

        $car = Car::where('id', $car->id)->firstOrFail();

        $car_translate = CarTranslation::where(['car_id' => $car->id, 'lang_code' => admin_lang()])->first();

        $notification= trans('translate.Created Successfully');

        return response()->json([
            'message' => $notification,
            'car' => $car,
            'car_translate' => $car_translate,
        ]);
    }




    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit(Request $request, $id)
    {

        $user = Auth::guard('api')->user();

        $car = Car::where('agent_id', $user->id)->where('id', $id)->first();

        if(!$car){
            return response()->json(['message' => trans('Not found')], 403);
        }

        $car_translate = CarTranslation::where(['car_id' => $id, 'lang_code' => $request->lang_code])->first();

        $brands = Brand::where('status', 'enable')->get();
        $cities = City::where('country_id', $car->country_id)->get();
        $countries = Country::latest()->get();

        $features = Feature::all();

        $existing_features = array();

        if($car->features != 'null'){
            if(is_array(json_decode($car->features))){
                $existing_features = json_decode($car->features);
            }
        }

        return response()->json([
            'brands' => $brands,
            'features' => $features,
            'countries' => $countries,
            'cities' => $cities,
            'existing_features' => $existing_features,
            'car' => $car,
            'car_translate' => $car_translate,
        ]);


    }


    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(CarBasicRequest $request, $id)
    {
        $car = Car::find($id);

        if(!$car){
            return response()->json(['message' => trans('Not found')], 403);
        }

        if($request->lang_code == admin_lang()){

            $car->brand_id = $request->brand_id;
            $car->condition = $request->condition;
            $car->regular_price = $request->regular_price;
            $car->offer_price = $request->offer_price;
            $car->save();

        }

        $car_translate = CarTranslation::findOrFail($request->translate_id);
        $car_translate->title = $request->title;
        $car_translate->description = $request->description;
        $car_translate->seo_title = $request->seo_title ? $request->seo_title : $request->title;
        $car_translate->seo_description = $request->seo_description ? $request->seo_description : $request->title;
        $car_translate->save();

        $notification= trans('translate.Updated Successfully');
        return response()->json([
            'message' => $notification,
            'car' => $car,
            'car_translate' => $car_translate,
        ]);
    }


    public function car_key_feature(CarKeyFeatureRequest $request, $id)
    {
        $car = Car::find($id);

        if(!$car){
            return response()->json(['message' => trans('Not found')], 403);
        }



        $car->seller_type = $request->seller_type;
        $car->body_type = $request->body_type;
        $car->engine_size = $request->engine_size;
        $car->interior_color = $request->interior_color;
        $car->exterior_color = $request->exterior_color;
        $car->year = $request->year;
        $car->mileage = $request->mileage;
        $car->number_of_owner = $request->number_of_owner;
        $car->fuel_type = $request->fuel_type;
        $car->transmission = $request->transmission;
        $car->drive = $request->drive;
        $car->save();

        $car = Car::where('id', $car->id)->firstOrFail();

        $car_translate = CarTranslation::where(['car_id' => $car->id, 'lang_code' => admin_lang()])->first();

        $notification= trans('Updated Successfully');

        return response()->json([
            'message' => $notification,
            'car' => $car,
            'car_translate' => $car_translate,
        ]);

    }


    public function car_feature(Request $request, $id)
    {
        $car = Car::find($id);

        if(!$car){
            return response()->json(['message' => trans('Not found')], 403);
        }


        $car->features = json_encode($request->features);
        $car->save();


        $car = Car::where('id', $car->id)->firstOrFail();

        $car_translate = CarTranslation::where(['car_id' => $car->id, 'lang_code' => admin_lang()])->first();

        $notification= trans('Updated Successfully');

        return response()->json([
            'message' => $notification,
            'car' => $car,
            'car_translate' => $car_translate,
        ]);


    }

    public function car_address(CarAddressRequest $request, $id)
    {
        $car = Car::find($id);

        if(!$car){
            return response()->json(['message' => trans('Not found')], 403);
        }


        $car->city_id = $request->city_id;
        $car->country_id = $request->country_id;
        $car->google_map = $request->google_map;
        $car->save();

        $car_translate = CarTranslation::where(['car_id' => $car->id, 'lang_code' => admin_lang()])->first();
        $car_translate->address = $request->address;
        $car_translate->save();

        $car = Car::where('id', $car->id)->firstOrFail();

        $notification= trans('Updated Successfully');

        return response()->json([
            'message' => $notification,
            'car' => $car,
            'car_translate' => $car_translate,
        ]);


    }


     public function video_images(Request $request, $id)
    {
        $car = Car::find($id);

        if(!$car){
            return response()->json(['message' => trans('Not found')], 403);
        }

        $car->video_id = $request->video_id;
        $car->save();


        if($request->thumb_image) {

            $old_image = $car->thumb_image;

            $image_name = 'car'.date('-Y-m-d-h-i-s-').rand(999,9999).'.webp';
            $image_name = 'uploads/custom-images/'.$image_name;
            $manager = new ImageManager(['driver' => 'gd']);
            $image = $manager->make($request->thumb_image);

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

            $car->thumb_image = $image_name;
            $car->save();

            if($old_image){
                if(File::exists(public_path().'/'.$old_image))unlink(public_path().'/'.$old_image);
            }

        }


        if($request->video_image){
            $old_image = $car->video_image;
            $image_name = 'car-video-'.date('-Y-m-d-h-i-s-').rand(999,9999).'.webp';
            $image_name ='uploads/custom-images/'.$image_name;
            Image::make($request->video_image)
                ->encode('webp', 80)
                ->save(public_path().'/'.$image_name);
            $car->video_image = $image_name;
            $car->save();

            if($old_image){
                if(File::exists(public_path().'/'.$old_image))unlink(public_path().'/'.$old_image);
            }
        }


        foreach ($request->file ?? [] as $index => $image) {
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


        $car_translate = CarTranslation::where(['car_id' => $car->id, 'lang_code' => admin_lang()])->first();
        $car_translate->video_description = $request->video_description;
        $car_translate->save();

        $car = Car::where('id', $car->id)->firstOrFail();



        $galleries = CarGallery::where('car_id', $car->id)->get();

        $notification= trans('Updated Successfully');

        return response()->json([
            'message' => $notification,
            'car' => $car,
            'car_translate' => $car_translate,
            'galleries' => $galleries,
        ]);


    }


    public function request_to_publish(Request $request, $id)
    {
        $car = Car::find($id);

        if(!$car){
            return response()->json(['message' => trans('Not found')], 403);
        }

        if($car->is_draft == 'enable'){
            $car->is_draft = 'disable';
            $car->save();
        }else{
            return response()->json(['message' => trans('Publish request already send')], 403);
        }


        $notification= trans('Publish request send to admin, please await for approval');

        return response()->json([
            'message' => $notification,
        ]);


    }




    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $car = Car::find($id);

        if(!$car){
            return response()->json(['message' => trans('Not found')], 403);
        }

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
        return response()->json(['message' => $notification]);
    }


    public function image_delete($id){
        $gallery = CarGallery::find($id);

        if(!$gallery){
            return response()->json(['message' => trans('Not found')], 403);
        }

        $old_image = $gallery->image;

        if($old_image){
            if(File::exists(public_path().'/'.$old_image))unlink(public_path().'/'.$old_image);
        }

        $gallery->delete();

        $notification=  trans('translate.Delete Successfully');
       return response()->json(['message' => $notification]);

    }
}

@extends('admin.master_layout')
@section('title')
    <title>{{ __('translate.Car for Sale') }}</title>
@endsection

@section('body-header')
    <h3 class="crancy-header__title m-0">{{ __('translate.Car for Sale') }}</h3>
    <p class="crancy-header__text">{{ __('translate.Manage Car') }} >> {{ __('translate.Car for Sale') }}</p>
@endsection

@section('body-content')

    <form action="{{ route('admin.car.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

    <!-- crancy Dashboard -->
    <section class="crancy-adashboard crancy-show">
        <div class="container container__bscreen">
            <div class="row">
                <div class="col-12">
                    <div class="crancy-body">
                        <!-- Dashboard Inner -->
                        <div class="crancy-dsinner">
                            <div class="row">
                                <div class="col-12 mg-top-30">
                                    <!-- Product Card -->
                                    <div class="crancy-product-card">
                                        <div class="create_new_btn_inline_box">
                                            <h4 class="crancy-product-card__title">{{ __('translate.Basic Information') }}</h4>
                                        </div>

                                        <div class="row">
                                            <input type="hidden" name="purpose" value="{{ request()->get('purpose') }}">

                                            <div class="col-12 mg-top-form-20">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="crancy__item-form--group w-100 h-100">
                                                            <label class="crancy__item-label">{{ __('translate.Thumbnail Image') }} * 
                                                                <i 
                                                                    class="fas fa-info-circle text-info"
                                                                    data-toggle="tooltip"
                                                                    data-placement="right"
                                                                    title="Recommended size: 329x203"
                                                                    style="cursor: pointer;"
                                                                ></i>
                                                            </label>
                                                            <div class="crancy-product-card__upload crancy-product-card__upload--border">
                                                                <input type="file" class="btn-check" name="thumb_image" id="input-img1" autocomplete="off" onchange="previewImage(event)">
                                                                <label class="crancy-image-video-upload__label" for="input-img1">
                                                                    <img id="view_img" src="{{ getImageOrPlaceholder($setting->placeholder_image, '329x203') }}">
                                                                    <h4 class="crancy-image-video-upload__title">{{ __('translate.Click here to') }} <span class="crancy-primary-color">{{ __('translate.Choose File') }}</span> {{ __('translate.and upload') }} </h4>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="col-md-6">
                                                <div class="crancy__item-form--group mg-top-form-20">
                                                    <label class="crancy__item-label">{{ __('translate.Dealer') }} * </label>
                                                    <select class="form-select crancy__item-input select2" name="agent_id">
                                                        <option value="">{{ __('translate.Select Dealer') }}</option>
                                                        @foreach ($dealers as $dealer)
                                                            <option  {{ $dealer->id == old('agent_id') ? 'selected' : '' }} value="{{ $dealer->id }}">{{ $dealer->name }} - {{ $dealer->email }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="crancy__item-form--group mg-top-form-20">
                                                    <label class="crancy__item-label">{{ __('translate.Title') }} * </label>
                                                    <input class="crancy__item-input" type="text" name="title" id="title" value="{{ old('title') }}">
                                                </div>
                                            </div>


                                            <div class="col-md-6">
                                                <div class="crancy__item-form--group mg-top-form-20">
                                                    <label class="crancy__item-label">{{ __('translate.Slug') }} * </label>
                                                    <input class="crancy__item-input" type="text" name="slug" id="slug" value="{{ old('slug') }}">
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="crancy__item-form--group mg-top-form-20">
                                                    <label class="crancy__item-label">{{ __('translate.Brand') }} * </label>
                                                    <select class="form-select crancy__item-input select2" name="brand_id">
                                                        <option value="">{{ __('translate.Select Brand') }}</option>
                                                        @foreach ($brands as $brand)
                                                        <option  {{ $brand->id == old('brand_id') ? 'selected' : '' }} value="{{ $brand->id }}">{{ $brand->translate->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="crancy__item-form--group mg-top-form-20">
                                                    <label class="crancy__item-label">{{ __('translate.Country') }} * </label>
                                                    <select class="form-select crancy__item-input " name="country_id" id="country_id">
                                                        <option value="">{{ __('translate.Select Country') }}</option>
                                                        @foreach ($countries as $country)
                                                            <option {{ $country->id == old('country_id') ? 'selected' : '' }} value="{{ $country->id }}">{{ $country->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>


                                            <div class="col-md-6">
                                                <div class="crancy__item-form--group mg-top-form-20">
                                                    <label class="crancy__item-label">{{ __('translate.City') }} * </label>
                                                    <select class="form-select crancy__item-input select2" name="city_id" id="city_id">
                                                        <option value="">{{ __('translate.Select City') }}</option>

                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="crancy__item-form--group mg-top-form-20">
                                                    <label class="crancy__item-label">{{ __('translate.Regular Price') }} * </label>
                                                    <input class="crancy__item-input" type="text" name="regular_price" id="regular_price" value="{{ old('regular_price') }}">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="crancy__item-form--group mg-top-form-20">
                                                    <label class="crancy__item-label">{{ __('translate.Offer Price') }} </label>
                                                    <input class="crancy__item-input" type="text" name="offer_price" id="offer_price" value="{{ old('offer_price') }}">
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <div class="crancy__item-form--group mg-top-form-20">
                                                    <label class="crancy__item-label">{{ __('translate.Description') }} * </label>

                                                    <textarea class="crancy__item-input crancy__item-textarea summernote"  name="description" id="description">{{ old('description') }}</textarea>

                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                    <!-- End Product Card -->
                                </div>
                            </div>
                        </div>
                        <!-- End Dashboard Inner -->
                    </div>
                </div>

            </div>
        </div>
    </section>
    <!-- End crancy Dashboard -->

    <!-- crancy Dashboard -->
    <section class="crancy-adashboard crancy-show">
        <div class="container container__bscreen">
            <div class="row">
                <div class="col-12">
                    <div class="crancy-body">
                        <!-- Dashboard Inner -->
                        <div class="crancy-dsinner">
                            <div class="row">
                                <div class="col-12">
                                    <!-- Product Card -->
                                    <div class="crancy-product-card">
                                        <div class="create_new_btn_inline_box">
                                            <h4 class="crancy-product-card__title">{{ __('translate.Key Information') }}</h4>
                                        </div>

                                        <div class="row">

                                            <div class="col-md-3">
                                                <div class="crancy__item-form--group mg-top-form-20">
                                                    <label class="crancy__item-label">{{ __('translate.Condition') }} * </label>
                                                    <select class="form-select crancy__item-input" name="condition">
                                                        <option {{ 'Used' == old('condition') ? 'selected' : '' }} value="Used">{{ __('translate.Used') }}</option>
                                                        <option {{ 'New' == old('condition') ? 'selected' : '' }} value="New">{{ __('translate.New') }}</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="crancy__item-form--group mg-top-form-20">
                                                    <label class="crancy__item-label">{{ __('translate.Seller Type') }} * </label>
                                                    <select class="form-select crancy__item-input" name="seller_type">
                                                        <option {{ 'Dealer' == old('seller_type') ? 'selected' : '' }}  value="Dealer">{{ __('translate.Dealer') }}</option>
                                                        <option {{ 'Personal' == old('seller_type') ? 'selected' : '' }} value="Personal">{{ __('translate.Indivisual') }}</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="crancy__item-form--group mg-top-form-20">
                                                    <label class="crancy__item-label">{{ __('translate.Body Type') }} * </label>
                                                    <input class="crancy__item-input" type="text" name="body_type" id="body_type" value="{{ old('body_type') }}">
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="crancy__item-form--group mg-top-form-20">
                                                    <label class="crancy__item-label">{{ __('translate.Engine Size') }} * </label>
                                                    <input class="crancy__item-input" type="text" name="engine_size" id="engine_size" value="{{ old('engine_size') }}">
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="crancy__item-form--group mg-top-form-20">
                                                    <label class="crancy__item-label">{{ __('translate.Drive') }} * </label>
                                                    <input class="crancy__item-input" type="text" name="drive" id="drive" value="{{ old('drive') }}">
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="crancy__item-form--group mg-top-form-20">
                                                    <label class="crancy__item-label">{{ __('translate.Interior Color') }} * </label>
                                                    <input class="crancy__item-input" type="text" name="interior_color" id="interior_color" value="{{ old('interior_color') }}">
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="crancy__item-form--group mg-top-form-20">
                                                    <label class="crancy__item-label">{{ __('translate.Exterior Color') }} * </label>
                                                    <input class="crancy__item-input" type="text" name="exterior_color" id="exterior_color" value="{{ old('exterior_color') }}">
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="crancy__item-form--group mg-top-form-20">
                                                    <label class="crancy__item-label">{{ __('translate.Year') }} * </label>
                                                    <input class="crancy__item-input" type="text" name="year" id="year" value="{{ old('year') }}">
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="crancy__item-form--group mg-top-form-20">
                                                    <label class="crancy__item-label">{{ __('translate.Mileage') }} * </label>
                                                    <input class="crancy__item-input" type="text" name="mileage" id="mileage" value="{{ old('mileage') }}">
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="crancy__item-form--group mg-top-form-20">
                                                    <label class="crancy__item-label">{{ __('translate.Number of Owner') }} * </label>
                                                    <input class="crancy__item-input" type="text" name="number_of_owner" id="number_of_owner" value="{{ old('number_of_owner') }}">
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="crancy__item-form--group mg-top-form-20">
                                                    <label class="crancy__item-label">{{ __('translate.Fuel Type') }} * </label>
                                                    <input class="crancy__item-input" type="text" name="fuel_type" id="fuel_type" value="{{ old('fuel_type') }}">
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="crancy__item-form--group mg-top-form-20">
                                                    <label class="crancy__item-label">{{ __('translate.Transmission') }} * </label>
                                                    <input class="crancy__item-input" type="text" name="transmission" id="transmission" value="{{ old('transmission') }}">
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-3">
                                                <div class="crancy__item-form--group mg-top-form-20"> 
                                                    <label class="crancy__item-label">{{ __('translate.Car Model') }} * </label>
                                                    <input class="crancy__item-input" type="text" name="car_model" id="car_model" value="{{ old('car_model') }}">
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                    <!-- End Product Card -->
                                </div>
                            </div>
                        </div>
                        <!-- End Dashboard Inner -->
                    </div>
                </div>

            </div>
        </div>
    </section>
    <!-- End crancy Dashboard -->

    <!-- crancy Dashboard -->
    <section class="crancy-adashboard crancy-show">
        <div class="container container__bscreen">
            <div class="row">
                <div class="col-12">
                    <div class="crancy-body">
                        <!-- Dashboard Inner -->
                        <div class="crancy-dsinner">
                            <div class="row">
                                <div class="col-12">
                                    <!-- Product Card -->
                                    <div class="crancy-product-card">
                                        <div class="create_new_btn_inline_box">
                                            <h4 class="crancy-product-card__title">{{ __('translate.Features') }}</h4>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="crancy__item-form--group mg-top-form-20">
                                                    <div class="crancy__item-form--added">
                                                        @foreach ($features as $index => $feature)
                                                            <div class="form-group crancy-form-checkbox__added mg-top-15">
                                                                <input class="d-none" type="checkbox" id="add1{{ $index }}" name="features[]" value="{{ $feature->id }}">
                                                                <label class="crancy-form-labe2" for="add1{{ $index }}">{{ $feature->translate->name }} <span><i class="fas fa-times"></i></span></label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                    <!-- End Product Card -->
                                </div>
                            </div>
                        </div>
                        <!-- End Dashboard Inner -->
                    </div>
                </div>

            </div>
        </div>
    </section>
    <!-- End crancy Dashboard -->

    <!-- crancy Dashboard -->
    <section class="crancy-adashboard crancy-show">
        <div class="container container__bscreen">
            <div class="row">
                <div class="col-12">
                    <div class="crancy-body">
                        <!-- Dashboard Inner -->
                        <div class="crancy-dsinner">
                            <div class="row">
                                <div class="col-12">
                                    <!-- Product Card -->
                                    <div class="crancy-product-card">
                                        <div class="create_new_btn_inline_box">
                                            <h4 class="crancy-product-card__title">{{ __('translate.Video Information') }}</h4>
                                        </div>

                                        <div class="row">

                                            <div class="col-12 mg-top-form-20">
                                                <div class="row ">
                                                    <div class="col-md-3">
                                                        <div class="crancy__item-form--group w-100 h-100">
                                                            <label class="crancy__item-label">{{ __('translate.Image') }} 
                                                                <i 
                                                                    class="fas fa-info-circle text-info"
                                                                    data-toggle="tooltip"
                                                                    data-placement="right"
                                                                    title="Recommended size: 874x398"
                                                                    style="cursor: pointer;"
                                                                ></i>
                                                            </label>
                                                            <div class="crancy-product-card__upload crancy-product-card__upload--border">
                                                                <input type="file" class="btn-check" name="video_image" id="video_image" autocomplete="off" onchange="previewVideoImage(event)">
                                                                <label class="crancy-image-video-upload__label" for="video_image">
                                                                    <img id="view_video_img" src="{{ getImageOrPlaceholder($setting->placeholder_image, '874x398') }}">
                                                                    <h4 class="crancy-image-video-upload__title">{{ __('translate.Click here to') }} <span class="crancy-primary-color">{{ __('translate.Choose File') }}</span> {{ __('translate.and upload') }} </h4>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="col-12">
                                                <div class="crancy__item-form--group mg-top-form-20">
                                                    <label class="crancy__item-label">{{ __('translate.Youtube Video Id') }} </label>
                                                    <input class="crancy__item-input" type="text" name="video_id" id="video_id" value="{{ old('video_id') }}">
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <div class="crancy__item-form--group mg-top-form-20">
                                                    <label class="crancy__item-label">{{ __('translate.Description') }} </label>
                                                    <textarea class="crancy__item-input crancy__item-textarea seo_description_box"  name="video_description" id="video_description">{{ old('video_description') }}</textarea>
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                    <!-- End Product Card -->
                                </div>
                            </div>
                        </div>
                        <!-- End Dashboard Inner -->
                    </div>
                </div>

            </div>
        </div>
    </section>
    <!-- End crancy Dashboard -->

    <!-- crancy Dashboard -->
    <section class="crancy-adashboard crancy-show">
        <div class="container container__bscreen">
            <div class="row">
                <div class="col-12">
                    <div class="crancy-body">
                        <!-- Dashboard Inner -->
                        <div class="crancy-dsinner">
                            <div class="row">
                                <div class="col-12">
                                    <!-- Product Card -->
                                    <div class="crancy-product-card">
                                        <div class="create_new_btn_inline_box">
                                            <h4 class="crancy-product-card__title">{{ __('translate.Address & Google Map') }}</h4>
                                        </div>

                                        <div class="row">
                                            <div class="col-12">
                                                <div class="crancy__item-form--group mg-top-form-20">
                                                    <label class="crancy__item-label">{{ __('translate.Address') }} * </label>
                                                    <input class="crancy__item-input" type="text" name="address" id="address" value="{{ old('address') }}">
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <div class="crancy__item-form--group mg-top-form-20">
                                                    <label class="crancy__item-label">{{ __('translate.Google Map Embed Link') }} * </label>
                                                    <textarea class="crancy__item-input crancy__item-textarea seo_description_box"  name="google_map" id="google_map">{{ old('google_map') }}</textarea>
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                    <!-- End Product Card -->
                                </div>
                            </div>
                        </div>
                        <!-- End Dashboard Inner -->
                    </div>
                </div>

            </div>
        </div>
    </section>
    <!-- End crancy Dashboard -->

    <!-- crancy Dashboard -->
    <section class="crancy-adashboard crancy-show">
        <div class="container container__bscreen">
            <div class="row">
                <div class="col-12">
                    <div class="crancy-body">
                        <!-- Dashboard Inner -->
                        <div class="crancy-dsinner">
                            <div class="row">
                                <div class="col-12">
                                    <!-- Product Card -->
                                    <div class="crancy-product-card">
                                        <div class="create_new_btn_inline_box">
                                            <h4 class="crancy-product-card__title">{{ __('translate.SEO Information') }}</h4>
                                        </div>

                                        <div class="row">
                                            <div class="col-12">
                                                <div class="crancy__item-form--group mg-top-form-20">
                                                    <label class="crancy__item-label">{{ __('translate.SEO title') }} </label>
                                                    <input class="crancy__item-input" type="text" name="seo_title" id="seo_title" value="{{ old('seo_title') }}">
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <div class="crancy__item-form--group mg-top-form-20">
                                                    <label class="crancy__item-label">{{ __('translate.SEO Description') }} </label>
                                                    <textarea class="crancy__item-input crancy__item-textarea seo_description_box"  name="seo_description" id="seo_description">{{ old('seo_description') }}</textarea>
                                                </div>
                                            </div>

                                        </div>

                                        <button class="crancy-btn mg-top-25" type="submit">{{ __('translate.Save Data') }}</button>

                                    </div>
                                    <!-- End Product Card -->
                                </div>
                            </div>
                        </div>
                        <!-- End Dashboard Inner -->
                    </div>
                </div>

            </div>
        </div>
    </section>
    <!-- End crancy Dashboard -->


    </form>



@endsection


@push('style_section')
    <style>
        .tox .tox-promotion,
        .tox-statusbar__branding{
            display: none !important;
        }
    </style>
@endpush

@push('js_section')

    <script src="{{ asset('global/tinymce/js/tinymce/tinymce.min.js') }}"></script>

    <script>
        (function($) {
            "use strict"
            $(document).ready(function () {
                $("#title").on("keyup",function(e){
                    let inputValue = $(this).val();
                    let slug = inputValue.toLowerCase().replace(/[^\w ]+/g,'').replace(/ +/g,'-');
                    $("#slug").val(slug);
                })

                $("#country_id").on("change", function(e){
                    let country_id = $(this).val();

                    if(country_id){
                        $.ajax({
                            type: "get",
                            url: "{{ url('cities-by-country') }}" + "/" + country_id,
                            success: function(response) {
                                $("#city_id").html(response)

                            },
                            error: function(response){
                                let empty_html = `<option value="">{{ __('translate.Select City') }}</option>`;
                                $("#city_id").html(empty_html)
                            }
                        });
                    }else{
                        let empty_html = `<option value="">{{ __('translate.Select City') }}</option>`;
                        $("#city_id").html(empty_html)
                    }
                })

                tinymce.init({
                    selector: '.summernote',
                    plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
                    toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
                    tinycomments_mode: 'embedded',
                    tinycomments_author: 'Author name',
                    mergetags_list: [
                        { value: 'First.Name', title: 'First Name' },
                        { value: 'Email', title: 'Email' },
                    ]
                });

            });
        })(jQuery);

        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function(){
                var output = document.getElementById('view_img');
                output.src = reader.result;
            }

            reader.readAsDataURL(event.target.files[0]);
        };

        function previewVideoImage(event) {
            var reader = new FileReader();
            reader.onload = function(){
                var output = document.getElementById('view_video_img');
                output.src = reader.result;
            }

            reader.readAsDataURL(event.target.files[0]);
        };

    </script>
@endpush


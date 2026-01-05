@extends('layout')
@section('title')
    <title>{{ __('translate.Create Rent Car') }}</title>
@endsection
@section('body-content')

<main>
    <!-- banner-part-start  -->

    <section class="inner-banner">
        <div class="inner-banner-img" style=" background-image: url({{ getImageOrPlaceholder($breadcrumb,'1905x300') }}) "></div>
            <div class="container">
                <div class="col-lg-12">

                    <div class="inner-banner-df">
                    <h1 class="inner-banner-taitel">{{ __('translate.Create Rent Car') }}</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('translate.Home') }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ __('translate.Create Rent Car') }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>
    <!-- banner-part-end -->

    <!-- dashboard-part-start -->
    <section class="dashboard">
        <div class="container">
            <div class="row">
                @include('profile.sidebar')

                <div class="col-lg-9">
                    <form action="{{ route('user.car.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <input type="hidden" name="purpose" value="{{ request()->get('purpose') }}">
                        <input type="hidden" name="agent_id" value="{{ Auth::guard('web')->user()->id }}">

                        <div class="row gy-5">

                            <!-- Car Images  -->

                            <div class="col-lg-12">
                                <div class="car-images">
                                    <h3 class="car-images-taitel">{{ __('translate.Thumbnail Image') }} </h3>
                                    <div class="car-images-inner">
                                        <h6 class="car-images-inner-txt">{{ __('translate.Upload New Image') }} <span>*</span>
                                         <i 
                                            class="fas fa-info-circle text-info"
                                            data-toggle="tooltip"
                                            data-placement="right"
                                            title="Recommended size: 329x203"
                                            style="cursor: pointer;"
                                        ></i></h6>

                                        <div class="row">
                                            <div class=" col-xl-3 col-lg-4">
                                                <div class="car-images-inner-item two">
                                                    <div class="car-images-inner-item-thumb">
                                                        <img src="{{ getImageOrPlaceholder($setting->placeholder_image, '329x203') }}" id="thumb_image">
                                                    </div>
                                                    <div class="choose-file-txt">
                                                        <h6>{{ __('translate.New') }} <span>{{ __('translate.Choose File') }}</span> {{ __('translate.Upload') }}</h6>
                                                        <input type="file" id="my-file" onchange="previewImage(event)" name="thumb_image">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>


                            <!-- Name & Description Overview  -->
                            <div class="col-lg-12">
                                <div class="car-images">
                                    <h3 class="car-images-taitel">{{ __('translate.Basic Information') }}</h3>

                                    <div class="car-images-inner">
                                        <div class="description-item">

                                            <div class="description-item-inner">
                                                <label for="title" class="form-label">{{ __('translate.Title') }}
                                                    <span>*</span> </label>
                                                <input type="text" class="form-control" id="title"
                                                    placeholder="{{ __('translate.Title') }}" name="title" value="{{ old('title') }}">
                                            </div>

                                            <div class="description-item-inner">
                                                <label for="slug" class="form-label">{{ __('translate.Slug') }}
                                                    <span>*</span> </label>
                                                <input type="text" class="form-control" id="slug"
                                                    placeholder="{{ __('translate.Slug') }}" name="slug" value="{{ old('slug') }}">
                                            </div>


                                        </div>
                                        <div class="description-item two">



                                            <div class="description-item-inner">
                                                <label for="brand" class="form-label">{{ __('translate.Brand') }}
                                                    <span>*</span> </label>
                                                <select class="form-select select2" name="brand_id">
                                                    <option value="">{{ __('translate.Select Brand') }}</option>
                                                    @foreach ($brands as $brand)
                                                        <option  {{ $brand->id == old('brand_id') ? 'selected' : '' }} value="{{ $brand->id }}">{{ $brand->translate->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>


                                            <div class="description-item-inner">
                                                <label for="country" class="form-label">{{ __('translate.Country') }}
                                                    <span>*</span> </label>
                                                <select class="form-select select2" name="country_id" id="country_id">
                                                    <option value="">{{ __('translate.Select Country') }}</option>
                                                    @foreach ($countries as $country)
                                                        <option {{ $country->id == old('country_id') ? 'selected' : '' }} value="{{ $country->id }}">{{ $country->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>


                                        </div>
                                        <div class="description-item two">

                                            <div class="description-item-inner">
                                                <label for="city" class="form-label">{{ __('translate.City') }}
                                                    <span>*</span> </label>
                                                <select class="form-select select2" name="city_id" id="city_id">
                                                    <option value="">{{ __('translate.Select City') }}</option>

                                                </select>
                                            </div>

                                            <div class="description-item-inner">
                                                <label for=" name="rent_period"" class="form-label">{{ __('translate.Rent Period') }}
                                                    <span>*</span> </label>
                                                <select class="form-select"  name="rent_period">
                                                    <option {{ 'Hourly' == old('rent_period') ? 'selected' : '' }} value="Hourly">{{ __('translate.Hourly') }}</option>
                                                    <option {{ 'Daily' == old('rent_period') ? 'selected' : '' }} value="Daily">{{ __('translate.Daily') }}</option>
                                                    <option {{ 'Monthly' == old('rent_period') ? 'selected' : '' }} value="Monthly">{{ __('translate.Monthly') }}</option>
                                                    <option {{ 'Yearly' == old('rent_period') ? 'selected' : '' }} value="Yearly">{{ __('translate.Yearly') }}</option>
                                                </select>
                                            </div>


                                        </div>
                                        <div class="description-item two">

                                            <div class="description-item-inner">
                                                <label for="regular_price" class="form-label">{{ __('translate.Regular Price') }}
                                                    <span>*</span> </label>
                                                <input type="text" class="form-control" placeholder="{{ __('translate.Regular Price') }}"  name="regular_price" value="{{ old('regular_price') }}">
                                            </div>

                                            <div class="description-item-inner">
                                                <label for="offer_price" class="form-label">{{ __('translate.Offer Price') }}
                                                </label>
                                                <input type="text" class="form-control" placeholder="{{ __('translate.Offer Price') }}" name="offer_price" value="{{ old('offer_price') }}">
                                            </div>

                                        </div>

                                        <div class="description-item two">

                                            <div class="description-item-inner">
                                                <label for="offer_price" class="form-label">{{ __('translate.Description') }}
                                                    <span>*</span>
                                                </label>
                                                <textarea class="summernote"  name="description" id="description">{!! old('description') !!}</textarea>
                                            </div>

                                        </div>


                                    </div>
                                </div>
                            </div>


                            <!-- Key Information  -->
                            <div class="col-lg-12">
                                <div class="car-images">
                                    <h3 class="car-images-taitel">{{ __('translate.Key Information') }}</h3>

                                    <input type="hidden" name="condition" value="Used">

                                    <div class="car-images-inner">
                                        <div class="description-item">

                                            <div class="description-item-inner">
                                                <label for="exampleFormControlInput1" class="form-label">
                                                    {{ __('translate.Seller Type') }}
                                                    <span>*</span> </label>
                                                <select class="form-select"  name="seller_type">
                                                    <option {{ 'Dealer' == old('seller_type') ? 'selected' : '' }}  value="Dealer">{{ __('translate.Dealer') }}</option>
                                                    <option {{ 'Personal' == old('seller_type') ? 'selected' : '' }} value="Personal">{{ __('translate.Indivisual') }}</option>
                                                </select>
                                            </div>

                                            <div class="description-item-inner">
                                                <label for="body_type" class="form-label">{{ __('translate.Body Type') }}
                                                    <span>*</span> </label>
                                                <input type="text" class="form-control" id="body_type"
                                                    placeholder="{{ __('translate.Body Type') }}" name="body_type" value="{{ old('body_type') }}">
                                            </div>

                                            <div class="description-item-inner">
                                                <label for="engine_size" class="form-label">{{ __('translate.Engine Size') }}
                                                    <span>*</span> </label>
                                                <input type="text" class="form-control"
                                                    placeholder="{{ __('translate.Engine Size') }}" name="engine_size" id="engine_size" value="{{ old('engine_size') }}">
                                            </div>

                                            <div class="description-item-inner">
                                                <label for="drive" class="form-label">{{ __('translate.Drive') }}
                                                    <span>*</span> </label>
                                                <input type="text" class="form-control"
                                                    placeholder="{{ __('translate.Drive') }}" name="drive" id="drive" value="{{ old('drive') }}">
                                            </div>


                                        </div>


                                        <div class="description-item two">
                                            <div class="description-item-inner">
                                                <label for="interior_color" class="form-label">{{ __('translate.Interior Color') }}
                                                    <span>*</span> </label>
                                                <input type="text" class="form-control"
                                                    placeholder="{{ __('translate.Interior Color') }}" name="interior_color" id="interior_color" value="{{ old('interior_color') }}">
                                            </div>


                                            <div class="description-item-inner">
                                                <label for="exterior_color" class="form-label">{{ __('translate.Exterior Color') }}
                                                    <span>*</span> </label>
                                                <input type="text" class="form-control"
                                                    placeholder="{{ __('translate.Exterior Color') }}" name="exterior_color" id="exterior_color" value="{{ old('exterior_color') }}">
                                            </div>

                                            <div class="description-item-inner">
                                                <label for="year" class="form-label">{{ __('translate.Year') }}
                                                    <span>*</span> </label>
                                                    <input class="form-control" type="text" name="year" id="year" value="{{ old('year') }}" placeholder="{{ __('translate.Year') }}">
                                            </div>

                                            <div class="description-item-inner">
                                                <label for="mileage" class="form-label">{{ __('translate.Mileage') }}
                                                    <span>*</span> </label>
                                                    <input class="form-control" type="text" name="mileage" id="mileage" value="{{ old('mileage') }}" placeholder="{{ __('translate.Mileage') }}">
                                            </div>

                                        </div>

                                        <div class="description-item two">

                                            <div class="description-item-inner">
                                                <label for="number_of_owner" class="form-label">{{ __('translate.Number of Owner') }}
                                                    <span>*</span> </label>
                                                <input type="text" class="form-control"
                                                    placeholder="{{ __('translate.Number of Owner') }}" name="number_of_owner" id="number_of_owner" value="{{ old('number_of_owner') }}">
                                            </div>

                                            <div class="description-item-inner">
                                                <label for="fuel_type" class="form-label">{{ __('translate.Fuel Type') }}
                                                    <span>*</span> </label>
                                                <input type="text" class="form-control"
                                                    placeholder="{{ __('translate.Fuel Type') }}" name="fuel_type" id="fuel_type" value="{{ old('fuel_type') }}">
                                            </div>

                                            <div class="description-item-inner">
                                                <label for="transmission" class="form-label">{{ __('translate.Transmission') }}
                                                    <span>*</span> </label>
                                                <input type="text" class="form-control"
                                                    placeholder="{{ __('translate.Transmission') }}" name="transmission" id="transmission" value="{{ old('transmission') }}">
                                            </div>

                                            <div class="description-item-inner">
                                                <label for="car_model" class="form-label">{{ __('translate.Car Model') }}
                                                    <span>*</span> </label>
                                                <input type="text" class="form-control"
                                                    placeholder="{{ __('translate.Car Model') }}" name="car_model" id="car_model" value="{{ old('car_model') }}">
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Features  -->
                            <div class="col-lg-12">
                                <div class="car-images">
                                    <h3 class="car-images-taitel">{{ __('translate.Features') }}</h3>
                                    <div class="car-images-inner">
                                        <div class="description-item two">
                                            <div class="description-item-inner">
                                                <div class="description-feature-item">
                                                    @foreach ($features as $index => $feature)
                                                        <div class="description-feature-inner">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" name="features[]" value="{{ $feature->id }}"
                                                                    id="flexCheckDefault{{ $index }}">
                                                                <label class="form-check-label" for="flexCheckDefault{{ $index }}">
                                                                    {{ $feature->translate->name }}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    @endforeach

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <!-- Video  -->
                            <div class="col-lg-12">
                                <div class="car-images">
                                    <h3 class="car-images-taitel">{{ __('translate.Video Information') }}</h3>
                                    <div class="car-images-inner">
                                        <h6 class="car-images-inner-txt">{{ __('translate.Video Image') }}

                                              <i 
                                                    class="fas fa-info-circle text-info"
                                                    data-toggle="tooltip"
                                                    data-placement="right"
                                                    title="Recommended size: 874x398"
                                                    style="cursor: pointer;"
                                                ></i>
                                        </h6>

                                        <div class="row">
                                            <div class="col-xl-3 col-lg-4">

                                        <div class="car-images-inner-item two">
                                            <div class="car-images-inner-item-thumb">
                                                <img src="{{ getImageOrPlaceholder($setting->placeholder_image, '874x398') }}" id="view_video_image">
                                            </div>

                                            <div class="choose-file-txt">
                                                <h6>{{ __('translate.New') }} <span>{{ __('translate.Choose File') }}</span> {{ __('translate.Upload') }}</h6>
                                                <input type="file" id="my-file-one" onchange="previewVideoImage(event)" name="video_image">
                                            </div>



                                        </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="car-images-inner">
                                        <div class="description-item">
                                            <div class="description-item-inner">
                                                <label for="video_id" class="form-label">{{ __('translate.Youtube Video Id') }} </label>
                                                <input type="text" class="form-control"
                                                    placeholder="{{ __('translate.Youtube Video Id') }}" name="video_id" id="video_id" value="{{ old('video_id') }}">
                                            </div>

                                        </div>
                                        <div class="description-item two">
                                            <div class="description-item-inner">
                                                <div class="description-item-inner">
                                                    <label for="video_description" class="form-label">{{ __('translate.Description') }} </label>
                                                    <textarea class="form-control" id="video_description"
                                                        rows="5" placeholder="{{ __('translate.Description') }}" name="video_description">{{ old('video_description') }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <!-- Locations  -->
                            <div class="col-lg-12">
                                <div class="car-images">
                                    <h3 class="car-images-taitel">{{ __('translate.Address & Google Map') }}</h3>

                                    <div class="car-images-inner">
                                        <div class="description-item">

                                            <div class="description-item-inner">
                                                <label for="address" class="form-label">{{ __('translate.Address') }} <span>*</span></label>
                                                <input type="text" class="form-control"
                                                    placeholder="{{ __('translate.Type your address') }}" name="address" id="address" value="{{ old('address') }}">
                                            </div>


                                        </div>
                                        <div class="description-item two">
                                            <div class="description-item-inner">
                                                <label for="google_map" class="form-label">{{ __('translate.Google Map Embed Link') }} <span>*</span></label>
                                                <textarea class="form-control" id="exampleFormControlTextarea121"
                                                    rows="10" placeholder="{{ __('translate.Past google embed code') }}" name="google_map" id="google_map">{{ old('google_map') }}</textarea>
                                            </div>


                                        </div>

                                    </div>
                                </div>
                            </div>

                            <!-- Locations  -->
                            <div class="col-lg-12">
                                <div class="car-images">
                                    <h3 class="car-images-taitel">{{ __('translate.SEO Information') }}</h3>

                                    <div class="car-images-inner">
                                        <div class="description-item">
                                            <div class="description-item-inner">
                                                <label for="seo_title" class="form-label">{{ __('translate.SEO Title') }}</label>
                                                <input type="text" class="form-control" id="seo_title"
                                                    placeholder="{{ __('translate.SEO Title') }}" name="seo_title" value="{{ old('seo_title') }}">
                                            </div>
                                        </div>
                                        <div class="description-item two">
                                            <div class="description-item-inner">
                                                <label class="form-label" for="seo_description">{{ __('translate.SEO Description') }}</label>
                                                <textarea class="form-control" id="seo_description"
                                                    rows="4" placeholder="{{ __('translate.SEO Description') }}" name="seo_description">{{ old('seo_description') }}</textarea>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <!-- button  -->
                            <div class="col-lg-12">
                                <div class="description-form-btn" >
                                <button class="thm-btn-two">{{ __('translate.Save Now') }}</button>
                                </div>

                            </div>
                        </div>

                    </form>
                </div>

            </div>
        </div>
        </div>
    </section>

    <!-- dashboard-part-end -->

    @include('profile.logout')



</main>

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
                var output = document.getElementById('thumb_image');
                output.src = reader.result;
            }

            reader.readAsDataURL(event.target.files[0]);
        };

        function previewVideoImage(event) {
            var reader = new FileReader();
            reader.onload = function(){
                var output = document.getElementById('view_video_image');
                output.src = reader.result;
            }

            reader.readAsDataURL(event.target.files[0]);
        };


    </script>
@endpush

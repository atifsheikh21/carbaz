@extends('layout')
@section('title')
    <title>{{ __('translate.Edit Sale Car') }}</title>
@endsection
@section('body-content')

<main>
    <!-- banner-part-start  -->

    <section class="inner-banner">
    <div class="inner-banner-img" style=" background-image: url({{ getImageOrPlaceholder($breadcrumb,'1905x300') }}) "></div>
        <div class="container">
        <div class="col-lg-12">
            <div class="inner-banner-df">
                <h1 class="inner-banner-taitel">{{ __('translate.Edit Sale Car') }}</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('translate.Home') }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('translate.Edit Sale Car') }}</li>
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
                    <form action="{{ route('user.car.update', $car->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <input type="hidden" name="agent_id" value="{{ Auth::guard('web')->user()->id }}">

                        <input type="hidden" name="purpose" value="{{ $car->purpose }}">
                        <input type="hidden" name="lang_code" value="{{ $car_translate->lang_code }}">
                        <input type="hidden" name="translate_id" value="{{ $car_translate->id }}">

                        <div class="row gy-5">

                            <div class="col-lg-12">
                                <div class="car-images">
                                    <h3 class="car-images-taitel">{{ __('translate.Switch to language translation') }}</h3>
                                    <div class="car-images-inner">
                                        <div class="edit-car-item">
                                            @foreach ($language_list as $language)
                                            <a href="{{ route('user.car.edit', ['car' => $car->id, 'lang_code' => $language->lang_code] ) }}" class="edit-car-btn">
                                                <span>
                                                    <svg width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">

                                                        <g mask="url(#mask0_1002_215026)">
                                                            <path d="M13.9277 5.23438H3.18555C2.10684 5.23438 1.23242 6.10879 1.23242 7.1875V22.8125C1.23242 23.8912 2.10684 24.7656 3.18555 24.7656H18.8105C19.8893 24.7656 20.7637 23.8912 20.7637 22.8125V12.0703" stroke-width="1.4" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
                                                            <path d="M24.4778 4.27852L13.4292 15.327L9.97656 16.0176L10.6671 12.5649L21.7156 1.51636C22.097 1.13501 22.7153 1.13501 23.0967 1.51636L24.4778 2.89746C24.8591 3.27881 24.8591 3.89712 24.4778 4.27852Z" stroke-width="1.4" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
                                                            <path d="M20.3379 2.89902L23.1 5.66113" stroke-width="1.4" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
                                                        </g>
                                                    </svg>

                                                </span>

                                                {{ $language->lang_name }}
                                            </a>
                                            @endforeach

                                        </div>

                                        <div class="edit-car-inner">
                                            @php
                                                $edited_language = $language_list->where('lang_code', request()->get('lang_code'))->first();
                                            @endphp
                                            <p class="edit-car-inner-p">{{ __('translate.Your editing mood') }}: <span>{{ $edited_language->lang_name }}</span></p>
                                        </div>

                                    </div>

                                </div>
                            </div>


                            <!-- Car Images  -->

                            @if (admin_lang() == request()->get('lang_code'))
                            <div class="col-lg-12">
                                <div class="car-images">
                                    <h3 class="car-images-taitel">{{ __('translate.Thumbnail Image') }}</h3>
                                    <div class="car-images-inner">
                                        <h6 class="car-images-inner-txt">{{ __('translate.Upload New Image') }}
                                              <i 
                                                class="fas fa-info-circle text-info"
                                                data-toggle="tooltip"
                                                data-placement="right"
                                                title="Recommended size: 329x203"
                                                style="cursor: pointer;"
                                            ></i>
                                        </h6>

                                        <div class="row">
                                            <div class=" col-xl-3 col-lg-4">
                                                <div class="car-images-inner-item two">
                                                    <div class="car-images-inner-item-thumb">
                                                        <img src="{{ getImageOrPlaceholder($car->thumb_image, '329x203') }}" id="thumb_image">
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
                            @endif


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
                                                    placeholder="{{ __('translate.Title') }}" name="title" value="{{ html_decode($car_translate->title) }}">
                                            </div>


                                        </div>

                                        @if (admin_lang() == request()->get('lang_code'))
                                        <div class="description-item two">

                                            <div class="description-item-inner">
                                                <label for="slug" class="form-label">{{ __('translate.Slug') }}
                                                    <span>*</span> </label>
                                                <input type="text" class="form-control" id="slug"
                                                    placeholder="{{ __('translate.Slug') }}" name="slug" value="{{ html_decode($car->slug) }}">
                                            </div>

                                            <div class="description-item-inner">
                                                <label for="brand" class="form-label">{{ __('translate.Brand') }}
                                                    <span>*</span> </label>
                                                <select class="form-select select2" name="brand_id">
                                                    <option value="">{{ __('translate.Select Brand') }}</option>
                                                    @foreach ($brands as $brand)
                                                        <option  {{ $brand->id == $car->brand_id ? 'selected' : '' }} value="{{ $brand->id }}">{{ $brand->translate->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                        </div>

                                        <div class="description-item two">


                                            <div class="description-item-inner">
                                                <label for="country" class="form-label">{{ __('translate.Country') }}
                                                    <span>*</span> </label>
                                                <select class="form-select select2" name="country_id" id="country_id">
                                                    <option value="">{{ __('translate.Select Country') }}</option>
                                                    @foreach ($countries as $country)
                                                        <option {{ $country->id == $car->country_id ? 'selected' : '' }} value="{{ $country->id }}">{{ $country->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>


                                            <div class="description-item-inner">
                                                <label for="city" class="form-label">{{ __('translate.City') }}
                                                    <span>*</span> </label>
                                                <select class="form-select select2" name="city_id" id="city_id">
                                                    <option value="">{{ __('translate.Select City') }}</option>
                                                    @foreach ($cities as $city)
                                                        <option {{ $city->id == $car->city_id ? 'selected' : '' }} value="{{ $city->id }}">{{ $city->translate->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                        </div>
                                        <div class="description-item two">

                                            <div class="description-item-inner">
                                                <label for="regular_price" class="form-label">{{ __('translate.Regular Price') }}
                                                    <span>*</span> </label>
                                                <input type="text" class="form-control" placeholder="{{ __('translate.Regular Price') }}"  name="regular_price" value="{{ html_decode($car->regular_price) }}">
                                            </div>

                                            <div class="description-item-inner">
                                                <label for="offer_price" class="form-label">{{ __('translate.Offer Price') }}
                                                </label>
                                                <input type="text" class="form-control" placeholder="{{ __('translate.Offer Price') }}" name="offer_price" value="{{ html_decode($car->offer_price )}}">
                                            </div>

                                        </div>

                                        @endif

                                        <div class="description-item two">

                                            <div class="description-item-inner">
                                                <label for="offer_price" class="form-label">{{ __('translate.Description') }}
                                                    <span>*</span>
                                                </label>
                                                <textarea class="summernote"  name="description" id="description">{!! html_decode($car_translate->description) !!}</textarea>
                                            </div>

                                        </div>


                                    </div>
                                </div>
                            </div>



                            <!-- Key Information  -->
                            @if (admin_lang() == request()->get('lang_code'))
                            <div class="col-lg-12">
                                <div class="car-images">
                                    <h3 class="car-images-taitel">{{ __('translate.Key Information') }}</h3>

                                    <input type="hidden" name="condition" value="Used">

                                    <div class="car-images-inner">
                                        <div class="description-item">

                                            <div class="description-item-inner">
                                                <label for="exampleFormControlInput1" class="form-label">
                                                    {{ __('translate.Condition') }}
                                                    <span>*</span> </label>
                                                <select class="form-select"  name="condition">
                                                    <option {{ 'Used' == $car->condition ? 'selected' : '' }} value="Used">{{ __('translate.Used') }}</option>
                                                    <option {{ 'New' == $car->condition ? 'selected' : '' }} value="New">{{ __('translate.New') }}</option>
                                                </select>
                                            </div>


                                            <div class="description-item-inner">
                                                <label for="exampleFormControlInput1" class="form-label">
                                                    {{ __('translate.Seller Type') }}
                                                    <span>*</span> </label>
                                                <select class="form-select"  name="seller_type">
                                                    <option {{ 'Dealer' == $car->seller_type ? 'selected' : '' }}  value="Dealer">{{ __('translate.Dealer') }}</option>
                                                    <option {{ 'Personal' == $car->seller_type ? 'selected' : '' }} value="Personal">{{ __('translate.Indivisual') }}</option>
                                                </select>
                                            </div>

                                            <div class="description-item-inner">
                                                <label for="body_type" class="form-label">{{ __('translate.Body Type') }}
                                                    <span>*</span> </label>
                                                <input type="text" class="form-control" id="body_type"
                                                    placeholder="{{ __('translate.Body Type') }}" name="body_type" value="{{ html_decode($car->body_type) }}">
                                            </div>

                                            <div class="description-item-inner">
                                                <label for="engine_size" class="form-label">{{ __('translate.Engine Size') }}
                                                    <span>*</span> </label>
                                                <input type="text" class="form-control"
                                                    placeholder="{{ __('translate.Engine Size') }}" name="engine_size" id="engine_size" value="{{ html_decode($car->engine_size) }}">
                                            </div>

                                        </div>


                                        <div class="description-item two">
                                            <div class="description-item-inner">
                                                <label for="interior_color" class="form-label">{{ __('translate.Interior Color') }}
                                                    <span>*</span> </label>
                                                <input type="text" class="form-control"
                                                    placeholder="{{ __('translate.Interior Color') }}" name="interior_color" id="interior_color" value="{{ html_decode($car->interior_color) }}">
                                            </div>


                                            <div class="description-item-inner">
                                                <label for="exterior_color" class="form-label">{{ __('translate.Exterior Color') }}
                                                    <span>*</span> </label>
                                                <input type="text" class="form-control"
                                                    placeholder="{{ __('translate.Exterior Color') }}" name="exterior_color" id="exterior_color" value="{{ html_decode($car->exterior_color) }}">
                                            </div>

                                            <div class="description-item-inner">
                                                <label for="year" class="form-label">{{ __('translate.Year') }}
                                                    <span>*</span> </label>
                                                    <input class="form-control" type="text" name="year" id="year" value="{{ html_decode($car->year) }}" placeholder="{{ __('translate.Year') }}">
                                            </div>

                                            <div class="description-item-inner">
                                                <label for="mileage" class="form-label">{{ __('translate.Mileage') }}
                                                    <span>*</span> </label>
                                                    <input class="form-control" type="text" name="mileage" id="mileage" value="{{ html_decode($car->mileage) }}" placeholder="{{ __('translate.Mileage') }}">
                                            </div>

                                        </div>

                                        <div class="description-item two">

                                            <div class="description-item-inner">
                                                <label for="number_of_owner" class="form-label">{{ __('translate.Number of Owner') }}
                                                    <span>*</span> </label>
                                                <input type="text" class="form-control"
                                                    placeholder="{{ __('translate.Number of Owner') }}" name="number_of_owner" id="number_of_owner" value="{{ html_decode($car->number_of_owner) }}">
                                            </div>

                                            <div class="description-item-inner">
                                                <label for="fuel_type" class="form-label">{{ __('translate.Fuel Type') }}
                                                    <span>*</span> </label>
                                                <input type="text" class="form-control"
                                                    placeholder="{{ __('translate.Fuel Type') }}" name="fuel_type" id="fuel_type" value="{{ html_decode($car->fuel_type) }}">
                                            </div>

                                            <div class="description-item-inner">
                                                <label for="transmission" class="form-label">{{ __('translate.Transmission') }}
                                                    <span>*</span> </label>
                                                <input type="text" class="form-control"
                                                    placeholder="{{ __('translate.Transmission') }}" name="transmission" id="transmission" value="{{ html_decode($car->transmission) }}">
                                            </div>

                                            <div class="description-item-inner">
                                                <label for="drive" class="form-label">{{ __('translate.Drive') }}
                                                    <span>*</span> </label>
                                                <input type="text" class="form-control"
                                                    placeholder="{{ __('translate.Drive') }}" name="drive" id="drive" value="{{ html_decode($car->drive) }}">
                                            </div>

                                            <div class="col-md-3">
                                                <div class="crancy__item-form--group mg-top-form-20">
                                                    <label class="crancy__item-label">{{ __('translate.Car Model') }} * </label>
                                                    <input class="crancy__item-input" type="text" name="car_model" id="car_model" value="{{ html_decode($car->car_model) }}">
                                                </div>
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
                                                                <input {{ in_array($feature->id, $existing_features) ? 'checked' : '' }}  class="form-check-input" type="checkbox" name="features[]" value="{{ $feature->id }}"
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

                            @endif


                            <!-- Video  -->
                            <div class="col-lg-12">
                                <div class="car-images">
                                    <h3 class="car-images-taitel">{{ __('translate.Video Information') }}</h3>

                                    @if (admin_lang() == request()->get('lang_code'))
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
                                            <div class=" col-xl-3 col-lg-4  ">
                                                <div class="car-images-inner-item two">
                                                    <div class="car-images-inner-item-thumb">
                                                        <img src="{{ getImageOrPlaceholder($car->video_image, '874x398') }}" id="view_video_image" >
                                                    </div>

                                                    <div class="choose-file-txt">
                                                        <h6>{{ __('translate.New') }} <span>{{ __('translate.Choose File') }}</span> {{ __('translate.Upload') }}</h6>
                                                        <input type="file" id="my-file-one" onchange="previewVideoImage(event)" name="video_image">
                                                    </div>



                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    @endif

                                    <div class="car-images-inner">
                                        @if (admin_lang() == request()->get('lang_code'))
                                        <div class="description-item">
                                            <div class="description-item-inner">
                                                <label for="video_id" class="form-label">{{ __('translate.Youtube Video Id') }} </label>
                                                <input type="text" class="form-control"
                                                    placeholder="{{ __('translate.Youtube Video Id') }}" name="video_id" id="video_id" value="{{ html_decode($car->video_id) }}">
                                            </div>

                                        </div>
                                        @endif
                                        <div class="description-item two">
                                            <div class="description-item-inner">
                                                <div class="description-item-inner">
                                                    <label for="video_description" class="form-label">{{ __('translate.Description') }} </label>
                                                    <textarea class="form-control" id="video_description"
                                                        rows="5" placeholder="{{ __('translate.Description') }}" name="video_description">{{ html_decode($car_translate->video_description) }}</textarea>
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
                                                    placeholder="{{ __('translate.Type your address') }}" name="address" id="address" value="{{ html_decode($car_translate->address) }}">
                                            </div>


                                        </div>

                                        @if (admin_lang() == request()->get('lang_code'))
                                        <div class="description-item two">
                                            <div class="description-item-inner">
                                                <label for="google_map" class="form-label">{{ __('translate.Google Map Embed Link') }} <span>*</span></label>
                                                <textarea class="form-control" id="exampleFormControlTextarea121"
                                                    rows="10" placeholder="{{ __('translate.Past google embed code') }}" name="google_map" id="google_map">{{ html_decode($car->google_map) }}</textarea>
                                            </div>
                                        </div>
                                        @endif

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
                                                    placeholder="{{ __('translate.SEO Title') }}" name="seo_title" value="{{ html_decode($car_translate->seo_title) }}">
                                            </div>
                                        </div>
                                        <div class="description-item two">
                                            <div class="description-item-inner">
                                                <label class="form-label" for="seo_description">{{ __('translate.SEO Description') }}</label>
                                                <textarea class="form-control" id="seo_description"
                                                    rows="4" placeholder="{{ __('translate.SEO Description') }}" name="seo_description">{{ html_decode($car_translate->seo_description) }}</textarea>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>


                            <!-- button  -->
                            <div class="col-lg-12">
                                <div class="description-form-btn" >
                                    <button class="thm-btn-two">{{ __('translate.Update Now') }}</button>
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

@extends('layout')
@section('title')
    <title>{{ __('translate.Create Sale Car') }}</title>
@endsection
@section('body-content')

<main>
    <!-- banner-part-start  -->

    <section class="inner-banner">
        <div class="inner-banner-img" style=" background-image: url({{ getImageOrPlaceholder($breadcrumb,'1905x300') }}) "></div>
        <div class="container">
        <div class="col-lg-12">
            <div class="inner-banner-df">
                <h1 class="inner-banner-taitel">{{ __('translate.Create Sale Car') }}</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('translate.Home') }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('translate.Create Sale Car') }}</li>
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
                                            ></i>
                                        </h6>

                                        <div class="row">
                                            <div class="col-xl-3 col-lg-4">
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
                                                <label for="registration_number" class="form-label">{{ __('translate.Registration Number') }}</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" id="registration_number" name="registration_number" placeholder="{{ __('translate.Registration Number') }}">
                                                    <button type="button" class="btn btn-primary" id="btn_motorcheck_lookup">{{ __('translate.Fetch Details') }}</button>
                                                </div>
                                            </div>

                                            <div class="description-item-inner">
                                                <label for="title" class="form-label">{{ __('translate.Title') }}
                                                    <span>*</span> </label>
                                                <input type="text" class="form-control" id="title"
                                                    placeholder="{{ __('translate.Title') }}" name="title" value="{{ old('title') }}">
                                            </div>


                                        </div>
                                        <div class="description-item two">

                                            <div class="description-item-inner">
                                                <label for="slug" class="form-label">{{ __('translate.Slug') }}
                                                    <span>*</span> </label>
                                                <input type="text" class="form-control" id="slug"
                                                    placeholder="{{ __('translate.Slug') }}" name="slug" value="{{ old('slug') }}">
                                            </div>

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

                                        </div>
                                        <div class="description-item two">

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


                                            <div class="description-item-inner">
                                                <label for="city" class="form-label">{{ __('translate.City') }}
                                                    <span>*</span> </label>
                                                <select class="form-select select2" name="city_id" id="city_id">
                                                    <option value="">{{ __('translate.Select City') }}</option>

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

                                        <input type="hidden" name="motorcheck_reg" id="motorcheck_reg" value="{{ old('motorcheck_reg') }}">
                                        <input type="hidden" name="motorcheck_make" id="motorcheck_make" value="{{ old('motorcheck_make') }}">
                                        <input type="hidden" name="motorcheck_model" id="motorcheck_model" value="{{ old('motorcheck_model') }}">
                                        <input type="hidden" name="motorcheck_version" id="motorcheck_version" value="{{ old('motorcheck_version') }}">
                                        <input type="hidden" name="motorcheck_body" id="motorcheck_body" value="{{ old('motorcheck_body') }}">
                                        <input type="hidden" name="motorcheck_doors" id="motorcheck_doors" value="{{ old('motorcheck_doors') }}">
                                        <input type="hidden" name="motorcheck_reg_date" id="motorcheck_reg_date" value="{{ old('motorcheck_reg_date') }}">
                                        <input type="hidden" name="motorcheck_engine_cc" id="motorcheck_engine_cc" value="{{ old('motorcheck_engine_cc') }}">
                                        <input type="hidden" name="motorcheck_colour" id="motorcheck_colour" value="{{ old('motorcheck_colour') }}">
                                        <input type="hidden" name="motorcheck_fuel" id="motorcheck_fuel" value="{{ old('motorcheck_fuel') }}">
                                        <input type="hidden" name="motorcheck_transmission" id="motorcheck_transmission" value="{{ old('motorcheck_transmission') }}">
                                        <input type="hidden" name="motorcheck_no_of_owners" id="motorcheck_no_of_owners" value="{{ old('motorcheck_no_of_owners') }}">
                                        <input type="hidden" name="motorcheck_tax_class" id="motorcheck_tax_class" value="{{ old('motorcheck_tax_class') }}">
                                        <input type="hidden" name="motorcheck_tax_expiry_date" id="motorcheck_tax_expiry_date" value="{{ old('motorcheck_tax_expiry_date') }}">
                                        <input type="hidden" name="motorcheck_nct_expiry_date" id="motorcheck_nct_expiry_date" value="{{ old('motorcheck_nct_expiry_date') }}">
                                        <input type="hidden" name="motorcheck_co2_emissions" id="motorcheck_co2_emissions" value="{{ old('motorcheck_co2_emissions') }}">
                                        <input type="hidden" name="motorcheck_last_date_of_sale" id="motorcheck_last_date_of_sale" value="{{ old('motorcheck_last_date_of_sale') }}">
                                        <input type="hidden" name="motorcheck_raw" id="motorcheck_raw" value="{{ old('motorcheck_raw') }}">

                                        <div class="description-item">
                                            <div class="description-item-inner">
                                                <label for="motorcheck_version_view" class="form-label">{{ __('translate.Version') }}</label>
                                                <input type="text" class="form-control" id="motorcheck_version_view" placeholder="{{ __('translate.Version') }}" value="{{ old('motorcheck_version') }}" readonly>
                                            </div>
                                        </div>

                                        <div class="description-item two">
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
                                                <label for="drive" class="form-label">{{ __('translate.Drive') }}
                                                    <span>*</span> </label>
                                                <input type="text" class="form-control"
                                                    placeholder="{{ __('translate.Drive') }}" name="drive" id="drive" value="{{ old('drive') }}">
                                            </div>

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

                                        <div class="description-item two">
                                            <div class="description-item-inner">
                                                <label for="motorcheck_doors_view" class="form-label">{{ __('translate.Doors') }}</label>
                                                <input type="text" class="form-control" id="motorcheck_doors_view" value="{{ old('motorcheck_doors') }}" readonly>
                                            </div>

                                            <div class="description-item-inner">
                                                <label for="motorcheck_tax_class_view" class="form-label">{{ __('translate.Tax Class') }}</label>
                                                <input type="text" class="form-control" id="motorcheck_tax_class_view" value="{{ old('motorcheck_tax_class') }}" readonly>
                                            </div>

                                            <div class="description-item-inner">
                                                <label for="motorcheck_tax_expiry_date_view" class="form-label">{{ __('translate.Tax Expiry Date') }}</label>
                                                <input type="text" class="form-control" id="motorcheck_tax_expiry_date_view" value="{{ old('motorcheck_tax_expiry_date') }}" readonly>
                                            </div>

                                            <div class="description-item-inner">
                                                <label for="motorcheck_nct_expiry_date_view" class="form-label">{{ __('translate.NCT Expiry Date') }}</label>
                                                <input type="text" class="form-control" id="motorcheck_nct_expiry_date_view" value="{{ old('motorcheck_nct_expiry_date') }}" readonly>
                                            </div>

                                            <div class="description-item-inner">
                                                <label for="motorcheck_co2_emissions_view" class="form-label">{{ __('translate.CO2 Emissions') }}</label>
                                                <input type="text" class="form-control" id="motorcheck_co2_emissions_view" value="{{ old('motorcheck_co2_emissions') }}" readonly>
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
                                                    {{ __('translate.Condition') }}
                                                    <span>*</span> </label>
                                                <select class="form-select"  name="condition">
                                                    <option {{ 'Used' == old('condition') ? 'selected' : '' }} value="Used">{{ __('translate.Used') }}</option>
                                                    <option {{ 'New' == old('condition') ? 'selected' : '' }} value="New">{{ __('translate.New') }}</option>
                                                </select>
                                            </div>

                                            <div class="description-item-inner">
                                                <label for="exampleFormControlInput1" class="form-label">
                                                    {{ __('translate.Seller Type') }}
                                                    <span>*</span> </label>
                                                <select class="form-select"  name="seller_type">
                                                    <option {{ 'Dealer' == old('seller_type') ? 'selected' : '' }}  value="Dealer">{{ __('translate.Dealer') }}</option>
                                                    <option {{ 'Personal' == old('seller_type') ? 'selected' : '' }} value="Personal">{{ __('translate.Indivisual') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="car-images">
                                    <h3 class="car-images-taitel">{{ __('translate.Gallery Images') }}</h3>
                                    <div class="car-images-inner">
                                        <h6 class="car-images-inner-txt">{{ __('translate.Upload New Image') }}</h6>
                                        <div class="row">
                                            <div class="col-xl-6 col-lg-8">
                                                <div class="choose-file-txt">
                                                    <h6>{{ __('translate.New') }} <span>{{ __('translate.Choose File') }}</span> {{ __('translate.Upload') }}</h6>
                                                    <input type="file" name="gallery_images[]" multiple accept="image/*">
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

                $("#btn_motorcheck_lookup").on("click", function() {
                    let reg = $("#registration_number").val();

                    if (!reg) {
                        toastr.error("{{ __('translate.Please fill out the form') }}");
                        return;
                    }

                    $.ajax({
                        type: "post",
                        url: "{{ route('user.car.motorcheck.lookup') }}",
                        data: {
                            _token: "{{ csrf_token() }}",
                            registration_number: reg,
                        },
                        success: function(res) {
                            if (!res || (!res.mapped && !res.raw)) {
                                toastr.error("MotorCheck response is empty");
                                return;
                            }

                            let mapped = res.mapped || {};
                            let vehicle = res.vehicle || res.raw?.vehicle || {};

                            function normalizeMake(make) {
                                if (!make) return make;
                                let val = String(make).trim();
                                if (!val) return val;
                                if (!val.includes(' ') && val.length <= 4) {
                                    return val.toUpperCase();
                                }
                                return val.split(' ').map(function(w) {
                                    w = w.trim();
                                    if (!w) return w;
                                    return w.charAt(0).toUpperCase() + w.slice(1).toLowerCase();
                                }).join(' ');
                            }

                            function normalizeTransmission(trans) {
                                if (!trans) return trans;
                                let t = String(trans).trim().toUpperCase();
                                if (t === 'A') return 'Automatic';
                                if (t === 'M') return 'Manual';
                                return trans;
                            }

                            console.log('MotorCheck response:', res);
                            console.log('MotorCheck raw:', res.raw);
                            console.log('MotorCheck vehicle:', vehicle);
                            console.log('MotorCheck mapped:', mapped);

                            if (mapped.year) $("#year").val(mapped.year);
                            if (mapped.fuel_type) $("#fuel_type").val(mapped.fuel_type);
                            if (mapped.transmission) $("#transmission").val(normalizeTransmission(mapped.transmission));
                            if (mapped.engine_size) $("#engine_size").val(mapped.engine_size);
                            if (mapped.model) $("#car_model").val(mapped.model);
                            if (mapped.body_type) $("#body_type").val(mapped.body_type);
                            if (mapped.exterior_color) $("#exterior_color").val(mapped.exterior_color);
                            if (mapped.number_of_owner) $("#number_of_owner").val(mapped.number_of_owner);

                            if (mapped.motorcheck_reg) $("#motorcheck_reg").val(mapped.motorcheck_reg);
                            if (mapped.motorcheck_make) $("#motorcheck_make").val(mapped.motorcheck_make);
                            if (mapped.motorcheck_model) $("#motorcheck_model").val(mapped.motorcheck_model);
                            if (mapped.motorcheck_version) {
                                $("#motorcheck_version").val(mapped.motorcheck_version);
                                $("#motorcheck_version_view").val(mapped.motorcheck_version);
                            }
                            if (mapped.motorcheck_body) $("#motorcheck_body").val(mapped.motorcheck_body);
                            if (mapped.motorcheck_doors !== undefined && mapped.motorcheck_doors !== null) {
                                $("#motorcheck_doors").val(mapped.motorcheck_doors);
                                $("#motorcheck_doors_view").val(mapped.motorcheck_doors);
                            }
                            if (mapped.motorcheck_reg_date) $("#motorcheck_reg_date").val(mapped.motorcheck_reg_date);
                            if (mapped.motorcheck_engine_cc !== undefined && mapped.motorcheck_engine_cc !== null) $("#motorcheck_engine_cc").val(mapped.motorcheck_engine_cc);
                            if (mapped.motorcheck_colour) $("#motorcheck_colour").val(mapped.motorcheck_colour);
                            if (mapped.motorcheck_fuel) $("#motorcheck_fuel").val(mapped.motorcheck_fuel);
                            if (mapped.motorcheck_transmission) $("#motorcheck_transmission").val(mapped.motorcheck_transmission);
                            if (mapped.motorcheck_no_of_owners !== undefined && mapped.motorcheck_no_of_owners !== null) $("#motorcheck_no_of_owners").val(mapped.motorcheck_no_of_owners);
                            if (mapped.motorcheck_tax_class) {
                                $("#motorcheck_tax_class").val(mapped.motorcheck_tax_class);
                                $("#motorcheck_tax_class_view").val(mapped.motorcheck_tax_class);
                            }
                            if (mapped.motorcheck_tax_expiry_date) {
                                $("#motorcheck_tax_expiry_date").val(mapped.motorcheck_tax_expiry_date);
                                $("#motorcheck_tax_expiry_date_view").val(mapped.motorcheck_tax_expiry_date);
                            }
                            if (mapped.motorcheck_nct_expiry_date) {
                                $("#motorcheck_nct_expiry_date").val(mapped.motorcheck_nct_expiry_date);
                                $("#motorcheck_nct_expiry_date_view").val(mapped.motorcheck_nct_expiry_date);
                            }
                            if (mapped.motorcheck_co2_emissions !== undefined && mapped.motorcheck_co2_emissions !== null) {
                                $("#motorcheck_co2_emissions").val(mapped.motorcheck_co2_emissions);
                                $("#motorcheck_co2_emissions_view").val(mapped.motorcheck_co2_emissions);
                            }
                            if (mapped.motorcheck_last_date_of_sale) $("#motorcheck_last_date_of_sale").val(mapped.motorcheck_last_date_of_sale);

                            try {
                                $("#motorcheck_raw").val(JSON.stringify(res.raw || res));
                            } catch (e) {
                                // ignore
                            }

                            if (mapped.make) {
                                let makeLower = String(mapped.make).toLowerCase().trim();
                                let $brandSelect = $("select[name='brand_id']");

                                $brandSelect.find('option').each(function() {
                                    let txt = $(this).text().toLowerCase().trim();
                                    if (txt === makeLower) {
                                        $brandSelect.val($(this).val()).trigger('change');
                                        return false;
                                    }
                                });
                            }

                            let titleParts = [];
                            if (vehicle.make) titleParts.push(normalizeMake(vehicle.make));
                            if (vehicle.model) titleParts.push(vehicle.model);
                            if (vehicle.version) titleParts.push(vehicle.version);

                            if (titleParts.length > 0) {
                                let title = titleParts.join(' ');
                                $("#title").val(title).trigger('keyup');
                            }

                            toastr.success("Vehicle details loaded");
                        },
                        error: function(xhr) {
                            let msg = "MotorCheck lookup failed";
                            if (xhr && xhr.responseJSON) {
                                if (xhr.responseJSON.message) {
                                    msg = xhr.responseJSON.message;
                                }

                                if (xhr.responseJSON.status) {
                                    msg += " (" + xhr.responseJSON.status + ")";
                                }
                            }

                            toastr.error(msg);
                        }
                    });
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

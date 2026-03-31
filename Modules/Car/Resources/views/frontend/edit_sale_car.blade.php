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
                        <input type="hidden" name="lang_code" value="{{ admin_lang() }}">
                        <input type="hidden" name="translate_id" value="{{ $car_translate->id }}">
                        <input type="hidden" id="slug" name="slug" value="{{ html_decode($car->slug) }}">

                        <div class="row gy-5">
                            <!-- Car Images  -->
                            <div class="col-lg-12">
                                <div class="car-images">
                                    <h3 class="car-images-taitel">{{ __('Images') }}</h3>
                                    <div class="car-images-inner">
                                        <h6 class="car-images-inner-txt">{{ __('translate.Upload New Image') }}
                                              <i 
                                                class="fas fa-info-circle text-info"
                                                data-toggle="tooltip"
                                                data-placement="right"
                                                title="First uploaded image will be used as thumbnail. Maximum 8 images allowed."
                                                style="cursor: pointer;"
                                            ></i>
                                        </h6>

                                        <div class="row">
                                            <div class="col-xl-6 col-lg-8">
                                                <div class="choose-file-txt">
                                                    <h6>{{ __('translate.New') }} <span>{{ __('translate.Choose File') }}</span> {{ __('translate.Upload') }}</h6>
                                                    <input type="file" id="gallery_images_input" name="gallery_images[]" multiple accept="image/*">
                                                </div>
                                            </div>
                                        </div>
                                        <div id="gallery_images_limit_note" class="mt-2"></div>
                                        <div id="gallery_preview_grid" class="gallery-preview-grid"></div>
                                        @if($car->galleries->count())
                                            <div class="gallery-preview-grid mt-3" id="existing_gallery_grid">
                                                @foreach($car->galleries as $gallery)
                                                    <div class="gallery-preview-card">
                                                        <img src="{{ asset($gallery->image) }}" alt="img">
                                                        <form action="{{ route('user.delete-gallery', $gallery->id) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to remove this image?') }}');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="gallery-preview-remove" aria-label="{{ __('Remove image') }}">&times;</button>
                                                        </form>
                                                        <div class="gallery-preview-meta">{{ __('Existing Image') }}</div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
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
                                                    <label for="registration_number" class="form-label">{{ __('Registration Number') }}</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" id="registration_number" placeholder="{{ __('Registration Number') }}" value="{{ old('registration_number', $car->motorcheck_reg) }}">
                                                        <button type="button" class="btn btn-primary" id="btn_motorcheck_lookup">{{ __('Fetch Details') }}</button>
                                                    </div>
                                                </div>
                                            </div>

                                            <div id="vehicle_details_panel" style="display:none;" class="vehicle-details-panel">
                                                <div class="vehicle-details-header">
                                                    <div class="vehicle-details-title">{{ __('Vehicle details found') }}</div>
                                                    <div class="vehicle-details-subtitle">{{ __('Check the details below before publishing your ad') }}</div>
                                                </div>

                                                <div class="vehicle-details-grid">
                                                    <div class="vehicle-details-item">
                                                        <div class="vehicle-details-label">{{ __('Make') }}</div>
                                                        <div class="vehicle-details-value" id="vd_make">—</div>
                                                    </div>
                                                    <div class="vehicle-details-item">
                                                        <div class="vehicle-details-label">{{ __('Model') }}</div>
                                                        <div class="vehicle-details-value" id="vd_model">—</div>
                                                    </div>

                                                    <div class="vehicle-details-item">
                                                        <div class="vehicle-details-label">{{ __('Version') }}</div>
                                                        <div class="vehicle-details-value" id="vd_version">—</div>
                                                    </div>
                                                    <div class="vehicle-details-item">
                                                        <div class="vehicle-details-label">{{ __('Car Model') }}</div>
                                                        <div class="vehicle-details-value" id="vd_car_model">—</div>
                                                    </div>

                                                    <div class="vehicle-details-item">
                                                        <div class="vehicle-details-label">{{ __('Body Type') }}</div>
                                                        <div class="vehicle-details-value" id="vd_body">—</div>
                                                    </div>
                                                    <div class="vehicle-details-item">
                                                        <div class="vehicle-details-label">{{ __('Fuel Type') }}</div>
                                                        <div class="vehicle-details-value" id="vd_fuel">—</div>
                                                    </div>

                                                    <div class="vehicle-details-item">
                                                        <div class="vehicle-details-label">{{ __('Colour') }}</div>
                                                        <div class="vehicle-details-value" id="vd_colour">—</div>
                                                    </div>
                                                    <div class="vehicle-details-item">
                                                        <div class="vehicle-details-label">{{ __('Year') }}</div>
                                                        <div class="vehicle-details-value" id="vd_year">—</div>
                                                    </div>

                                                    <div class="vehicle-details-item">
                                                        <div class="vehicle-details-label">{{ __('Transmission') }}</div>
                                                        <div class="vehicle-details-value" id="vd_transmission">—</div>
                                                    </div>
                                                    <div class="vehicle-details-item">
                                                        <div class="vehicle-details-label">{{ __('Engine Size') }}</div>
                                                        <div class="vehicle-details-value" id="vd_engine_size">—</div>
                                                    </div>

                                                    <div class="vehicle-details-item">
                                                        <div class="vehicle-details-label">{{ __('Number of Doors') }}</div>
                                                        <div class="vehicle-details-value" id="vd_doors">—</div>
                                                    </div>
                                                    <div class="vehicle-details-item">
                                                        <div class="vehicle-details-label">{{ __('NCT Expiry') }}</div>
                                                        <div class="vehicle-details-value" id="vd_nct">—</div>
                                                    </div>

                                                    <div class="vehicle-details-item">
                                                        <div class="vehicle-details-label">{{ __('Owners') }}</div>
                                                        <div class="vehicle-details-value" id="vd_owners">—</div>
                                                    </div>
                                                    <div class="vehicle-details-item">
                                                        <div class="vehicle-details-label">{{ __('Tax Expiry Date') }}</div>
                                                        <div class="vehicle-details-value" id="vd_tax_expiry">—</div>
                                                    </div>

                                                    <div class="vehicle-details-item">
                                                        <div class="vehicle-details-label">{{ __('CO2 Emissions') }}</div>
                                                        <div class="vehicle-details-value" id="vd_co2">—</div>
                                                    </div>
                                                </div>

                                                <button type="button" class="vehicle-details-action" id="btn_apply_vehicle_details">{{ __('Edit vehicle details') }}</button>
                                            </div>

                                            <input type="hidden" name="motorcheck_reg" id="motorcheck_reg" value="{{ old('motorcheck_reg', $car->motorcheck_reg) }}">
                                            <input type="hidden" name="motorcheck_make" id="motorcheck_make" value="{{ old('motorcheck_make', $car->motorcheck_make) }}">
                                            <input type="hidden" name="motorcheck_model" id="motorcheck_model" value="{{ old('motorcheck_model', $car->motorcheck_model) }}">
                                            <input type="hidden" name="motorcheck_version" id="motorcheck_version" value="{{ old('motorcheck_version', $car->motorcheck_version) }}">
                                            <input type="hidden" name="motorcheck_body" id="motorcheck_body" value="{{ old('motorcheck_body', $car->motorcheck_body) }}">
                                            <input type="hidden" name="motorcheck_doors" id="motorcheck_doors" value="{{ old('motorcheck_doors', $car->motorcheck_doors) }}">
                                            <input type="hidden" name="motorcheck_reg_date" id="motorcheck_reg_date" value="{{ old('motorcheck_reg_date', $car->motorcheck_reg_date) }}">
                                            <input type="hidden" name="motorcheck_engine_cc" id="motorcheck_engine_cc" value="{{ old('motorcheck_engine_cc', $car->motorcheck_engine_cc) }}">
                                            <input type="hidden" name="motorcheck_colour" id="motorcheck_colour" value="{{ old('motorcheck_colour', $car->motorcheck_colour) }}">
                                            <input type="hidden" name="motorcheck_fuel" id="motorcheck_fuel" value="{{ old('motorcheck_fuel', $car->motorcheck_fuel) }}">
                                            <input type="hidden" name="motorcheck_transmission" id="motorcheck_transmission" value="{{ old('motorcheck_transmission', $car->motorcheck_transmission) }}">
                                            <input type="hidden" name="motorcheck_no_of_owners" id="motorcheck_no_of_owners" value="{{ old('motorcheck_no_of_owners', $car->motorcheck_no_of_owners) }}">
                                            <input type="hidden" name="motorcheck_tax_class" id="motorcheck_tax_class" value="{{ old('motorcheck_tax_class', $car->motorcheck_tax_class) }}">
                                            <input type="hidden" name="motorcheck_tax_expiry_date" id="motorcheck_tax_expiry_date" value="{{ old('motorcheck_tax_expiry_date', $car->motorcheck_tax_expiry_date) }}">
                                            <input type="hidden" name="motorcheck_nct_expiry_date" id="motorcheck_nct_expiry_date" value="{{ old('motorcheck_nct_expiry_date', $car->motorcheck_nct_expiry_date) }}">
                                            <input type="hidden" name="motorcheck_co2_emissions" id="motorcheck_co2_emissions" value="{{ old('motorcheck_co2_emissions', $car->motorcheck_co2_emissions) }}">
                                            <input type="hidden" name="motorcheck_last_date_of_sale" id="motorcheck_last_date_of_sale" value="{{ old('motorcheck_last_date_of_sale', $car->motorcheck_last_date_of_sale) }}">
                                            <input type="hidden" name="motorcheck_raw" id="motorcheck_raw" value="{{ old('motorcheck_raw', $car->motorcheck_raw) }}">

                                            <div class="description-item two">
                                                <div class="description-item-inner">
                                                    <label for="mileage" class="form-label">{{ __('translate.Mileage') }}
                                                        <span>*</span> </label>
                                                        <input class="form-control" type="text" name="mileage" id="mileage" value="{{ html_decode($car->mileage) }}" placeholder="{{ __('translate.Mileage') }}">
                                                </div>
                                            </div>


                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="car-images">
                                    <h3 class="car-images-taitel">{{ __('Ad Details') }}</h3>

                                    <div class="car-images-inner">
                                        <div class="description-item two">
                                            <div class="description-item-inner">
                                                <label for="title" class="form-label">{{ __('translate.Title') }}
                                                    <span>*</span> </label>
                                                <input type="text" class="form-control" id="title"
                                                    placeholder="{{ __('translate.Title') }}" name="title" value="{{ html_decode($car_translate->title) }}">
                                            </div>

                                            <div class="description-item-inner" id="wrap_brand" style="display:none;">
                                                <label for="brand" class="form-label">{{ __('translate.Brand') }}
                                                    <span>*</span> </label>
                                                <select class="form-select select2" name="brand_id" id="brand_id">
                                                    <option value="">{{ __('translate.Select Brand') }}</option>
                                                    @foreach ($brands as $brand)
                                                        <option  {{ $brand->id == $car->brand_id ? 'selected' : '' }} value="{{ $brand->id }}">{{ $brand->translate->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div id="vehicle_details_fields" style="display:none;">
                                            <div class="description-item two">

                                                <div class="description-item-inner" id="wrap_body_type">
                                                    <label for="body_type" class="form-label">{{ __('translate.Body Type') }}
                                                        <span>*</span> </label>
                                                    <input type="text" class="form-control" id="body_type"
                                                        placeholder="{{ __('translate.Body Type') }}" name="body_type" value="{{ html_decode($car->body_type) }}">
                                                </div>

                                                <div class="description-item-inner" id="wrap_engine_size">
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

                                                <div class="description-item-inner" id="wrap_exterior_color">
                                                    <label for="exterior_color" class="form-label">{{ __('translate.Exterior Color') }}
                                                        <span>*</span> </label>
                                                    <input type="text" class="form-control"
                                                        placeholder="{{ __('translate.Exterior Color') }}" name="exterior_color" id="exterior_color" value="{{ html_decode($car->exterior_color) }}">
                                                </div>

                                                <div class="description-item-inner" id="wrap_year">
                                                    <label for="year" class="form-label">{{ __('translate.Year') }}
                                                        <span>*</span> </label>
                                                        <input class="form-control" type="text" name="year" id="year" value="{{ html_decode($car->year) }}" placeholder="{{ __('translate.Year') }}">
                                                </div>
                                            </div>

                                            <div class="description-item two">
                                                <div class="description-item-inner" id="wrap_number_of_owner">
                                                    <label for="number_of_owner" class="form-label">{{ __('translate.Number of Owner') }}
                                                        <span>*</span> </label>
                                                    <input type="text" class="form-control"
                                                        placeholder="{{ __('translate.Number of Owner') }}" name="number_of_owner" id="number_of_owner" value="{{ html_decode($car->number_of_owner) }}">
                                                </div>

                                                <div class="description-item-inner" id="wrap_fuel_type">
                                                    <label for="fuel_type" class="form-label">{{ __('translate.Fuel Type') }}
                                                        <span>*</span> </label>
                                                    <input type="text" class="form-control"
                                                        placeholder="{{ __('translate.Fuel Type') }}" name="fuel_type" id="fuel_type" value="{{ html_decode($car->fuel_type) }}">
                                                </div>

                                                <div class="description-item-inner" id="wrap_transmission">
                                                    <label for="transmission" class="form-label">{{ __('translate.Transmission') }}
                                                        <span>*</span> </label>
                                                    <input type="text" class="form-control"
                                                        placeholder="{{ __('translate.Transmission') }}" name="transmission" id="transmission" value="{{ html_decode($car->transmission) }}">
                                                </div>

                                                <div class="description-item-inner" id="wrap_car_model">
                                                    <label for="car_model" class="form-label">{{ __('translate.Car Model') }}
                                                        <span>*</span> </label>
                                                    <input class="form-control" type="text" name="car_model" id="car_model" value="{{ html_decode($car->car_model) }}" placeholder="{{ __('translate.Car Model') }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="description-item two">

                                            <div class="description-item-inner">
                                                <label for="country" class="form-label">{{ __('translate.Country') }}
                                                    <span>*</span> </label>
                                                <input type="hidden" name="country_id" value="{{ $ireland?->id }}">
                                                <input type="text" class="form-control" value="{{ $ireland?->name ?? __('translate.Ireland') }}" readonly>
                                            </div>

                                            <div class="description-item-inner">
                                                <label for="city" class="form-label">{{ __('translate.City') }}
                                                    <span>*</span> </label>
                                                <select class="form-select select2" name="city_id" id="city_id">
                                                    <option value="">{{ __('translate.Select City') }}</option>
                                                    @foreach ($cities as $city)
                                                        <option {{ $city->id == $car->city_id ? 'selected' : '' }} value="{{ $city->id }}">{{ $city->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                        </div>

                                        <div class="description-item two">

                                            <div class="description-item-inner">
                                                <label for="price" class="form-label">{{ __('translate.Price') }}
                                                    <span>*</span> </label>
                                                <input type="text" class="form-control" placeholder="{{ __('translate.Price') }}"  name="price" value="{{ html_decode($car->regular_price) }}">
                                            </div>

                                        </div>

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
                                                @php
                                                    $authUser = Auth::guard('web')->user();
                                                    $sellerType = ($authUser && $authUser->is_dealer) ? 'Dealer' : 'Personal';
                                                @endphp
                                                <input type="hidden" name="seller_type" value="{{ $sellerType }}">
                                                <input type="text" class="form-control" value="{{ $sellerType }}" readonly>
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

                                    <div class="car-images-inner">
                                        <div class="description-item">
                                            <div class="description-item-inner">
                                                <label for="video_id" class="form-label">{{ __('translate.Youtube Video Id') }} </label>
                                                <input type="text" class="form-control"
                                                    placeholder="{{ __('translate.Youtube Video Id') }}" name="video_id" id="video_id" value="{{ html_decode($car->video_id) }}">
                                            </div>

                                        </div>
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

        .vehicle-details-panel{
            padding: 18px;
            border: 1px solid #e6e6e6;
            border-radius: 10px;
            background: #fff;
            margin-bottom: 15px;
        }
        .vehicle-details-title{font-weight: 600;}
        .vehicle-details-subtitle{opacity: .7; margin-top: 4px;}
        .vehicle-details-grid{
            margin-top: 14px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px 28px;
        }
        .vehicle-details-item{
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            gap: 10px;
        }
        .vehicle-details-label{opacity: .75;}
        .vehicle-details-value{font-weight: 600; text-align: right;}
        .vehicle-details-action{
            margin-top: 14px;
            padding: 0;
            border: 0;
            background: transparent;
            color: #0d6efd;
            text-decoration: underline;
            font-weight: 600;
        }
        .gallery-preview-grid{
            display:grid;
            grid-template-columns:repeat(auto-fill,minmax(140px,1fr));
            gap:14px;
            margin-top:16px;
        }
        .gallery-preview-card{
            position:relative;
            border:1px solid #e6e6e6;
            border-radius:10px;
            overflow:hidden;
            background:#fff;
        }
        .gallery-preview-card img{
            width:100%;
            height:120px;
            object-fit:cover;
            display:block;
        }
        .gallery-preview-meta{
            padding:8px 10px;
            font-size:12px;
            color:#666;
        }
        .gallery-preview-remove{
            position:absolute;
            top:8px;
            right:8px;
            border:0;
            background:#dc3545;
            color:#fff;
            width:28px;
            height:28px;
            border-radius:50%;
            font-size:16px;
            line-height:28px;
        }
    </style>
@endpush

@push('js_section')

    <script src="{{ asset('global/tinymce/js/tinymce/tinymce.min.js') }}"></script>

    <script>
        (function($) {
            "use strict"
            $(document).ready(function () {
                const galleryInput = document.getElementById('gallery_images_input');
                const galleryPreviewGrid = document.getElementById('gallery_preview_grid');
                const galleryLimitNote = document.getElementById('gallery_images_limit_note');
                const existingGalleryGrid = document.getElementById('existing_gallery_grid');
                let selectedGalleryFiles = [];

                function getExistingGalleryCount() {
                    if (!existingGalleryGrid) {
                        return 0;
                    }

                    return existingGalleryGrid.querySelectorAll('.gallery-preview-card').length;
                }

                function syncGalleryInputFiles() {
                    if (!galleryInput) {
                        return;
                    }

                    const dataTransfer = new DataTransfer();
                    selectedGalleryFiles.forEach(function(file) {
                        dataTransfer.items.add(file);
                    });
                    galleryInput.files = dataTransfer.files;
                }

                function renderGalleryPreview() {
                    if (!galleryPreviewGrid || !galleryLimitNote) {
                        return;
                    }

                    galleryPreviewGrid.innerHTML = '';
                    const totalImages = getExistingGalleryCount() + selectedGalleryFiles.length;
                    galleryLimitNote.textContent = totalImages ? (totalImages + ' / 8 images selected') : '';

                    selectedGalleryFiles.forEach(function(file, index) {
                        const card = document.createElement('div');
                        card.className = 'gallery-preview-card';

                        const img = document.createElement('img');
                        img.src = URL.createObjectURL(file);
                        img.onload = function() {
                            URL.revokeObjectURL(img.src);
                        };

                        const meta = document.createElement('div');
                        meta.className = 'gallery-preview-meta';
                        meta.textContent = file.name;

                        const removeBtn = document.createElement('button');
                        removeBtn.type = 'button';
                        removeBtn.className = 'gallery-preview-remove';
                        removeBtn.innerHTML = '&times;';
                        removeBtn.addEventListener('click', function() {
                            selectedGalleryFiles.splice(index, 1);
                            syncGalleryInputFiles();
                            renderGalleryPreview();
                        });

                        card.appendChild(img);
                        card.appendChild(removeBtn);
                        card.appendChild(meta);
                        galleryPreviewGrid.appendChild(card);
                    });
                }

                if (galleryInput) {
                    galleryInput.addEventListener('change', function(event) {
                        const incomingFiles = Array.from(event.target.files || []);
                        const mergedFiles = selectedGalleryFiles.concat(incomingFiles);

                        if ((mergedFiles.length + getExistingGalleryCount()) > 8) {
                            toastr.error('{{ __('You can upload maximum 8 images only.') }}');
                            event.target.value = '';
                            return;
                        }

                        selectedGalleryFiles = mergedFiles;
                        syncGalleryInputFiles();
                        renderGalleryPreview();
                    });
                }

                renderGalleryPreview();

                $("#title").on("input", function() {
                    $(this).attr('data-user-edited', '1');
                });

                $("#title").on("keyup",function(e){
                    let inputValue = $(this).val();
                    let slug = inputValue.toLowerCase().replace(/[^\w ]+/g,'').replace(/ +/g,'-');
                    $("#slug").val(slug);
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

                function normalizeTransmission(trans) {
                    if (!trans) return trans;
                    let t = String(trans).trim().toUpperCase();
                    if (t === 'A') return 'Automatic';
                    if (t === 'M') return 'Manual';
                    return trans;
                }

                function setVehicleDetailText(selector, value) {
                    let v = (value === undefined || value === null) ? '' : String(value).trim();
                    $(selector).text(v ? v : '—');
                }

                function normalizeBrandText(text) {
                    if (!text) return '';
                    return String(text).trim().toLowerCase().replace(/\s+/g, ' ');
                }

                function trySetBrandFromMake(make) {
                    let mk = normalizeBrandText(make);
                    if (!mk) return false;

                    let matchedVal = null;
                    $("#brand_id option").each(function() {
                        let optVal = $(this).attr('value');
                        if (!optVal) return;
                        let optText = normalizeBrandText($(this).text());
                        if (!optText) return;
                        if (optText === mk) {
                            matchedVal = optVal;
                        }
                    });

                    if (matchedVal) {
                        $("#brand_id").val(matchedVal).trigger('change');
                        $("#wrap_brand").hide();
                        return true;
                    }

                    return false;
                }

                function hydratePanelFromHiddenInputs() {
                    let vehicleDetails = {
                        make: $("#motorcheck_make").val() || '',
                        model: $("#motorcheck_model").val() || '',
                        version: $("#motorcheck_version").val() || '',
                        car_model: $("#motorcheck_model").val() || '',
                        year: (function() {
                            let rd = $("#motorcheck_reg_date").val();
                            if (rd && String(rd).length >= 4) return String(rd).substring(0, 4);
                            return '';
                        })(),
                        engine_size: $("#motorcheck_engine_cc").val() || '',
                        fuel_type: $("#motorcheck_fuel").val() || '',
                        transmission: normalizeTransmission($("#motorcheck_transmission").val() || ''),
                        body_type: $("#motorcheck_body").val() || '',
                        colour: $("#motorcheck_colour").val() || '',
                        doors: $("#motorcheck_doors").val() || '',
                        nct: $("#motorcheck_nct_expiry_date").val() || '',
                        owners: $("#motorcheck_no_of_owners").val() || '',
                        tax_expiry: $("#motorcheck_tax_expiry_date").val() || '',
                        co2: $("#motorcheck_co2_emissions").val() || '',
                    };

                    if (!vehicleDetails.make && !vehicleDetails.model && !vehicleDetails.version && !vehicleDetails.body_type) {
                        return null;
                    }

                    setVehicleDetailText("#vd_make", vehicleDetails.make);
                    setVehicleDetailText("#vd_model", vehicleDetails.model);
                    setVehicleDetailText("#vd_version", vehicleDetails.version);
                    setVehicleDetailText("#vd_car_model", vehicleDetails.car_model);
                    setVehicleDetailText("#vd_year", vehicleDetails.year);
                    setVehicleDetailText("#vd_engine_size", vehicleDetails.engine_size);
                    setVehicleDetailText("#vd_fuel", vehicleDetails.fuel_type);
                    setVehicleDetailText("#vd_transmission", vehicleDetails.transmission);
                    setVehicleDetailText("#vd_body", vehicleDetails.body_type);
                    setVehicleDetailText("#vd_colour", vehicleDetails.colour);
                    setVehicleDetailText("#vd_doors", vehicleDetails.doors);
                    setVehicleDetailText("#vd_nct", vehicleDetails.nct);
                    setVehicleDetailText("#vd_owners", vehicleDetails.owners);
                    setVehicleDetailText("#vd_tax_expiry", vehicleDetails.tax_expiry);
                    setVehicleDetailText("#vd_co2", vehicleDetails.co2);

                    $("#vehicle_details_panel").show();

                    if (vehicleDetails.make) {
                        let mappedBrand = trySetBrandFromMake(vehicleDetails.make);
                        if (!mappedBrand) {
                            $("#wrap_brand").show();
                        }
                    } else {
                        $("#wrap_brand").show();
                    }

                    return vehicleDetails;
                }

                let vehicleEditMode = false;
                function toggleManualFieldsForApiData(vehicleDetails, editMode) {
                    if (!vehicleDetails) {
                        $("#vehicle_details_fields").show();
                        return;
                    }

                    const rules = [
                        {wrap: "#wrap_engine_size", value: vehicleDetails.engine_size},
                        {wrap: "#wrap_exterior_color", value: vehicleDetails.colour},
                        {wrap: "#wrap_fuel_type", value: vehicleDetails.fuel_type},
                        {wrap: "#wrap_transmission", value: vehicleDetails.transmission},
                        {wrap: "#wrap_year", value: vehicleDetails.year},
                        {wrap: "#wrap_car_model", value: vehicleDetails.car_model},
                        {wrap: "#wrap_body_type", value: vehicleDetails.body_type},
                        {wrap: "#wrap_number_of_owner", value: vehicleDetails.owners},
                    ];

                    let anyVisible = false;
                    rules.forEach(function(r) {
                        let hasVal = r.value !== undefined && r.value !== null && String(r.value).trim() !== '';
                        if (editMode) {
                            $(r.wrap).show();
                            anyVisible = true;
                        } else {
                            if (hasVal) {
                                $(r.wrap).hide();
                            } else {
                                $(r.wrap).show();
                                anyVisible = true;
                            }
                        }
                    });

                    if (anyVisible) {
                        $("#vehicle_details_fields").show();
                    } else {
                        $("#vehicle_details_fields").hide();
                    }
                }

                let initialDetails = hydratePanelFromHiddenInputs();
                if (initialDetails) {
                    toggleManualFieldsForApiData(initialDetails, false);
                }

                $("#btn_apply_vehicle_details").off('click').on('click', function() {
                    vehicleEditMode = !vehicleEditMode;
                    let details = hydratePanelFromHiddenInputs();
                    toggleManualFieldsForApiData(details, vehicleEditMode);
                });

                $("#btn_motorcheck_lookup").on("click", function() {
                    let reg = $("#registration_number").val();
                    if (!reg) {
                        toastr.error("{{ __('Please fill out the form') }}");
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

                            const vehicleDetails = {
                                make: mapped.make || vehicle.make || '',
                                model: mapped.model || vehicle.model || '',
                                version: mapped.motorcheck_version || vehicle.version || '',
                                car_model: mapped.model || vehicle.model || '',
                                year: mapped.year || vehicle.year || '',
                                engine_size: mapped.engine_size || mapped.motorcheck_engine_cc || '',
                                fuel_type: mapped.fuel_type || mapped.motorcheck_fuel || '',
                                transmission: normalizeTransmission(mapped.transmission || mapped.motorcheck_transmission || ''),
                                body_type: mapped.body_type || mapped.motorcheck_body || '',
                                colour: mapped.motorcheck_colour || mapped.exterior_color || '',
                                doors: (mapped.motorcheck_doors !== undefined && mapped.motorcheck_doors !== null) ? mapped.motorcheck_doors : '',
                                nct: mapped.motorcheck_nct_expiry_date || '',
                                owners: (mapped.motorcheck_no_of_owners !== undefined && mapped.motorcheck_no_of_owners !== null) ? mapped.motorcheck_no_of_owners : (mapped.number_of_owner || ''),
                                tax_expiry: mapped.motorcheck_tax_expiry_date || '',
                                co2: (mapped.motorcheck_co2_emissions !== undefined && mapped.motorcheck_co2_emissions !== null) ? mapped.motorcheck_co2_emissions : '',
                            };

                            setVehicleDetailText("#vd_make", vehicleDetails.make);
                            setVehicleDetailText("#vd_model", vehicleDetails.model);
                            setVehicleDetailText("#vd_version", vehicleDetails.version);
                            setVehicleDetailText("#vd_car_model", vehicleDetails.car_model);
                            setVehicleDetailText("#vd_year", vehicleDetails.year);
                            setVehicleDetailText("#vd_engine_size", vehicleDetails.engine_size);
                            setVehicleDetailText("#vd_fuel", vehicleDetails.fuel_type);
                            setVehicleDetailText("#vd_transmission", vehicleDetails.transmission);
                            setVehicleDetailText("#vd_body", vehicleDetails.body_type);
                            setVehicleDetailText("#vd_colour", vehicleDetails.colour);
                            setVehicleDetailText("#vd_doors", vehicleDetails.doors);
                            setVehicleDetailText("#vd_nct", vehicleDetails.nct);
                            setVehicleDetailText("#vd_owners", vehicleDetails.owners);
                            setVehicleDetailText("#vd_tax_expiry", vehicleDetails.tax_expiry);
                            setVehicleDetailText("#vd_co2", vehicleDetails.co2);

                            $("#vehicle_details_panel").show();

                            if (vehicleDetails.make) {
                                $("#motorcheck_make").val(vehicleDetails.make);
                                let mappedBrand = trySetBrandFromMake(vehicleDetails.make);
                                if (!mappedBrand) {
                                    $("#wrap_brand").show();
                                }
                            } else {
                                $("#wrap_brand").show();
                            }

                            function buildAutoTitle() {
                                let parts = [];
                                if (vehicleDetails.make) parts.push(vehicleDetails.make);
                                if (vehicleDetails.version) parts.push(vehicleDetails.version);
                                if (vehicleDetails.car_model) parts.push(vehicleDetails.car_model);
                                if (parts.length === 0) return '';
                                return parts.join(' ');
                            }

                            let autoTitle = buildAutoTitle();
                            if (autoTitle) {
                                let titleEl = $("#title");
                                if (titleEl.attr('data-user-edited') !== '1') {
                                    titleEl.val(autoTitle);
                                    let slug = autoTitle.toLowerCase().replace(/[^\w ]+/g,'').replace(/ +/g,'-');
                                    $("#slug").val(slug);
                                }
                            }

                            toggleManualFieldsForApiData(vehicleDetails, false);

                            if (mapped.motorcheck_reg) $("#motorcheck_reg").val(mapped.motorcheck_reg);
                            if (mapped.motorcheck_make) $("#motorcheck_make").val(mapped.motorcheck_make);
                            if (mapped.motorcheck_model) $("#motorcheck_model").val(mapped.motorcheck_model);
                            if (mapped.motorcheck_version) $("#motorcheck_version").val(mapped.motorcheck_version);
                            if (mapped.motorcheck_body) $("#motorcheck_body").val(mapped.motorcheck_body);
                            if (mapped.motorcheck_doors !== undefined && mapped.motorcheck_doors !== null) $("#motorcheck_doors").val(mapped.motorcheck_doors);
                            if (mapped.motorcheck_reg_date) $("#motorcheck_reg_date").val(mapped.motorcheck_reg_date);
                            if (mapped.motorcheck_engine_cc !== undefined && mapped.motorcheck_engine_cc !== null) $("#motorcheck_engine_cc").val(mapped.motorcheck_engine_cc);
                            if (mapped.motorcheck_colour) $("#motorcheck_colour").val(mapped.motorcheck_colour);
                            if (mapped.motorcheck_fuel) $("#motorcheck_fuel").val(mapped.motorcheck_fuel);
                            if (mapped.motorcheck_transmission) $("#motorcheck_transmission").val(mapped.motorcheck_transmission);
                            if (mapped.motorcheck_no_of_owners !== undefined && mapped.motorcheck_no_of_owners !== null) $("#motorcheck_no_of_owners").val(mapped.motorcheck_no_of_owners);
                            if (mapped.motorcheck_tax_class) $("#motorcheck_tax_class").val(mapped.motorcheck_tax_class);
                            if (mapped.motorcheck_tax_expiry_date) $("#motorcheck_tax_expiry_date").val(mapped.motorcheck_tax_expiry_date);
                            if (mapped.motorcheck_nct_expiry_date) $("#motorcheck_nct_expiry_date").val(mapped.motorcheck_nct_expiry_date);
                            if (mapped.motorcheck_co2_emissions !== undefined && mapped.motorcheck_co2_emissions !== null) $("#motorcheck_co2_emissions").val(mapped.motorcheck_co2_emissions);
                            if (mapped.motorcheck_last_date_of_sale) $("#motorcheck_last_date_of_sale").val(mapped.motorcheck_last_date_of_sale);

                            try {
                                $("#motorcheck_raw").val(JSON.stringify(res.raw || res));
                            } catch (e) {
                                // ignore
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
                            $("#vehicle_details_fields").show();
                        }
                    });
                });

            });
        })(jQuery);

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

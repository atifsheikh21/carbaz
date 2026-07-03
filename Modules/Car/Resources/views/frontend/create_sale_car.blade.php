@extends('layout')
@section('title')
    <title>{{ __('Create Sale Car') }}</title>
@endsection
@section('body-content')
@php
    $authUser = Auth::guard('web')->user();
    $sellerTypeLabel = ($authUser && $authUser->is_dealer) ? 'Dealer/Company' : 'Private';
@endphp

<main>
    <!-- banner-part-start  -->

    <section class="inner-banner">
        <style>
            .place-ad-back-btn{
                display:inline-block;
                border:1px solid #cfcfcf;
                background:#fff;
                padding:10px 18px;
                border-radius:4px;
                font-weight:600;
                color:#111;
                text-decoration:none;
                line-height:1;
                position:absolute;
                left:0;
                top:0;
            }
            .place-ad-banner-col{position:relative;}
        </style>
        <div class="inner-banner-img" style=" background-image: url({{ getImageOrPlaceholder($breadcrumb,'1905x300') }}) "></div>
        <div class="container">
        <div class="col-lg-12">
            <div class="place-ad-banner-col">
                <a href="#" class="place-ad-back-btn d-none d-md-inline-block" onclick="event.preventDefault(); history.back();">{{ __('Back') }}</a>
            </div>
            <div class="inner-banner-df">
                <h1 class="inner-banner-taitel">{{ $sellerTypeLabel }} - {{ __('Create Ad') }}</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('Create Sale Car') }}</li>
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
                    @if(($feeFreeModeEnabled ?? false) === true)
                        <div class="alert alert-success" role="alert" style="margin-bottom:16px;">
                            <strong>Free Posting Enabled:</strong> During launch, posting is free for all users. Each ad remains active for 30 days.
                        </div>
                    @endif
                    <form action="{{ route('user.car.store') }}" method="POST" enctype="multipart/form-data" id="carCreateForm">
                        @csrf

                        @if($errors->any())
                            <div class="alert alert-danger" style="margin-bottom: 20px;">
                                <ul style="margin: 0; padding-left: 20px;">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <input type="hidden" name="purpose" value="{{ request()->get('purpose') }}">
                        <input type="hidden" name="agent_id" value="{{ Auth::guard('web')->user()->id }}">
                        <input type="hidden" name="seller_type" value="{{ old('seller_type', $sellerTypeLabel) }}">

                        @if($authUser && $authUser->is_dealer)
                        <div class="dealer-bulk-upload-banner">
                            <div class="dealer-bulk-upload-banner__icon">&#128196;</div>
                            <div class="dealer-bulk-upload-banner__text">
                                <strong>{{ __('Create multiple ads') }}</strong>
                                {{ __('Use our Bulk Upload tool to import many vehicles at once from a CSV file.') }}
                            </div>
                            <a href="{{ route('user.car.bulk-upload.form') }}" class="dealer-bulk-upload-banner__btn">
                                {{ __('Bulk Upload (CSV)') }}
                            </a>
                        </div>
                        <style>
                            .dealer-bulk-upload-banner{
                                display:flex;
                                align-items:center;
                                gap:16px;
                                background:#f8f9fa;
                                border:1px solid #e2e4e8;
                                border-left:4px solid #1a1a1a;
                                border-radius:6px;
                                padding:14px 20px;
                                margin-bottom:28px;
                                flex-wrap:wrap;
                            }
                            .dealer-bulk-upload-banner__icon{
                                font-size:28px;
                                line-height:1;
                                flex-shrink:0;
                            }
                            .dealer-bulk-upload-banner__text{
                                flex:1;
                                font-size:14px;
                                color:#444;
                                line-height:1.5;
                                min-width:180px;
                            }
                            .dealer-bulk-upload-banner__text strong{
                                display:block;
                                color:#1a1a1a;
                                font-size:15px;
                                margin-bottom:2px;
                            }
                            .dealer-bulk-upload-banner__btn{
                                flex-shrink:0;
                                background:#1a1a1a;
                                color:#fff;
                                border:none;
                                padding:9px 20px;
                                border-radius:5px;
                                font-size:13px;
                                font-weight:700;
                                text-decoration:none;
                                white-space:nowrap;
                                transition:background 0.2s;
                            }
                            .dealer-bulk-upload-banner__btn:hover{
                                background:#b60304;
                                color:#fff;
                                text-decoration:none;
                            }
                            @media(max-width:575.98px){
                                .dealer-bulk-upload-banner{
                                    flex-direction:column;
                                    align-items:flex-start;
                                }
                                .dealer-bulk-upload-banner__btn{
                                    width:100%;
                                    text-align:center;
                                }
                                #registration_lookup_section .input-group{
                                    max-width:100% !important;
                                    width:100%;
                                    display:flex;
                                    flex-direction:column;
                                    gap:10px;
                                }
                                #registration_lookup_section .form-control,
                                #registration_lookup_section .btn{
                                    width:100%;
                                    max-width:100%;
                                    display:block;
                                    border-radius:6px;
                                }
                                .mileage-mobile-row{
                                    display:grid !important;
                                    grid-template-columns:minmax(0,1fr) minmax(0,1fr);
                                    gap:12px;
                                }
                                .mileage-mobile-row .description-item-inner{
                                    width:100% !important;
                                    min-width:0;
                                }
                            }
                        </style>
                        @endif

                        <div class="row gy-5">
                            <!-- Name & Description Overview  -->
                            <div class="col-lg-12">
                                <div class="car-images">
                                    <h3 class="car-images-taitel">{{ __('Create single ad') }}</h3>

                                    <div class="car-images-inner">
                                        <input type="hidden" class="form-control" id="slug" name="slug" value="{{ old('slug') }}">

                                        @php
                                            $__isDealer = $authUser && $authUser->is_dealer;
                                            $__vehicleSource = old('vehicle_source', 'registered');
                                        @endphp

                                        <div class="description-item">
                                            <div class="description-item-inner">
                                                <label class="form-label">{{ __('Vehicle Type') }} <span>*</span></label>
                                                <div class="vehicle-source-options" id="vehicle_source">
                                                    <div class="vehicle-source-option" style="margin-bottom: 15px;">
                                                        <input type="radio" id="vehicle_source_registered" name="vehicle_source" value="registered" {{ $__vehicleSource === 'registered' ? 'checked' : '' }}>
                                                        <label for="vehicle_source_registered">{{ __('Registered Vehicle') }}</label>
                                                    </div>
                                                    <div class="vehicle-source-option" style="margin-bottom: 15px;">
                                                        <input type="radio" id="vehicle_source_unregistered" name="vehicle_source" value="unregistered" {{ $__vehicleSource === 'unregistered' ? 'checked' : '' }}>
                                                        <label for="vehicle_source_unregistered">{{ $__isDealer ? __('New / Unregistered Vehicle') . ' / ' . __('manual entry') : __('Manual Entry') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="description-item" id="registration_lookup_section">
                                            <div class="description-item-inner">
                                                <label for="registration_number" class="form-label">{{ __('Registration Number') }}</label>
                                                <div class="input-group" style="max-width:50%;">
                                                    <input type="text" class="form-control" id="registration_number" name="registration_number" value="{{ old('registration_number') }}" placeholder="{{ __('Registration Number') }}">
                                                    <button type="button" class="btn btn-primary" id="btn_motorcheck_lookup">{{ __('Get Vehicle Details') }}</button>
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
                                                    <div class="vehicle-details-value" id="vd_make">â€”</div>
                                                </div>
                                                <div class="vehicle-details-item">
                                                    <div class="vehicle-details-label">{{ __('Model') }}</div>
                                                    <div class="vehicle-details-value" id="vd_model">â€”</div>
                                                </div>

                                                <div class="vehicle-details-item">
                                                    <div class="vehicle-details-label">{{ __('Version') }}</div>
                                                    <div class="vehicle-details-value" id="vd_version">â€”</div>
                                                </div>
                                                <div class="vehicle-details-item">
                                                    <div class="vehicle-details-label">{{ __('Car Model') }}</div>
                                                    <div class="vehicle-details-value" id="vd_car_model">â€”</div>
                                                </div>

                                                <div class="vehicle-details-item">
                                                    <div class="vehicle-details-label">{{ __('Body Type') }}</div>
                                                    <div class="vehicle-details-value" id="vd_body">â€”</div>
                                                </div>
                                                <div class="vehicle-details-item">
                                                    <div class="vehicle-details-label">{{ __('Fuel Type') }}</div>
                                                    <div class="vehicle-details-value" id="vd_fuel">â€”</div>
                                                </div>

                                                <div class="vehicle-details-item">
                                                    <div class="vehicle-details-label">{{ __('Colour') }}</div>
                                                    <div class="vehicle-details-value" id="vd_colour">â€”</div>
                                                </div>
                                                <div class="vehicle-details-item">
                                                    <div class="vehicle-details-label">{{ __('Year') }}</div>
                                                    <div class="vehicle-details-value" id="vd_year">â€”</div>
                                                </div>

                                                <div class="vehicle-details-item">
                                                    <div class="vehicle-details-label">{{ __('Transmission') }}</div>
                                                    <div class="vehicle-details-value" id="vd_transmission">â€”</div>
                                                </div>
                                                <div class="vehicle-details-item">
                                                    <div class="vehicle-details-label">{{ __('Engine Size') }}</div>
                                                    <div class="vehicle-details-value" id="vd_engine_size">â€”</div>
                                                </div>

                                                <div class="vehicle-details-item">
                                                    <div class="vehicle-details-label">{{ __('Number of Doors') }}</div>
                                                    <div class="vehicle-details-value" id="vd_doors">â€”</div>
                                                </div>
                                                <div class="vehicle-details-item">
                                                    <div class="vehicle-details-label">{{ __('NCT Expiry') }}</div>
                                                    <div class="vehicle-details-value" id="vd_nct">â€”</div>
                                                </div>

                                                <div class="vehicle-details-item">
                                                    <div class="vehicle-details-label">{{ __('Owners') }}</div>
                                                    <div class="vehicle-details-value" id="vd_owners">â€”</div>
                                                </div>
                                                <div class="vehicle-details-item">
                                                    <div class="vehicle-details-label">{{ __('Tax Expiry Date') }}</div>
                                                    <div class="vehicle-details-value" id="vd_tax_expiry">â€”</div>
                                                </div>

                                                <div class="vehicle-details-item">
                                                    <div class="vehicle-details-label">{{ __('CO2 Emissions') }}</div>
                                                    <div class="vehicle-details-value" id="vd_co2">â€”</div>
                                                </div>
                                            </div>

                                            <button type="button" class="vehicle-details-action" id="btn_apply_vehicle_details">{{ __('Edit vehicle details') }}</button>
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

                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="car-images">
                                    <h3 class="car-images-taitel">{{ __('Ad Details') }}</h3>

                                    <div class="car-images-inner">
                                        <div class="description-item two">
                                            <div class="description-item-inner" id="wrap_title" style="display:none;">
                                                <label for="title" class="form-label">{{ __('Title') }}
                                                    <span>*</span> </label>
                                                <input type="text" class="form-control" id="title"
                                                    placeholder="{{ __('Title') }}" name="title" value="{{ old('title') }}">
                                            </div>

                                            <div class="description-item-inner" id="wrap_brand" style="display:none;">
                                                <label for="brand" class="form-label">{{ __('Brand') }}
                                                    <span>*</span> </label>
                                                <select class="form-select select2" name="brand_id" id="brand_id">
                                                    <option value="">{{ __('Select Brand') }}</option>
                                                    @foreach ($brands as $brand)
                                                        <option  {{ $brand->id == old('brand_id') ? 'selected' : '' }} value="{{ $brand->id }}">{{ $brand->translate->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                        </div>

                                        <div class="description-item two mileage-mobile-row">
                                            <div class="description-item-inner">
                                                <label for="mileage" class="form-label">{{ __('Mileage') }}
                                                </label>
                                                <input class="form-control" type="text" name="mileage" id="mileage" value="{{ old('mileage') }}" placeholder="{{ __('Mileage') }}">
                                            </div>

                                            <div class="description-item-inner">
                                                <label for="mileage_unit" class="form-label">{{ __('Mileage Unit') }}
                                                </label>
                                                <select class="form-select" name="mileage_unit" id="mileage_unit">
                                                    <option value="km" {{ old('mileage_unit', 'km') === 'km' ? 'selected' : '' }}>{{ __('KM') }}</option>
                                                    <option value="miles" {{ old('mileage_unit', 'km') === 'miles' ? 'selected' : '' }}>{{ __('Miles') }}</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div id="vehicle_details_fields" style="display:none;">
                                            <div class="description-item two">
                                                <div class="description-item-inner" id="wrap_body_type">
                                                    <label for="body_type" class="form-label">{{ __('Body Type') }}</label>
                                                    <input type="text" class="form-control" id="body_type" placeholder="{{ __('Body Type') }}" name="body_type" value="{{ old('body_type') }}">
                                                </div>

                                                <div class="description-item-inner" id="wrap_engine_size">
                                                    <label for="engine_size" class="form-label">{{ __('Engine Size') }}</label>
                                                    <input type="text" class="form-control" placeholder="{{ __('Engine Size') }}" name="engine_size" id="engine_size" value="{{ old('engine_size') }}">
                                                </div>
                                            </div>

                                            <div class="description-item two">
                                                <div class="description-item-inner" id="wrap_interior_color">
                                                    <label for="interior_color" class="form-label">{{ __('Interior Color') }}</label>
                                                    <input type="text" class="form-control" placeholder="{{ __('Interior Color') }}" name="interior_color" id="interior_color" value="{{ old('interior_color') }}">
                                                </div>

                                                <div class="description-item-inner" id="wrap_exterior_color">
                                                    <label for="exterior_color" class="form-label">{{ __('Exterior Color') }}</label>
                                                    <input type="text" class="form-control" placeholder="{{ __('Exterior Color') }}" name="exterior_color" id="exterior_color" value="{{ old('exterior_color') }}">
                                                </div>

                                                <div class="description-item-inner" id="wrap_year">
                                                    <label for="year" class="form-label">{{ __('Year') }}</label>
                                                    <input class="form-control" type="text" name="year" id="year" value="{{ old('year') }}" placeholder="{{ __('Year') }}">
                                                </div>
                                            </div>

                                            <div class="description-item two">
                                                <div class="description-item-inner" id="wrap_drive">
                                                    <label for="drive" class="form-label">{{ __('Drive') }}</label>
                                                    <input type="text" class="form-control" placeholder="{{ __('Drive') }}" name="drive" id="drive" value="{{ old('drive') }}">
                                                </div>

                                                <div class="description-item-inner" id="wrap_number_of_owner">
                                                    <label for="number_of_owner" class="form-label">{{ __('Number of Owner') }}</label>
                                                    <input type="text" class="form-control" placeholder="{{ __('Number of Owner') }}" name="number_of_owner" id="number_of_owner" value="{{ old('number_of_owner') }}">
                                                </div>

                                                <div class="description-item-inner" id="wrap_fuel_type">
                                                    <label for="fuel_type" class="form-label">{{ __('Fuel Type') }}</label>
                                                    <input type="text" class="form-control" placeholder="{{ __('Fuel Type') }}" name="fuel_type" id="fuel_type" value="{{ old('fuel_type') }}">
                                                </div>

                                                <div class="description-item-inner" id="wrap_transmission">
                                                    <label for="transmission" class="form-label">{{ __('Transmission') }}</label>
                                                    <input type="text" class="form-control" placeholder="{{ __('Transmission') }}" name="transmission" id="transmission" value="{{ old('transmission') }}">
                                                </div>

                                                <div class="description-item-inner" id="wrap_car_model">
                                                    <label for="car_model" class="form-label">{{ __('Car Model') }}</label>
                                                    <input type="text" class="form-control" placeholder="{{ __('Car Model') }}" name="car_model" id="car_model" value="{{ old('car_model') }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="description-item two">
                                            <div class="description-item-inner">
                                                <label for="price" class="form-label">{{ __('Price') }}
                                                    <span>*</span>
                                                </label>
                                                <input type="text" class="form-control" placeholder="{{ __('Price') }}" name="price" value="{{ old('price') }}">
                                            </div>
                                        </div>

                                        @if(Auth::guard('web')->check() && Auth::guard('web')->user()?->is_dealer)
                                        <div class="description-item two">
                                            <div class="description-item-inner">
                                                <label for="warranty_months" class="form-label">{{ __('Warranty') }}</label>
                                                <select class="form-select" name="warranty_months" id="warranty_months">
                                                    <option value="">{{ __('Select') }}</option>
                                                    @for($i = 1; $i <= 12; $i++)
                                                        <option value="{{ $i }}" {{ (string) old('warranty_months') === (string) $i ? 'selected' : '' }}>{{ $i }} {{ $i === 1 ? __('month') : __('months') }}</option>
                                                    @endfor
                                                    @for($y = 1; $y <= 10; $y++)
                                                        @php
                                                            $__months = $y * 12;
                                                        @endphp
                                                        <option value="{{ $__months }}" {{ (string) old('warranty_months') === (string) $__months ? 'selected' : '' }}>{{ $y }} {{ $y === 1 ? __('year') : __('years') }}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>
                                        @endif

                                        <div class="description-item two">
                                            <div class="description-item-inner">
                                                <label for="condition" class="form-label">{{ __('Condition') }} <span>*</span></label>
                                                <select class="form-select" name="condition" id="condition" required>
                                                    <option {{ 'Used' == old('condition', 'Used') ? 'selected' : '' }} value="Used">{{ __('Used') }}</option>
                                                    <option {{ 'New' == old('condition') ? 'selected' : '' }} value="New">{{ __('New') }}</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="description-item two">
                                            <div class="description-item-inner">
                                                <label for="description" class="form-label">{{ __('Description') }}
                                                    <span>*</span>
                                                </label>
                                                <textarea class="summernote"  name="description" id="description">{!! old('description') !!}</textarea>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <!-- Car Images  -->
 
                            <div class="col-lg-12">
                                <div class="car-images">
                                    <h3 class="car-images-taitel">{{ __('Images') }}</h3>
                                    <div class="car-images-inner">
                                        <h6 class="car-images-inner-txt">{{ __('Upload New Image') }} <span>*</span>
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
                                                <div class="modern-upload">
                                                    <input type="file" id="gallery_images_input" name="gallery_images[]" class="modern-upload-input" multiple accept="image/jpeg,image/png">
                                                    <label for="gallery_images_input" class="modern-upload-btn">{{ __('Upload photos') }}</label>
                                                    <div class="modern-upload-sub">{{ __('PNG, JPG. Max 8 images.') }}</div>
                                                    <div id="gallery_images_selected_text" class="modern-upload-selected">{{ __('No files selected') }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="gallery_images_limit_note" class="mt-2"></div>
                                        <div id="gallery_preview_grid" class="gallery-preview-grid"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Video  -->
                            <div class="col-lg-12">
                                <div class="car-images">
                                    <h3 class="car-images-taitel">{{ __('Video Information') }}</h3>
                                    <div class="car-images-inner">
                                        <h6 class="car-images-inner-txt">{{ __('Video Image') }}

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
                                                        <h6>{{ __('New') }} <span>{{ __('Choose File') }}</span> {{ __('Upload') }}</h6>
                                                        <input type="file" id="my-file-one" onchange="previewVideoImage(event)" name="video_image">
                                                    </div>



                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="car-images-inner">
                                        <div class="description-item">
                                            <div class="description-item-inner">
                                                <label for="video_id" class="form-label">{{ __('Youtube Video Id') }} </label>
                                                <input type="text" class="form-control"
                                                    placeholder="{{ __('Youtube Video Id') }}" name="video_id" id="video_id" value="{{ old('video_id') }}">
                                            </div>

                                        </div>
                                        <div class="description-item two">
                                            <div class="description-item-inner">
                                                <div class="description-item-inner">
                                                    <label for="video_description" class="form-label">{{ __('Description') }} </label>
                                                    <textarea class="form-control" id="video_description"
                                                        rows="5" placeholder="{{ __('Description') }}" name="video_description">{{ old('video_description') }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <!-- button  -->
                            <div class="col-lg-12">
                            <div class="description-form-btn" >
                                <button class="thm-btn-two">{{ __('Create Ad') }}</button>
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



<div id="adSubmittingOverlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.75);z-index:999999;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:18px;">
    <div style="text-align:center;padding:20px 24px;background:rgba(0,0,0,.4);border-radius:12px;backdrop-filter:blur(2px);">
        <div style="font-size:16px;margin-bottom:8px;">{{ __('Please wait') }}</div>
        <div style="font-size:22px;">{{ __('Your ad is being placed...') }}</div>
        <div style="font-size:14px;margin-top:10px;opacity:.8;">{{ __('System is reviewing your ad. Do not close this window.') }}</div>
    </div>
</div>

<script>
    (function(){
        var f = document.getElementById('carCreateForm');
        if (!f) return;
        f.addEventListener('submit', function(){
            var ov = document.getElementById('adSubmittingOverlay');
            if (ov){ ov.style.display = 'flex'; }
        });
    })();
    </script>

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
        .modern-upload{
            border: 1px dashed #d0d7de;
            border-radius: 12px;
            padding: 14px;
            background: #fff;
        }
        .modern-upload-input{
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }
        .modern-upload-btn{
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 14px;
            border-radius: 10px;
            background: #0d6efd;
            color: #fff;
            font-weight: 600;
            cursor: pointer;
            margin-bottom: 8px;
        }
        .modern-upload-btn:hover{
            background: #0b5ed7;
            color: #fff;
        }
        .modern-upload-sub{
            font-size: 12px;
            color: #6c757d;
            margin-bottom: 6px;
        }
        .modern-upload-selected{
            font-size: 13px;
            color: #333;
        }
    </style>
@endpush

@push('js_section')

    <script src="{{ asset('global/tinymce/js/tinymce/tinymce.min.js') }}"></script>

    @include('partials.image_upload_optimizer')
    <script>
        (function($) {
            "use strict"
            $(document).ready(function () {
                const galleryInput = document.getElementById('gallery_images_input');
                const galleryPreviewGrid = document.getElementById('gallery_preview_grid');
                const galleryLimitNote = document.getElementById('gallery_images_limit_note');
                const vehicleSourceInputs = document.querySelectorAll('input[name="vehicle_source"]');
                let selectedGalleryFiles = [];
                let vehicleEditMode = false;
                const initialBrandOptionsHtml = $('#brand_id').length ? $('#brand_id').html() : null;

                function normalizeOptionKey(value, text) {
                    const v = String(value || '').trim();
                    const t = String(text || '').trim().toLowerCase().replace(/\s+/g, ' ');
                    if (v !== '') {
                        return 'v:' + v;
                    }
                    return 't:' + t;
                }

                function dedupeBrandOptions() {
                    if (!$('#brand_id').length) {
                        return;
                    }

                    const seen = new Set();
                    $('#brand_id option').each(function() {
                        const optVal = $(this).attr('value');
                        const optText = $(this).text();
                        const key = normalizeOptionKey(optVal, optText);

                        if (key === 'v:' || key === 't:') {
                            return;
                        }

                        if (seen.has(key)) {
                            $(this).remove();
                            return;
                        }
                        seen.add(key);
                    });
                }

                function resetBrandOptions() {
                    if (!initialBrandOptionsHtml || !$('#brand_id').length) {
                        return;
                    }
                    $('#brand_id').html(initialBrandOptionsHtml);
                    dedupeBrandOptions();
                    $('#brand_id').val('').trigger('change');
                }

                function getSelectedVehicleSource() {
                    const checkedInput = document.querySelector('input[name="vehicle_source"]:checked');
                    return checkedInput ? checkedInput.value : 'registered';
                }

                function clearMotorcheckFields() {
                    [
                        '#motorcheck_reg',
                        '#motorcheck_make',
                        '#motorcheck_model',
                        '#motorcheck_version',
                        '#motorcheck_body',
                        '#motorcheck_doors',
                        '#motorcheck_reg_date',
                        '#motorcheck_engine_cc',
                        '#motorcheck_colour',
                        '#motorcheck_fuel',
                        '#motorcheck_transmission',
                        '#motorcheck_no_of_owners',
                        '#motorcheck_tax_class',
                        '#motorcheck_tax_expiry_date',
                        '#motorcheck_nct_expiry_date',
                        '#motorcheck_co2_emissions',
                        '#motorcheck_last_date_of_sale',
                        '#motorcheck_raw'
                    ].forEach(function(selector) {
                        $(selector).val('');
                    });
                }

                function setVehicleSourceMode(mode) {
                    const isRegistered = mode === 'registered';

                    $('#registration_lookup_section').toggle(isRegistered);
                    $('#vehicle_details_panel').toggle(isRegistered && $('#vehicle_details_panel').data('hasDetails') === true);
                    $('#wrap_title').toggle(!isRegistered || $('#vehicle_details_panel').data('hasDetails') === true);

                    if (isRegistered) {
                        vehicleEditMode = false;
                        $('#vehicle_details_fields').data('manual-mode', false).hide();
                        $('#wrap_brand').hide();
                        if ($('#vehicle_details_panel').data('hasDetails') === true) {
                            $('#vehicle_details_panel').show();
                        }
                    } else {
                        $('#vehicle_details_panel').hide().data('hasDetails', false);
                        $('#vehicle_details_fields').show().data('manual-mode', true);
                        $('#wrap_brand').show();
                        resetBrandOptions();
                        clearMotorcheckFields();
                    }
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

                    const gallerySelectedText = document.getElementById('gallery_images_selected_text');

                    galleryPreviewGrid.innerHTML = '';
                    galleryLimitNote.textContent = selectedGalleryFiles.length ? (selectedGalleryFiles.length + ' / 8 images selected') : '';
                    if (gallerySelectedText) {
                        gallerySelectedText.textContent = selectedGalleryFiles.length ? (selectedGalleryFiles.length + ' file(s) selected') : 'No files selected';
                    }

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
                    galleryInput.addEventListener('change', async function(event) {
                        const incomingFiles = await window.optimizeImageFilesForUpload(event.target.files || []);
                        const mergedFiles = selectedGalleryFiles.concat(incomingFiles);

                        if (mergedFiles.length > 8) {
                            toastr.error('{{ __('You can upload maximum 8 images only.') }}');
                            event.target.value = '';
                            return;
                        }

                        selectedGalleryFiles = mergedFiles;
                        syncGalleryInputFiles();
                        renderGalleryPreview();
                    });
                }

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

                $('#vehicle_details_panel').data('hasDetails', false);
                $('#vehicle_details_fields').data('manual-mode', {{ $__vehicleSource === 'unregistered' ? 'true' : 'false' }});
                dedupeBrandOptions();
                setVehicleSourceMode(getSelectedVehicleSource());

                if (vehicleSourceInputs.length) {
                    vehicleSourceInputs.forEach(function(input) {
                        input.addEventListener('change', function() {
                            vehicleEditMode = false;
                            setVehicleSourceMode(this.value);
                        });
                    });
                }

                function normalizeTransmission(trans) {
                    if (!trans) return trans;
                    let t = String(trans).trim().toUpperCase();
                    if (t === 'A') return 'Automatic';
                    if (t === 'M') return 'Manual';
                    return trans;
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
                        return true;
                    }

                    return false;
                }

                function toggleManualFieldsForApiData(editMode, vehicleDetails) {
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
                        } else if (hasVal) {
                            $(r.wrap).hide();
                        } else {
                            $(r.wrap).show();
                            anyVisible = true;
                        }
                    });

                    if (anyVisible) {
                        $("#vehicle_details_fields").show();
                    } else {
                        $("#vehicle_details_fields").hide();
                    }
                }

                function buildVehicleTitle(vehicleDetails) {
                    let parts = [];
                    if (vehicleDetails.make) parts.push(vehicleDetails.make);
                    if (vehicleDetails.version) parts.push(vehicleDetails.version);
                    if (vehicleDetails.car_model) parts.push(vehicleDetails.car_model);
                    return parts.join(' ');
                }

                function slugify(value) {
                    return String(value || '').toLowerCase().replace(/[^\w ]+/g,'').replace(/ +/g,'-');
                }

                $("#btn_motorcheck_lookup").on("click", function() {
                    if (getSelectedVehicleSource() !== 'registered') {
                        return;
                    }

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
                                toastr.error("No vehicle details found for this registration number.");
                                return;
                            }

                            let mapped = res.mapped || {};
                            let raw = res.raw || {};

                            const vehicleDetails = {
                                make: mapped.make || '',
                                model: mapped.model || '',
                                version: mapped.motorcheck_version || '',
                                car_model: mapped.model || '',
                                year: mapped.year || '',
                                engine_size: mapped.engine_size || mapped.motorcheck_engine_cc || '',
                                fuel_type: mapped.fuel_type || mapped.motorcheck_fuel || '',
                                transmission: normalizeTransmission(mapped.transmission || mapped.motorcheck_transmission || ''),
                                body_type: mapped.body_type || mapped.motorcheck_body || '',
                                colour: mapped.motorcheck_colour || mapped.exterior_color || '',
                                doors: mapped.motorcheck_doors ?? '',
                                nct: mapped.motorcheck_nct_expiry_date || '',
                                owners: (mapped.motorcheck_no_of_owners ?? mapped.number_of_owner) || '',
                                tax_expiry: mapped.motorcheck_tax_expiry_date || '',
                                co2: mapped.motorcheck_co2_emissions ?? '',
                            };

                            $('#vehicle_details_panel').show().data('hasDetails', true);
                            $('#wrap_title').show();
                            $("#vehicle_details_fields").data('manual-mode', true);
                            toggleManualFieldsForApiData(false, vehicleDetails);

                            $("#motorcheck_reg").val(mapped.motorcheck_reg || '');
                            $("#motorcheck_make").val(mapped.motorcheck_make || '');
                            $("#motorcheck_model").val(mapped.motorcheck_model || '');
                            $("#motorcheck_version").val(mapped.motorcheck_version || '');
                            $("#motorcheck_body").val(mapped.motorcheck_body || '');
                            $("#motorcheck_doors").val(mapped.motorcheck_doors || '');
                            $("#motorcheck_reg_date").val(mapped.motorcheck_reg_date || '');
                            $("#motorcheck_engine_cc").val(mapped.motorcheck_engine_cc || '');
                            $("#motorcheck_colour").val(mapped.motorcheck_colour || '');
                            $("#motorcheck_fuel").val(mapped.motorcheck_fuel || '');
                            $("#motorcheck_transmission").val(mapped.motorcheck_transmission || '');
                            $("#motorcheck_no_of_owners").val(mapped.motorcheck_no_of_owners || '');
                            $("#motorcheck_tax_class").val(mapped.motorcheck_tax_class || '');
                            $("#motorcheck_tax_expiry_date").val(mapped.motorcheck_tax_expiry_date || '');
                            $("#motorcheck_nct_expiry_date").val(mapped.motorcheck_nct_expiry_date || '');
                            $("#motorcheck_co2_emissions").val(mapped.motorcheck_co2_emissions || '');
                            $("#motorcheck_last_date_of_sale").val(mapped.motorcheck_last_date_of_sale || '');
                            $("#motorcheck_raw").val(JSON.stringify(raw || {}));

                            $("#registration_number").val(mapped.registration_number || reg);
                            $("#body_type").val(vehicleDetails.body_type || '').trigger('change');
                            $("#engine_size").val(vehicleDetails.engine_size || '');
                            $("#fuel_type").val(vehicleDetails.fuel_type || '').trigger('change');
                            $("#transmission").val(vehicleDetails.transmission || '').trigger('change');
                            $("#exterior_color").val(vehicleDetails.colour || '');
                            $("#number_of_owner").val(vehicleDetails.owners || '');
                            $("#year").val(vehicleDetails.year || '');
                            $("#car_model").val(vehicleDetails.car_model || '');

                            let autoTitle = buildVehicleTitle(vehicleDetails);
                            if (autoTitle) {
                                let titleEl = $("#title");
                                if (titleEl.attr('data-user-edited') !== '1') {
                                    titleEl.val(autoTitle);
                                    $("#slug").val(slugify(autoTitle));
                                }
                            }

                            if (vehicleDetails.make) {
                                $("#motorcheck_make").val(vehicleDetails.make);
                            }

                            $('#wrap_brand').hide();

                            $("#vd_make").text(vehicleDetails.make || 'â€”');
                            $("#vd_model").text(vehicleDetails.model || 'â€”');
                            $("#vd_version").text(vehicleDetails.version || 'â€”');
                            $("#vd_car_model").text(vehicleDetails.car_model || 'â€”');
                            $("#vd_body").text(vehicleDetails.body_type || 'â€”');
                            $("#vd_fuel").text(vehicleDetails.fuel_type || 'â€”');
                            $("#vd_colour").text(vehicleDetails.colour || 'â€”');
                            $("#vd_year").text(vehicleDetails.year || 'â€”');
                            $("#vd_transmission").text(vehicleDetails.transmission || 'â€”');
                            $("#vd_engine_size").text(vehicleDetails.engine_size || 'â€”');
                            $("#vd_doors").text(vehicleDetails.doors || 'â€”');
                            $("#vd_nct").text(vehicleDetails.nct || 'â€”');
                            $("#vd_owners").text(vehicleDetails.owners || 'â€”');
                            $("#vd_tax_expiry").text(vehicleDetails.tax_expiry || 'â€”');
                            $("#vd_co2").text(vehicleDetails.co2 || 'â€”');

                            $("#btn_apply_vehicle_details").off('click').on('click', function() {
                                vehicleEditMode = !vehicleEditMode;
                                $("#vehicle_details_fields").data('manual-mode', vehicleEditMode);
                                toggleManualFieldsForApiData(vehicleEditMode, vehicleDetails);
                            });

                            toastr.success("Vehicle details loaded");
                        },
                        error: function(xhr) {
                            let msg = xhr?.responseJSON?.message || 'Registration number not found. Please check and try again.';
                            toastr.error(msg);
                            $("#vehicle_details_fields").show().data('manual-mode', true);
                            $('#wrap_brand').hide();
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

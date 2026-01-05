@extends('layout')
@section('title')
    <title>{{ __('translate.Manage Kyc') }}</title>
@endsection
@section('body-content')

<main>
    <!-- banner-part-start  -->

    <section class="inner-banner">
    <div class="inner-banner-img" style=" background-image: url({{ getImageOrPlaceholder($breadcrumb,'1905x300') }}) "></div>
        <div class="container">
        <div class="col-lg-12">
            <div class="inner-banner-df">
                <h1 class="inner-banner-taitel">{{ __('translate.Manage Kyc') }}</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('translate.Home') }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('translate.Manage Kyc') }}</li>
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
                    <div class="row">
                        @if ($kyc)
                            <div class="col-lg-12 col-md-12">
                                <div class="add-listing-car">
                                    <div class="add-listing-car-thumb-main">
                                        <div class="add-listing-car-thumb">
                                            <a href="{{ asset($kyc->file) }}">
                                                <img width="120" src="{{ getImageOrPlaceholder($kyc->file, '120x90') }}" alt="">
                                            </a>
                                        </div>
                                    </div>

                                    <h4 class="add-listing-car-txt">{{ $kyc->kyc_type->name}}</h4>
                                    <p class="add-listing-car-sub-txt">
                                        {{ $kyc->message}}
                                    </p>

                                    <div class="add-listing-car-btn">
                                        @if ($kyc->status == 0)
                                            <a class="thm-btn-two">
                                                <div class="homec-dashboard-property__label">{{__('translate.Pending')}}</div>
                                            </a>
                                        @endif
                                        @if ($kyc->status == 1)
                                            <a class="thm-btn-two">
                                                <div class="homec-dashboard-property__label">{{__('translate.Approved')}}</div>
                                            </a>
                                        @endif
                                        @if ($kyc->status == 2)
                                            <a class="thm-btn-two">
                                                <div class="homec-dashboard-property__label">{{__('translate.Reject')}}</div>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @else

                        <div class="col-lg-9">
                            <form action="{{ route('user.kyc-submit') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row gy-5">

                                    <!-- Name & Description Overview  -->
                                    <div class="col-lg-12">
                                        <div class="car-images">
                                            <h3 class="car-images-taitel">{{ __('translate.Manage Kyc') }}</h3>

                                            <div class="car-images-inner">
                                                <div class="description-item-inner">
                                                    <label for="brand" class="form-label">{{__('translate.Select Type')}}
                                                        <span>*</span> </label>
                                                    <select class="form-select select2" name="kyc_id">
                                                        <option value="">{{__('translate.Select Type')}}</option>
                                                        @foreach ($kycType as $type)
                                                            <option  {{ $type->id == old('kyc_id') ? 'selected' : '' }} value="{{ $type->id }}">{{ $type->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="car-images">
                                                    <div class="car-images-inner">
                                                        <h6 class="car-images-inner-txt">{{ __('translate.Upload New Image') }} <span>*</span></h6>

                                                        <div class="row">
                                                            <div class=" col-xl-6 col-lg-6">
                                                                <div class="car-images-inner-item two">
                                                                    <div class="car-images-inner-item-thumb">
                                                                        <img src="{{ getImageOrPlaceholder($setting->placeholder_image, '1440x585') }}" id="thumb_image">
                                                                    </div>

                                                                    <div class="choose-file-txt">
                                                                        <h6>{{ __('translate.New') }} <span>{{ __('translate.Choose File') }}</span> {{ __('translate.Upload') }}</h6>
                                                                        <input type="file" id="my-file" onchange="previewImage(event)" name="file">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>

                                                <div class="description-item two">

                                                    <div class="description-item-inner">
                                                        <label for="offer_price" class="form-label">{{ __('translate.Description') }}
                                                            <span>*</span>
                                                        </label>
                                                        <textarea class="form-control"  name="message" id="message">{!! old('message') !!}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- button  -->
                                    <div class="col-lg-12">
                                        <div class="description-form-btn" >
                                        <button class="thm-btn-two">{{ __('translate.Submit') }}</button>
                                        </div>

                                    </div>
                                </div>

                            </form>
                        </div>





                        @endif
                    </div>
                </div>


            </div>
        </div>
        </div>
    </section>

    <!-- dashboard-part-end -->

    @include('profile.logout')



</main>

@endsection

@push('js_section')
    <script>
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function(){
                var output = document.getElementById('thumb_image');
                output.src = reader.result;
            }

            reader.readAsDataURL(event.target.files[0]);
        };
    </script>
@endpush

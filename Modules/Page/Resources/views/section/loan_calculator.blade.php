@extends('admin.master_layout')
@section('title')
    <title>{{ __('translate.Loan Calculator') }}</title>
@endsection

@section('body-header')
    <h3 class="crancy-header__title m-0">{{ __('translate.Loan Calculator') }}</h3>
    <p class="crancy-header__text">{{ __('translate.Manage Section') }} >> {{ __('translate.Loan Calculator') }}</p>
@endsection

@section('body-content')


    <!-- crancy Dashboard -->
    <section class="crancy-adashboard crancy-show">
        <div class="container container__bscreen">
            <div class="row">
                <div class="col-12">
                    <div class="crancy-body">
                        <!-- Dashboard Inner -->
                        <div class="crancy-dsinner">
                            <form action="{{ route('admin.update-loan-calculator') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                @method('PUT')

                                <div class="row">
                                    <div class="col-12 mg-top-30">
                                        <!-- Product Card -->
                                        <div class="crancy-product-card">

                                            <div class="row">

                                                <div class="col-12">
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class="crancy__item-form--group w-100 h-100">
                                                                <label class="crancy__item-label">{{ __('translate.Background Image') }} 
                                                                    <i 
                                                                    class="fas fa-info-circle text-info"
                                                                    data-toggle="tooltip"
                                                                    data-placement="right"
                                                                    title="Recommended size: 1904x825"
                                                                    style="cursor: pointer;"
                                                                ></i>
                                                                </label>
                                                                <div class="crancy-product-card__upload crancy-product-card__upload--border">
                                                                    <input type="file" class="btn-check" name="loan_bg_image" id="input-img1" autocomplete="off" onchange="previewImage(event)">
                                                                    <label class="crancy-image-video-upload__label" for="input-img1">
                                                                        <img id="view_img" src="{{ getImageOrPlaceholder($loan_calculator->loan_bg_image, '1904x825') }}">
                                                                        <h4 class="crancy-image-video-upload__title">{{ __('translate.Click here to') }} <span class="crancy-primary-color">{{ __('translate.Choose File') }}</span> {{ __('translate.and upload') }} </h4>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>

                                                <div class="col-12 mg-top-form-20">
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class="crancy__item-form--group w-100 h-100">
                                                                <label class="crancy__item-label">{{ __('translate.Foreground Image') }} 
                                                                    <i 
                                                                    class="fas fa-info-circle text-info"
                                                                    data-toggle="tooltip"
                                                                    data-placement="right"
                                                                    title="Recommended size: 218x165"
                                                                    style="cursor: pointer;"
                                                                ></i>
                                                                </label>
                                                                <div class="crancy-product-card__upload crancy-product-card__upload--border">
                                                                    <input type="file" class="btn-check" name="loan_foreground_image" id="input-img2" autocomplete="off" onchange="previewForgroundImage(event)">
                                                                    <label class="crancy-image-video-upload__label" for="input-img2">
                                                                        <img id="view_forground_img" src="{{ getImageOrPlaceholder($loan_calculator->loan_foreground_image, '218x165') }}">
                                                                        <h4 class="crancy-image-video-upload__title">{{ __('translate.Click here to') }} <span class="crancy-primary-color">{{ __('translate.Choose File') }}</span> {{ __('translate.and upload') }} </h4>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                            <button class="crancy-btn mg-top-25" type="submit">{{ __('translate.Update') }}</button>

                                        </div>
                                        <!-- End Product Card -->
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- End Dashboard Inner -->
                    </div>
                </div>

            </div>
        </div>
    </section>
    <!-- End crancy Dashboard -->
@endsection

@push('js_section')

    <script>
        "use strict";

        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function(){
                var output = document.getElementById('view_img');
                output.src = reader.result;
            }

            reader.readAsDataURL(event.target.files[0]);
        };

        function previewForgroundImage(event) {
            var reader = new FileReader();
            reader.onload = function(){
                var output = document.getElementById('view_forground_img');
                output.src = reader.result;
            }

            reader.readAsDataURL(event.target.files[0]);
        };


    </script>
@endpush

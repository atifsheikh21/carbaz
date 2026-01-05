@extends('admin.master_layout')
@section('title')
    <title>{{ __('translate.Counter') }}</title>
@endsection

@section('body-header')
    <h3 class="crancy-header__title m-0">{{ __('translate.Counter') }}</h3>
    <p class="crancy-header__text">{{ __('translate.Manage Section') }} >> {{ __('translate.Counter') }}</p>
@endsection

@section('body-content')

    <!-- crancy Dashboard -->
    <section class="crancy-adashboard crancy-show language_box">
        <div class="container container__bscreen">
            <div class="row">
                <div class="col-12">
                    <div class="crancy-body">
                        <!-- Dashboard Inner -->
                        <div class="crancy-dsinner">
                            <div class="row">
                                <div class="col-12 mg-top-30">
                                    <!-- Product Card -->
                                    <div class="crancy-product-card translation_main_box">

                                        <div class="crancy-customer-filter">
                                            <div class="crancy-customer-filter__single crancy-customer-filter__single--csearch">
                                                <div class="crancy-header__form crancy-header__form--customer">
                                                    <h4 class="crancy-product-card__title">{{ __('translate.Switch to language translation') }}</h4>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="translation_box">
                                            <ul >
                                                @foreach ($language_list as $language)
                                                <li><a href="{{ route('admin.counter', ['lang_code' => $language->lang_code] ) }}">
                                                    @if (request()->get('lang_code') == $language->lang_code)
                                                        <i class="fas fa-eye"></i>
                                                    @else
                                                        <i class="fas fa-edit"></i>
                                                    @endif

                                                    {{ $language->lang_name }}</a></li>
                                                @endforeach
                                            </ul>

                                            <div class="alert alert-secondary" role="alert">

                                                @php
                                                    $edited_language = $language_list->where('lang_code', request()->get('lang_code'))->first();
                                                @endphp

                                            <p>{{ __('translate.Your editing mode') }} : <b>{{ $edited_language->lang_name }}</b></p>
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
                            <form action="{{ route('admin.update-counter') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                @method('PUT')

                                <input type="hidden" name="lang_code" value="{{ request()->get('lang_code') }}">
                                <input type="hidden" name="translate_id" value="{{ $translate->id }}">

                                <div class="row">
                                    <div class="col-12 mg-top-30">
                                        <!-- Product Card -->
                                        <div class="crancy-product-card">
                                            <div class="row">
                                                <div class="col-md-6 mg-top-form-20">
                                                    @if (admin_lang() == request()->get('lang_code'))

                                                        <div class="crancy__item-form--group mg-top-form-20">
                                                            <label class="crancy__item-label">{{ __('translate.Quantity one') }} * 
                                                                <i 
                                                                    class="fas fa-info-circle text-info"
                                                                    data-toggle="tooltip"
                                                                    data-placement="right"
                                                                    title="Recommended size: 1440x585"
                                                                    style="cursor: pointer;"
                                                                ></i>
                                                            </label>
                                                            <input class="crancy__item-input" type="number" name="counter_qty1" id="counter_qty1" value="{{ $counter->counter_qty1 }}">
                                                        </div>

                                                    @endif


                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{ __('translate.Title one') }} * </label>
                                                        <input class="crancy__item-input" type="text" name="counter_title1" id="counter_title1" value="{{ $translate->counter_title1 }}">
                                                    </div>

                                                </div>

                                                <div class="col-md-6 mg-top-form-20">
                                                    @if (admin_lang() == request()->get('lang_code'))

                                                        <div class="crancy__item-form--group mg-top-form-20">
                                                            <label class="crancy__item-label">{{ __('translate.Quantity two') }} * </label>
                                                            <input class="crancy__item-input" type="number" name="counter_qty2" id="counter_qty2" value="{{ $counter->counter_qty2 }}">
                                                        </div>

                                                    @endif


                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{ __('translate.Title two') }} * </label>
                                                        <input class="crancy__item-input" type="text" name="counter_title2" id="counter_title2" value="{{ $translate->counter_title2 }}">
                                                    </div>

                                                </div>

                                                <div class="col-md-6 mg-top-form-20">
                                                    @if (admin_lang() == request()->get('lang_code'))

                                                        <div class="crancy__item-form--group mg-top-form-20">
                                                            <label class="crancy__item-label">{{ __('translate.Quantity three') }} * </label>
                                                            <input class="crancy__item-input" type="number" name="counter_qty3" id="counter_qty3" value="{{ $counter->counter_qty3 }}">
                                                        </div>

                                                    @endif


                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{ __('translate.Title three') }} * </label>
                                                        <input class="crancy__item-input" type="text" name="counter_title3" id="counter_title3" value="{{ $translate->counter_title3 }}">
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

        function previewImage2(event) {
            var reader = new FileReader();
            reader.onload = function(){
                var output = document.getElementById('view_img2');
                output.src = reader.result;
            }

            reader.readAsDataURL(event.target.files[0]);
        };

        function previewImage3(event) {
            var reader = new FileReader();
            reader.onload = function(){
                var output = document.getElementById('view_img3');
                output.src = reader.result;
            }

            reader.readAsDataURL(event.target.files[0]);
        };

    </script>
@endpush

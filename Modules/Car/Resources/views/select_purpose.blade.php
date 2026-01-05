@extends('admin.master_layout')
@section('title')
    <title>{{ __('translate.Select Purpose') }}</title>
@endsection

@section('body-header')
    <h3 class="crancy-header__title m-0">{{ __('translate.Select Purpose') }}</h3>
    <p class="crancy-header__text">{{ __('translate.Manage Car') }} >> {{ __('translate.Select Purpose') }}</p>
@endsection

@section('body-content')

<section class="crancy-adashboard crancy-show">
    <div class="container container__bscreen">
        <div class="row row__bscreen">
            <div class="col-12">
                <div class="crancy-body">
                    <!-- Dashboard Inner -->
                    <div class="crancy-dsinner">
                        <div class="row">
                            <div class="col-xxl-3 col-lg-4 col-md-6 col-12">
                                <!-- Single Integration -->
                                <div class="crancy-single-integration mg-top-30">
                                    <div class="crancy-single-integration__content">
                                        <p>{{ __('translate.List your car parts for sale. Click the button below to create a new car part listing.') }}</p>
                                        <a href="{{ route('admin.car-part.create') }}" class="crancy-btn crancy-btn__regular crancy-btn__regular--connect">{{ __('translate.Sell Car Parts') }}</a>
                                    </div>
                                </div>
                                <!-- End Single Integration -->
                            </div>

                            <div class="col-xxl-3 col-lg-4 col-md-6 col-12">
                                <!-- Single Integration -->
                                <div class="crancy-single-integration mg-top-30">

                                    <div class="crancy-single-integration__content">
                                        <p>{{ __('translate.Do you have a car sales business? Click the button below to add new sales car information and get a new client') }}</p>
                                        <a href="{{ route('admin.car.create', ['purpose' => 'Sale']) }}" class="crancy-btn crancy-btn__regular">{{ __('translate.For Sale') }}</a>
                                    </div>
                                </div>
                                <!-- End Single Integration -->
                            </div>
                        </div>
                    </div>
                    <!-- End Dashboard Inner -->
                </div>
            </div>


        </div>
    </div>
</section>
@endsection

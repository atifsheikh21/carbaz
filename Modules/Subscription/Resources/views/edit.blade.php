@extends('admin.master_layout')
@section('title')
    <title>{{ __('translate.Edit Plan') }}</title>
@endsection

@section('body-header')
    <h3 class="crancy-header__title m-0">{{ __('translate.Edit Plan') }}</h3>
    <p class="crancy-header__text">{{ __('translate.Subscription Plan') }} >> {{ __('translate.Edit Plan') }}</p>
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
                            <form action="{{ route('admin.subscription-plan.update', $plan->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <div class="col-12 mg-top-30">
                                        <!-- Product Card -->
                                        <div class="crancy-product-card">
                                            <div class="create_new_btn_inline_box">
                                                <h4 class="crancy-product-card__title">{{ __('translate.Edit Plan') }}</h4>

                                                <a href="{{ route('admin.subscription-plan.index') }}" class="crancy-btn "><i class="fa fa-list"></i> {{ __('translate.Plan List') }}</a>
                                            </div>

                                            <div class="row mg-top-30">

                                                <div class="col-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{ __('translate.Plan Name') }} * </label>
                                                        <input class="crancy__item-input" type="text" name="plan_name" id="plan_name" value="{{ $plan->plan_name }}">
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{ __('translate.Plan Price') }} <span data-toggle="tooltip" data-placement="top" class="fa fa-info-circle text--primary" title="For free plan use(0.00), price should be USD"> * </label>
                                                        <input class="crancy__item-input" type="text" name="plan_price" id="plan_price" value="{{ $plan->plan_price }}">
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{ __('translate.Expiration Date') }} * </label>
                                                        <select class="form-select crancy__item-input" name="expiration_date">
                                                            <option {{ $plan->expiration_date == 'monthly' ? 'selected' : '' }} value="monthly">{{ __('translate.Monthly') }}</option>
                                                            <option {{ $plan->expiration_date == 'yearly' ? 'selected' : '' }} value="yearly">{{ __('translate.Yearly') }}</option>
                                                            <option {{ $plan->expiration_date == 'lifetime' ? 'selected' : '' }} value="lifetime">{{ __('translate.Lifetime') }}</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{ __('translate.Maximum Car') }} * </label>
                                                        <input class="crancy__item-input" type="number" name="max_car" id="max_car" value="{{ $plan->max_car }}">
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{ __('translate.Featured Car') }} * </label>
                                                        <input class="crancy__item-input" type="number" name="featured_car" id="featured_car" value="{{ $plan->featured_car }}">
                                                    </div>
                                                </div>


                                                <div class="col-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{ __('translate.Serial') }} * </label>
                                                        <input class="crancy__item-input" type="number" name="serial" id="serial" value="{{ $plan->serial }}">
                                                    </div>
                                                </div>



                                                <div class="col-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{__('Visibility Status')}} </label>
                                                        <div class="crancy-ptabs__notify-switch  crancy-ptabs__notify-switch--two">
                                                            <label class="crancy__item-switch">
                                                            <input {{ $plan->status == 'active' ? 'checked' : '' }} name="status" type="checkbox" >
                                                            <span class="crancy__item-switch--slide crancy__item-switch--round"></span>
                                                            </label>
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

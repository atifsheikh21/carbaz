@extends('admin.master_layout')
@section('title')
    <title>{{ __('translate.Ads Banner') }}</title>
@endsection

@section('body-header')
    <h3 class="crancy-header__title m-0">{{ __('translate.Ads Banner') }}</h3>
    <p class="crancy-header__text">{{ __('translate.Dashboard') }} >> {{ __('translate.Ads Banner') }}</p>
@endsection

@section('body-content')


<section class="crancy-adashboard crancy-show">
    <div class="container container__bscreen">
        <div class="row row__bscreen">
            <div class="col-12">
                <div class="crancy-body">
                    <!-- Dashboard Inner -->
                    <div class="crancy-dsinner">
                        <div class="crancy-personals mg-top-30">
                            <div class="row">
                                <div class="col-lg-3 col-md-2 col-12 crancy-personals__list">
                                    <div class="crancy-psidebar">
                                        <!-- Features Tab List -->
                                        <div class="list-group crancy-psidebar__list" id="list-tab" role="tablist">

                                            @foreach ($ads_banner as $index => $ads_item)
                                            <a class="list-group-item {{ $index == 0  ? 'active' : '' }}" data-bs-toggle="list" href="#id1{{ $ads_item->id }}" role="tab" aria-selected="{{ $index == 0  ? 'true' : 'false' }}">
                                                <span class="crancy-psidebar__icon">
                                                    <i class="fas fa-list    "></i>
                                                </span>
                                                <h4 class="crancy-psidebar__title">{{ $ads_item->position }}</h4>
                                            </a>
                                            @endforeach

                                        </div>

                                    </div>
                                </div>

                                <div class="col-lg-9 col-md-10 col-12  crancy-personals__content">
                                    <div class="crancy-ptabs">

                                        <div class="crancy-ptabs__inner">
                                            <div class="tab-content" id="nav-tabContent">
                                                <!--  Features Single Tab -->
                                                @foreach ($ads_banner as $index => $ads_item)
                                                <div class="tab-pane fade {{ $index == 0  ? 'active show' : '' }}" id="id1{{ $ads_item->id }}" role="tabpanel">
                                                    <form action="{{ route('admin.ads-banner-update',$ads_item->id) }}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="crancy-ptabs__separate">
                                                                    <div class="crancy-ptabs__form-main">
                                                                        <div class="crancy__item-group">
                                                                            <h3 class="crancy__item-group__title">{{ $ads_item->position }}
                                                                                @if($ads_item->position == 'Home-1 featured car side bar')
                                                                                <i 
                                                                                    class="fas fa-info-circle text-info"
                                                                                    data-toggle="tooltip"
                                                                                    data-placement="right"
                                                                                    title="Recommended size: 693x459"
                                                                                    style="cursor: pointer;"
                                                                                ></i>
                                                                                @elseif ($ads_item->position == 'Home-2 brand side bar')
                                                                                <i 
                                                                                    class="fas fa-info-circle text-info"
                                                                                    data-toggle="tooltip"
                                                                                    data-placement="right"
                                                                                    title="Recommended size: 389x557"
                                                                                    style="cursor: pointer;"
                                                                                ></i>
                                                                                @elseif ($ads_item->position == 'Home-3 featured side bar')
                                                                                <i 
                                                                                    class="fas fa-info-circle text-info"
                                                                                    data-toggle="tooltip"
                                                                                    data-placement="right"
                                                                                    title="Recommended size: 342x625"
                                                                                    style="cursor: pointer;"
                                                                                ></i>
                                                                                @elseif ($ads_item->position == 'Listing page sidebar')
                                                                                <i 
                                                                                    class="fas fa-info-circle text-info"
                                                                                    data-toggle="tooltip"
                                                                                    data-placement="right"
                                                                                    title="Recommended size: 336x525"
                                                                                    style="cursor: pointer;"
                                                                                ></i>
                                                                                @elseif ($ads_item->position == 'Listing detail page banner')
                                                                                <i 
                                                                                    class="fas fa-info-circle text-info"
                                                                                    data-toggle="tooltip"
                                                                                    data-placement="right"
                                                                                    title="Recommended size: 400x525"
                                                                                    style="cursor: pointer;"
                                                                                ></i>
                                                                                @elseif ($ads_item->position == 'Dealer detail page banner')
                                                                                <i 
                                                                                    class="fas fa-info-circle text-info"
                                                                                    data-toggle="tooltip"
                                                                                    data-placement="right"
                                                                                    title="Recommended size: 400x525"
                                                                                    style="cursor: pointer;"
                                                                                ></i>
                                                                                @endif
                                                                            </h3>
                                                                            <div class="crancy__item-form--group">
                                                                                <div class="row">

                                                                                    <div class="col-12">
                                                                                        <div class="row">
                                                                                            <div class="col-md-4">
                                                                                                <div class="crancy__item-form--group w-100 h-100">

                                                                                                    <div class="crancy-product-card__upload crancy-product-card__upload--border">
                                                                                                        <input type="file" class="btn-check" name="image" id="input-img{{ $index }}" autocomplete="off" onchange="previewImage(event, '{{ $index }}')">
                                                                                                        <label class="crancy-image-video-upload__label" for="input-img{{ $index }}">

                                                                                                        @if($ads_item->position == 'Home-1 featured car side bar')

                                                                                                            <img id="view_img{{ $index }}" src="{{ getImageOrPlaceholder($ads_item->imag, '693x459') }}">

                                                                                                            @elseif ($ads_item->position == 'Home-2 brand side bar')
                                                                                                            
                                                                                                             <img id="view_img{{ $index }}" src="{{ getImageOrPlaceholder($ads_item->imag, '389x557') }}">

                                                                                                            @elseif ($ads_item->position == 'Home-3 featured side bar')

                                                                                                             <img id="view_img{{ $index }}" src="{{ getImageOrPlaceholder($ads_item->imag, '342x625') }}">

                                                                                                            @elseif ($ads_item->position == 'Listing page sidebar')

                                                                                                            <img id="view_img{{ $index }}" src="{{ getImageOrPlaceholder($ads_item->imag, '336x525') }}">

                                                                                                            @elseif ($ads_item->position == 'Listing detail page banner')

                                                                                                            <img id="view_img{{ $index }}" src="{{ getImageOrPlaceholder($ads_item->imag, '400x525') }}">

                                                                                                            @elseif ($ads_item->position == 'Dealer detail page banner')

                                                                                                            <img id="view_img{{ $index }}" src="{{ getImageOrPlaceholder($ads_item->imag, '400x525') }}">

                                                                                                            @endif

                                                                                                            <h4 class="crancy-image-video-upload__title">{{ __('translate.Click here to') }} <span class="crancy-primary-color">{{ __('translate.Choose File') }}</span> {{ __('translate.and upload') }} </h4>
                                                                                                        </label>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                    </div>

                                                                                    <div class="col-12">
                                                                                        <div class="crancy__item-form--group mg-top-form-20">
                                                                                            <label class="crancy__item-label">{{ __('translate.Link') }} * </label>
                                                                                            <input class="crancy__item-input" type="text" name="link" id="header" value="{{ $ads_item->link }}">
                                                                                        </div>
                                                                                    </div>

                                                                                    <div class="col-12">
                                                                                        <div class="crancy__item-form--group mg-top-form-20">
                                                                                            <label class="crancy__item-label">{{__('Visibility Status')}} </label>
                                                                                            <div class="crancy-ptabs__notify-switch  crancy-ptabs__notify-switch--two">
                                                                                                <label class="crancy__item-switch">
                                                                                                <input {{ $ads_item->status == 'enable' ? 'checked' : '' }} name="status" type="checkbox" >
                                                                                                <span class="crancy__item-switch--slide crancy__item-switch--round"></span>
                                                                                                </label>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>





                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="mg-top-40">
                                                                            <button class="crancy-btn" type="submit">{{ __('translate.Update') }}</button>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>

                                    </div>
                                </div>
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

@push('js_section')
    <script>

        "use strict"

        function previewImage(event, id) {
            var reader = new FileReader();
            reader.onload = function(){
                var output = document.getElementById(`view_img${id}`);
                output.src = reader.result;
            }

            reader.readAsDataURL(event.target.files[0]);
        };

    </script>
@endpush

@extends('layout')
@section('title')
    <title>{{ $seo_setting->seo_title }}</title>
    <meta name="title" content="{{ $seo_setting->seo_title }}">
    <meta name="description" content="{!! strip_tags(clean($seo_setting->seo_description)) !!}">
@endsection

@section('body-content')

    <main>
        <!-- banner-part-start  -->

        <section class="inner-banner">
        <div class="inner-banner-img" style=" background-image: url({{ getImageOrPlaceholder($breadcrumb,'1905x300') }}) "></div>
            <div class="container">
            <div class="col-lg-12">
            <div class="inner-banner-df">
                    <h1 class="inner-banner-taitel">{{ __('translate.Terms & Conditions') }}</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('translate.Home') }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ __('translate.Terms & Conditions') }}</li>
                        </ol>
                    </nav>
                </div>
                </div>
            </div>
        </section>
        <!-- banner-part-end -->


        <!-- Privacy and Policy-part start  -->

        <section class="privacy">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">

                        <div class="privacy-text-item">
                            {!! clean($terms_condition->description) !!}
                        </div>

                    </div>
                </div>
            </div>
        </section>

        <!-- Privacy and Policy-part end  -->

    </main>

@endsection

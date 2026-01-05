@extends('layout')
@section('title')
    <title>{{ $custom_page->page_name }}</title>
    <meta name="title" content="{{ $custom_page->page_name }}">
    <meta name="description" content="{{ $custom_page->page_name }}">
@endsection

@section('body-content')

    <main>
        <!-- banner-part-start  -->

        <section class="inner-banner">
        <div class="inner-banner-img" style=" background-image: url({{ getImageOrPlaceholder($breadcrumb,'1905x300') }}) "></div>
            <div class="container">
            <div class="col-lg-12">
            <div class="inner-banner-df">
                    <h1 class="inner-banner-taitel">{{ $custom_page->page_name }}</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('translate.Home') }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $custom_page->page_name }}</li>
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
                            {!! clean($custom_page->description) !!}
                        </div>

                    </div>
                </div>
            </div>
        </section>
        <!-- Privacy and Policy-part end  -->

    </main>

@endsection

@extends('layout')
@section('title')
    <title>{{ __('translate.Sell Car Parts') }}</title>
@endsection

@section('body-content')
<main>
    <section class="inner-banner">
        <div class="inner-banner-img" style=" background-image: url({{ getImageOrPlaceholder($breadcrumb,'1905x300') }}) "></div>
        <div class="container">
            <div class="col-lg-12">
                <div class="inner-banner-df">
                    <h1 class="inner-banner-taitel">{{ __('translate.Sell Car Parts') }}</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('translate.Home') }}</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('user.car-part.index') }}">{{ __('translate.Car Parts') }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ __('translate.Create') }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>

    <section class="dashboard">
        <div class="container">
            <div class="row">
                @include('profile.sidebar')

                <div class="col-lg-9">
                    <form action="{{ route('user.car-part.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="car-images">
                            <h3 class="car-images-taitel">{{ __('translate.Basic Information') }}</h3>
                            <div class="car-images-inner">
                                <div class="description-item">
                                    <div class="description-item-inner">
                                        <label class="form-label">{{ __('translate.Title') }} <span>*</span></label>
                                        <input type="text" class="form-control" name="title" value="{{ old('title') }}" required>
                                    </div>
                                </div>

                                <div class="description-item two">
                                    <div class="description-item-inner">
                                        <label class="form-label">{{ __('translate.Slug') }} <span>*</span></label>
                                        <input type="text" class="form-control" name="slug" value="{{ old('slug') }}" required>
                                    </div>
                                    <div class="description-item-inner">
                                        <label class="form-label">{{ __('translate.Brand') }}</label>
                                        <select class="form-select select2" name="brand_id">
                                            <option value="">{{ __('translate.Select Brand') }}</option>
                                            @foreach($brands as $b)
                                                <option value="{{ $b->id }}">{{ $b->translate?->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="description-item two">
                                    <div class="description-item-inner">
                                        <label class="form-label">{{ __('translate.Condition') }} <span>*</span></label>
                                        <select class="form-select" name="condition" required>
                                            <option value="Used">{{ __('translate.Used') }}</option>
                                            <option value="New">{{ __('translate.New') }}</option>
                                        </select>
                                    </div>
                                    <div class="description-item-inner">
                                        <label class="form-label">{{ __('translate.Part Number') }}</label>
                                        <input type="text" class="form-control" name="part_number" value="{{ old('part_number') }}">
                                    </div>
                                </div>

                                <div class="description-item two">
                                    <div class="description-item-inner">
                                        <label class="form-label">{{ __('translate.Compatibility') }}</label>
                                        <input type="text" class="form-control" name="compatibility" value="{{ old('compatibility') }}">
                                    </div>
                                    <div class="description-item-inner">
                                        <label class="form-label">{{ __('translate.Thumbnail Image') }}</label>
                                        <input type="file" class="form-control" name="thumb_image">
                                    </div>
                                </div>

                                <div class="description-item two">
                                    <div class="description-item-inner">
                                        <label class="form-label">{{ __('translate.Regular Price') }} <span>*</span></label>
                                        <input type="text" class="form-control" name="regular_price" value="{{ old('regular_price') }}" required>
                                    </div>
                                    <div class="description-item-inner">
                                        <label class="form-label">{{ __('translate.Offer Price') }}</label>
                                        <input type="text" class="form-control" name="offer_price" value="{{ old('offer_price') }}">
                                    </div>
                                </div>

                                <div class="description-item">
                                    <div class="description-item-inner" style="width:100%">
                                        <label class="form-label">{{ __('translate.Description') }} <span>*</span></label>
                                        <textarea class="form-control" name="description" rows="5" required>{{ old('description') }}</textarea>
                                    </div>
                                </div>

                                <div class="description-item two">
                                    <div class="description-item-inner">
                                        <label class="form-label">{{ __('translate.SEO Title') }}</label>
                                        <input type="text" class="form-control" name="seo_title" value="{{ old('seo_title') }}">
                                    </div>
                                    <div class="description-item-inner">
                                        <label class="form-label">{{ __('translate.SEO Description') }}</label>
                                        <input type="text" class="form-control" name="seo_description" value="{{ old('seo_description') }}">
                                    </div>
                                </div>

                                <div class="text-end" style="margin-top:12px;">
                                    <button type="submit" class="thm-btn-two">{{ __('translate.Save') }}</button>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

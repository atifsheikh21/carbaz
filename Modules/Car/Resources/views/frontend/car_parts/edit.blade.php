@extends('layout')
@section('title')
    <title>{{ __('translate.Edit Car Part') }}</title>
@endsection

@section('body-content')
<main>
    <section class="inner-banner">
        <div class="inner-banner-img" style=" background-image: url({{ getImageOrPlaceholder($breadcrumb,'1905x300') }}) "></div>
        <div class="container">
            <div class="col-lg-12">
                <div class="inner-banner-df">
                    <h1 class="inner-banner-taitel">{{ __('translate.Edit Car Part') }}</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('translate.Home') }}</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('user.car-part.index') }}">{{ __('translate.Car Parts') }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ __('translate.Edit') }}</li>
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
                    <form action="{{ route('user.car-part.update', $carPart->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="car-images">
                            <h3 class="car-images-taitel">{{ __('translate.Basic Information') }}</h3>
                            <div class="car-images-inner">
                                <div class="description-item">
                                    <div class="description-item-inner" style="width:100%">
                                        <label class="form-label">{{ __('translate.Title') }} <span>*</span></label>
                                        <input type="text" class="form-control" name="title" value="{{ old('title', $translation?->title) }}" required>
                                    </div>
                                </div>

                                <div class="description-item two">
                                    <div class="description-item-inner">
                                        <label class="form-label">{{ __('translate.Slug') }} <span>*</span></label>
                                        <input type="text" class="form-control" name="slug" value="{{ old('slug', $carPart->slug) }}" required>
                                    </div>
                                    <div class="description-item-inner">
                                        <label class="form-label">{{ __('translate.Brand') }}</label>
                                        <select class="form-select select2" name="brand_id">
                                            <option value="">{{ __('translate.Select Brand') }}</option>
                                            @foreach($brands as $b)
                                                <option value="{{ $b->id }}" {{ (int) old('brand_id', $carPart->brand_id) === (int) $b->id ? 'selected' : '' }}>{{ $b->translate?->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="description-item two">
                                    <div class="description-item-inner">
                                        <label class="form-label">{{ __('translate.Condition') }} <span>*</span></label>
                                        <select class="form-select" name="condition" required>
                                            <option value="Used" {{ old('condition', $carPart->condition) == 'Used' ? 'selected' : '' }}>{{ __('translate.Used') }}</option>
                                            <option value="New" {{ old('condition', $carPart->condition) == 'New' ? 'selected' : '' }}>{{ __('translate.New') }}</option>
                                        </select>
                                    </div>
                                    <div class="description-item-inner">
                                        <label class="form-label">{{ __('translate.Part Number') }}</label>
                                        <input type="text" class="form-control" name="part_number" value="{{ old('part_number', $carPart->part_number) }}">
                                    </div>
                                </div>

                                <div class="description-item two">
                                    <div class="description-item-inner">
                                        <label class="form-label">{{ __('translate.Compatibility') }}</label>
                                        <input type="text" class="form-control" name="compatibility" value="{{ old('compatibility', $carPart->compatibility) }}">
                                    </div>
                                    <div class="description-item-inner">
                                        <label class="form-label">{{ __('translate.Thumbnail Image') }}</label>
                                        <input type="file" class="form-control" name="thumb_image">
                                    </div>
                                </div>

                                <div class="description-item two">
                                    <div class="description-item-inner">
                                        <label class="form-label">{{ __('translate.Regular Price') }} <span>*</span></label>
                                        <input type="text" class="form-control" name="regular_price" value="{{ old('regular_price', $carPart->regular_price) }}" required>
                                    </div>
                                    <div class="description-item-inner">
                                        <label class="form-label">{{ __('translate.Offer Price') }}</label>
                                        <input type="text" class="form-control" name="offer_price" value="{{ old('offer_price', $carPart->offer_price) }}">
                                    </div>
                                </div>

                                <div class="description-item">
                                    <div class="description-item-inner" style="width:100%">
                                        <label class="form-label">{{ __('translate.Description') }} <span>*</span></label>
                                        <textarea class="form-control" name="description" rows="5" required>{{ old('description', $translation?->description) }}</textarea>
                                    </div>
                                </div>

                                <div class="text-end" style="margin-top:12px;">
                                    <button type="submit" class="thm-btn-two">{{ __('translate.Update') }}</button>
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

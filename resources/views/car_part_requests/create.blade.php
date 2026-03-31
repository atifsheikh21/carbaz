@extends('layout')
@section('title')
    <title>{{ __('translate.Create Request') }}</title>
@endsection

@section('body-content')
<main>
    <section class="inner-banner">
        <div class="container">
            <div class="col-lg-12">
                <div class="inner-banner-df">
                    <h1 class="inner-banner-taitel">{{ __('translate.Create Request') }}</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('translate.Home') }}</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('car-part-requests.index') }}">{{ __('translate.Forum') }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ __('translate.Create Request') }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>

    <section class="brand-car brand-car-two py-120px forum-feed">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="brand-car-item shadow-sm rounded-3 forum-card forum-composer">
                        <div class="forum-composer__header">
                            <h3 class="forum-composer__title">{{ __('translate.Create Request') }}</h3>
                        </div>

                        <form method="POST" action="{{ route('car-part-requests.store') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="forum-composer__body">
                                <div class="form-group forum-composer__group">
                                    <label class="forum-composer__label">{{ __('translate.Title') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="title" class="form-control" placeholder="{{ __('translate.Enter discussion title') }}" value="{{ old('title') }}">
                                </div>

                                <div class="form-group forum-composer__group">
                                    <label class="forum-composer__label">{{ __('translate.Content') }} <span class="text-danger">*</span></label>
                                    <textarea name="part_description" class="form-control" rows="8" placeholder="{{ __('translate.Write your discussion content...') }}">{{ old('part_description') }}</textarea>
                                </div>

                                <div class="forum-composer__row">
                                    <div class="form-group forum-composer__group">
                                        <label class="forum-composer__label">{{ __('translate.Car Make') }}</label>
                                        <input type="text" name="car_make" class="form-control" value="{{ old('car_make') }}">
                                    </div>
                                    <div class="form-group forum-composer__group">
                                        <label class="forum-composer__label">{{ __('translate.Car Model') }}</label>
                                        <input type="text" name="car_model" class="form-control" value="{{ old('car_model') }}">
                                    </div>
                                    <div class="form-group forum-composer__group">
                                        <label class="forum-composer__label">{{ __('translate.Car Year') }}</label>
                                        <input type="text" name="car_year" class="form-control" value="{{ old('car_year') }}">
                                    </div>
                                </div>

                                <div class="form-group forum-composer__group">
                                    <label class="forum-composer__label">{{ __('translate.Additional Notes') }}</label>
                                    <textarea name="additional_notes" class="form-control" rows="4">{{ old('additional_notes') }}</textarea>
                                </div>

                                <div class="form-group forum-composer__group">
                                    <label class="forum-composer__label">{{ __('translate.Image') }} ({{ __('translate.Optional') }})</label>
                                    <input type="file" name="image" class="form-control" accept="image/*">
                                </div>
                            </div>

                            <div class="forum-composer__footer">
                                <a class="btn btn-light" href="{{ route('car-part-requests.index') }}">{{ __('translate.Cancel') }}</a>
                                <button type="submit" class="btn btn-primary">{{ __('translate.Create Post') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

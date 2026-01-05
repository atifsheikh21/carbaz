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

    <section class="brand-car brand-car-two py-120px">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="brand-car-item p-4 shadow-sm rounded-3">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <h3 class="mb-0">{{ __('translate.Create Request') }}</h3>
                            <a class="thm-btn-two" href="{{ route('car-part-requests.index') }}">{{ __('translate.Back') }}</a>
                        </div>

                        <form method="POST" action="{{ route('car-part-requests.store') }}">
                            @csrf

                            <div class="form-group mb-3">
                                <label>{{ __('translate.Title') }}</label>
                                <input type="text" name="title" class="form-control" value="{{ old('title') }}">
                            </div>

                            <div class="form-group mb-3">
                                <label>{{ __('translate.Part Description') }}</label>
                                <textarea name="part_description" class="form-control" rows="5">{{ old('part_description') }}</textarea>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label>{{ __('translate.Car Make') }}</label>
                                        <input type="text" name="car_make" class="form-control" value="{{ old('car_make') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label>{{ __('translate.Car Model') }}</label>
                                        <input type="text" name="car_model" class="form-control" value="{{ old('car_model') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label>{{ __('translate.Car Year') }}</label>
                                        <input type="text" name="car_year" class="form-control" value="{{ old('car_year') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-4">
                                <label>{{ __('translate.Additional Notes') }}</label>
                                <textarea name="additional_notes" class="form-control" rows="4">{{ old('additional_notes') }}</textarea>
                            </div>

                            <button type="submit" class="thm-btn-two">{{ __('translate.Submit') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

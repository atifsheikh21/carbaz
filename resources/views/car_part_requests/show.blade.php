@extends('layout')
@section('title')
    <title>{{ $request->title }}</title>
@endsection

@section('body-content')
<main>
    <section class="inner-banner">
        <div class="container">
            <div class="col-lg-12">
                <div class="inner-banner-df">
                    <h1 class="inner-banner-taitel">{{ $request->title }}</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('translate.Home') }}</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('car-part-requests.index') }}">{{ __('translate.Forum') }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ __('translate.Request Details') }}</li>
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
                    @php
                        $status = (string) $request->status;
                        $statusClass = 'bg-secondary';
                        if (strtolower($status) === 'open') {
                            $statusClass = 'bg-success';
                        }
                        if (strtolower($status) === 'closed') {
                            $statusClass = 'bg-dark';
                        }
                    @endphp

                    <div class="brand-car-item p-4 shadow-sm rounded-3 mb-4">
                        <div class="brand-car-inner">
                            <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-3">
                                <div>
                                    <div class="d-flex flex-wrap align-items-center gap-2">
                                        <strong>{{ $request->user?->name }}</strong>
                                        <span class="text-muted">{{ $request->created_at?->format('d M, Y') }}</span>
                                    </div>
                                    <div class="mt-2">
                                        <span class="badge {{ $statusClass }}">{{ $status }}</span>
                                    </div>
                                </div>

                                <a href="{{ route('car-part-requests.index') }}" class="thm-btn-two">{{ __('translate.Back') }}</a>
                            </div>

                            <h3 class="mb-2">{{ __('translate.Part Description') }}</h3>
                            <p class="text-muted mb-4">{{ $request->part_description }}</p>

                            <div class="row g-3 mb-3">
                                <div class="col-md-4">
                                    <div class="p-3 border rounded-3 h-100">
                                        <div class="text-muted">{{ __('translate.Car Make') }}</div>
                                        <div class="fw-semibold">{{ $request->car_make ?? '-' }}</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 border rounded-3 h-100">
                                        <div class="text-muted">{{ __('translate.Car Model') }}</div>
                                        <div class="fw-semibold">{{ $request->car_model ?? '-' }}</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 border rounded-3 h-100">
                                        <div class="text-muted">{{ __('translate.Car Year') }}</div>
                                        <div class="fw-semibold">{{ $request->car_year ?? '-' }}</div>
                                    </div>
                                </div>
                            </div>

                            @if ($request->additional_notes)
                                <h3 class="mb-2">{{ __('translate.Additional Notes') }}</h3>
                                <p class="text-muted mb-0">{{ $request->additional_notes }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="brand-car-item p-4 shadow-sm rounded-3 mb-4">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <h3 class="mb-0">{{ __('translate.Replies') }}</h3>
                            <span class="text-muted">{{ $request->replies->count() }}</span>
                        </div>

                        @forelse ($request->replies as $reply)
                            <div class="p-3 border rounded-3 mb-3">
                                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                                    <strong>{{ $reply->user?->name }}</strong>
                                    <span class="text-muted">{{ $reply->created_at?->format('d M, Y') }}</span>
                                </div>

                                <p class="mb-0 text-muted mt-2">{{ $reply->message }}</p>

                                @if (!is_null($reply->offer_price))
                                    <div class="mt-2">
                                        <span class="badge bg-primary">{{ __('translate.Offer Price') }}: {{ currency($reply->offer_price) }}</span>
                                    </div>
                                @endif
                            </div>
                        @empty
                            <p class="mb-0">{{ __('translate.No replies yet') }}</p>
                        @endforelse
                    </div>

                    <div class="brand-car-item p-4 shadow-sm rounded-3">
                        <h3 class="mb-3">{{ __('translate.Reply') }}</h3>
                        <form method="POST" action="{{ route('car-part-requests.reply', $request->id) }}">
                            @csrf

                            <div class="form-group mb-3">
                                <label>{{ __('translate.Message') }}</label>
                                <textarea name="message" class="form-control" rows="4">{{ old('message') }}</textarea>
                            </div>

                            <div class="form-group mb-3">
                                <label>{{ __('translate.Offer Price') }} ({{ __('translate.Optional') }})</label>
                                <input type="number" step="0.01" name="offer_price" class="form-control" value="{{ old('offer_price') }}">
                            </div>

                            <button type="submit" class="thm-btn-two">{{ __('translate.Submit Reply') }}</button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </section>
</main>
@endsection

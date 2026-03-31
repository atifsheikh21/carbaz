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

    <section class="brand-car brand-car-two py-120px forum-feed">
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

                    <div class="forum-thread__topbar mb-3">
                        <a href="{{ route('car-part-requests.index') }}" class="forum-thread__back">&larr; {{ __('translate.Go Back') }}</a>
                        <a href="{{ route('car-part-requests.create') }}" class="thm-btn-two">{{ __('translate.New Post') }}</a>
                    </div>

                    <div class="brand-car-item p-4 shadow-sm rounded-3 mb-4 forum-card forum-thread">
                        <div class="forum-thread__row">
                            <div class="forum-thread__side">
                                <div class="forum-thread__avatar">
                                    <img src="{{ getImageOrPlaceholder($request->user?->image, '96x96') }}" alt="User" />
                                </div>
                                <div class="forum-thread__user">
                                    <div class="forum-thread__username">{{ $request->user?->name }}</div>
                                    <div class="forum-thread__meta">{{ __('translate.Category') }}: {{ __('translate.Car Part Requests') }}</div>
                                </div>
                                <span class="badge {{ $statusClass }} forum-thread__status">{{ $status }}</span>
                            </div>

                            <div class="forum-thread__content">
                                <div class="forum-thread__content-top">
                                    <a class="forum-thread__title" href="{{ route('car-part-requests.show', $request->id) }}">{{ $request->title }}</a>
                                    <div class="forum-thread__time">{{ $request->created_at?->format('l, d F Y h:i A') }}</div>
                                </div>

                                <div class="forum-thread__text">{{ $request->part_description }}</div>

                                @if (!empty($request->image))
                                    <div class="forum-thread__media">
                                        <img src="{{ getImageOrPlaceholder($request->image, '900x700') }}" alt="Request image" />
                                    </div>
                                @endif

                                <div class="forum-thread__tags">
                                    @if (!empty($request->car_make))
                                        <span class="forum-thread__tag">{{ $request->car_make }}</span>
                                    @endif
                                    @if (!empty($request->car_model))
                                        <span class="forum-thread__tag">{{ $request->car_model }}</span>
                                    @endif
                                    @if (!empty($request->car_year))
                                        <span class="forum-thread__tag">{{ $request->car_year }}</span>
                                    @endif
                                </div>

                                @if ($request->additional_notes)
                                    <div class="forum-thread__notes">{{ $request->additional_notes }}</div>
                                @endif

                                <div class="forum-thread__actions">
                                    <a href="#replies" class="forum-thread__action">{{ __('translate.View') }}</a>
                                    <a href="#reply-form" class="forum-thread__action">{{ __('translate.Reply') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="replies" class="brand-car-item p-4 shadow-sm rounded-3 mb-4 forum-card forum-thread">
                        <div class="forum-thread__count">{{ __('translate.Replies') }}: {{ $request->replies->count() }}</div>

                        @forelse ($request->replies as $reply)
                            <div class="forum-thread__row forum-thread__row--reply">
                                <div class="forum-thread__side">
                                    <div class="forum-thread__avatar">
                                        <img src="{{ getImageOrPlaceholder($reply->user?->image, '96x96') }}" alt="User" />
                                    </div>
                                    <div class="forum-thread__user">
                                        <div class="forum-thread__username">{{ $reply->user?->name }}</div>
                                    </div>
                                </div>

                                <div class="forum-thread__content">
                                    <div class="forum-thread__content-top">
                                        <div class="forum-thread__title">{{ __('translate.Reply') }}</div>
                                        <div class="forum-thread__time">{{ $reply->created_at?->format('l, d F Y h:i A') }}</div>
                                    </div>

                                    <div class="forum-thread__text">{{ $reply->message }}</div>

                                    @if (!is_null($reply->offer_price))
                                        <div class="forum-thread__tags">
                                            <span class="forum-thread__tag">{{ __('translate.Offer Price') }}: {{ currency($reply->offer_price) }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="forum-thread__empty">{{ __('translate.No replies yet') }}</div>
                        @endforelse
                    </div>

                    <div id="reply-form" class="brand-car-item p-4 shadow-sm rounded-3 forum-card">
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

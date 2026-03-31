@extends('layout')
@section('title')
    <title>{{ __('translate.Car Part Requests') }}</title>
@endsection

@section('body-content')
<main>
    <section class="inner-banner">
        <div class="container">
            <div class="col-lg-12">
                <div class="inner-banner-df">
                    <h1 class="inner-banner-taitel">{{ __('translate.Community Forum') }}</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('translate.Home') }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ __('translate.Forum') }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>

    <section class="brand-car brand-car-two py-120px forum-feed">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="brand-car-item p-4 shadow-sm rounded-3 forum-card forum-board">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
                            <h2 class="mb-0 forum-board__title">{{ __('translate.Community Forum') }}</h2>
                            <a class="thm-btn-two" href="{{ route('car-part-requests.create') }}">{{ __('translate.Create Request') }}</a>
                        </div>

                        <form method="GET" action="{{ route('car-part-requests.index') }}" class="forum-board__filters">
                            <div class="row g-3 align-items-center">
                                <div class="col-md-7">
                                    <div class="forum-board__search">
                                        <input type="text" name="q" class="form-control" placeholder="{{ __('translate.Search') }}..." value="{{ $search ?? '' }}">
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <select class="form-control" name="sort" onchange="this.form.submit()">
                                        <option value="latest" {{ ($sort ?? 'latest') === 'latest' ? 'selected' : '' }}>{{ __('translate.Latest') }}</option>
                                        <option value="oldest" {{ ($sort ?? 'latest') === 'oldest' ? 'selected' : '' }}>{{ __('translate.Oldest') }}</option>
                                        <option value="most_replied" {{ ($sort ?? 'latest') === 'most_replied' ? 'selected' : '' }}>{{ __('translate.Most Replied') }}</option>
                                    </select>
                                </div>
                            </div>
                        </form>

                        <div class="forum-board__table mt-3">
                            <div class="forum-board__thead">
                                <div class="forum-board__th">{{ __('translate.Category') }}</div>
                                <div class="forum-board__th text-center">{{ __('translate.Status') }}</div>
                                <div class="forum-board__th text-end">{{ __('translate.Activity') }}</div>
                            </div>

                            @forelse ($requests as $item)
                                @php
                                    $status = (string) $item->status;
                                    $statusClass = 'bg-secondary';
                                    if (strtolower($status) === 'open') {
                                        $statusClass = 'bg-success';
                                    }
                                    if (strtolower($status) === 'closed') {
                                        $statusClass = 'bg-dark';
                                    }

                                    $activityAt = $item->replies_max_created_at ?: $item->created_at;
                                @endphp

                                <a class="forum-board__row" href="{{ route('car-part-requests.show', $item->id) }}">
                                    <div class="forum-board__cell">
                                        <div class="forum-board__category">
                                            <div class="forum-board__icon"></div>
                                            <div class="forum-board__cat-text">
                                                <div class="forum-board__cat-title">{{ $item->title }}</div>
                                                <div class="forum-board__cat-desc">{{ \Illuminate\Support\Str::limit($item->part_description, 90) }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="forum-board__cell text-center">
                                        <span class="badge {{ $statusClass }} forum-board__status">{{ $status }}</span>
                                    </div>
                                    <div class="forum-board__cell text-end">
                                        <div class="forum-board__activity">{{ $activityAt?->diffForHumans() }}</div>
                                        <div class="forum-board__activity-sub">{{ __('translate.Replies') }}: {{ $item->replies_count }}</div>
                                    </div>
                                </a>
                            @empty
                                <div class="forum-board__empty">{{ __('translate.No Item Found') }}</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-60px">
                <div class="col-12">
                    {{ $requests->links('pagination_box') }}
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

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
                    <h1 class="inner-banner-taitel">{{ __('translate.Car Part Requests') }}</h1>
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

    <section class="brand-car brand-car-two py-120px">
        <div class="container">
            <div class="row align-items-end">
                <div class="col-lg-6 col-sm-6 col-md-6">
                    <div class="taitel two">
                        <div class="taitel-img">
                            <span>
                                <svg width="188" height="6" viewBox="0 0 188 6" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M1 5C26.4245 1.98151 99.2187 -2.24439 187 5" stroke="#405FF2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </span>
                        </div>
                        <span>{{ __('translate.Forum') }}</span>
                    </div>
                    <h2>{{ __('translate.Request a Car Part') }}</h2>
                </div>
                <div class="col-lg-6 col-sm-6 col-md-6 text-end">
                    <a class="thm-btn-two" href="{{ route('car-part-requests.create') }}">{{ __('translate.Create Request') }}</a>
                </div>
            </div>

            <div class="row mt-60px g-4">
                @forelse ($requests as $item)
                    <div class="col-lg-12">
                        <div class="brand-car-item p-4 shadow-sm rounded-3">
                            <div class="brand-car-inner">
                                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <strong class="mb-0">{{ $item->user?->name }}</strong>
                                        <span class="text-muted">{{ $item->created_at?->format('d M, Y') }}</span>
                                    </div>

                                    @php
                                        $status = (string) $item->status;
                                        $statusClass = 'bg-secondary';
                                        if (strtolower($status) === 'open') {
                                            $statusClass = 'bg-success';
                                        }
                                        if (strtolower($status) === 'closed') {
                                            $statusClass = 'bg-dark';
                                        }
                                    @endphp
                                    <span class="badge {{ $statusClass }}">{{ $status }}</span>
                                </div>

                                <a href="{{ route('car-part-requests.show', $item->id) }}" class="text-decoration-none">
                                    <h3 class="mb-2">{{ $item->title }}</h3>
                                </a>

                                <p class="mb-0 text-muted">{{ \Illuminate\Support\Str::limit($item->part_description, 220) }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="brand-car-item p-4 shadow-sm rounded-3">
                            <p class="mb-0">{{ __('translate.No Item Found') }}</p>
                        </div>
                    </div>
                @endforelse
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

@extends('layout')
@section('title')
    <title>{{ __('translate.Messages') }}</title>
@endsection

@section('body-content')
<main>
    <section class="inner-banner">
        <div class="inner-banner-img" style=" background-image: url({{ getImageOrPlaceholder($breadcrumb, '1920x150') }}) ;"></div>
        <div class="container">
            <div class="col-lg-12">
                <div class="inner-banner-df">
                    <h1 class="inner-banner-taitel">{{ __('Messages') }}</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">{{ __('Dashboard') }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ __('Messages') }}</li>
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
                    <div class="dashboard-item">
                        <div class="dashboard-inner-text">
                            <h5>{{ __('Messages') }}</h5>
                        </div>

                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>{{ __('translate.User') }}</th>
                                        <th>{{ __('Last Message') }}</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($conversations as $c)
                                        @php
                                            $other = $c->user_one_id === $user->id ? $c->userTwo : $c->userOne;
                                            $preview = $c->lastMessage?->body;
                                        @endphp
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <img src="{{ getImageOrPlaceholder($other?->image, '40x40') }}" alt="img" style="width:40px;height:40px;border-radius:50%;object-fit:cover;">
                                                    <div>
                                                        <div style="display:flex;align-items:center;gap:8px;">
                                                            <span>{{ html_decode($other?->name) }}</span>
                                                            @if(($c->unread_count ?? 0) > 0)
                                                                <span class="badge" style="background:var(--brand-primary);color:#fff;">{{ $c->unread_count }}</span>
                                                            @endif
                                                        </div>
                                                        @if($preview)
                                                            <div style="font-size:12px;color:#6b7280;max-width:420px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                                                {{ $preview }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                {{ $c->last_message_at ? $c->last_message_at->diffForHumans() : '' }}
                                            </td>
                                            <td class="text-end">
                                                <a class="thm-btn-two" href="{{ route('user.messages.show', $c->id) }}">{{ __('Open') }}</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3">{{ __('No Data Found') }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
</main>
@endsection

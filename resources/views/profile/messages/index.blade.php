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

                        @if($conversations->count() > 0)
                            <div class="list-group" style="gap:10px;">
                                @foreach($conversations as $conversation)
                                    @php
                                        $otherUser = $conversation->user_one_id === $user->id ? $conversation->userTwo : $conversation->userOne;
                                        $lastMessage = $conversation->lastMessage;
                                    @endphp
                                    <a href="{{ route('user.messages.show', $conversation->id) }}" class="list-group-item list-group-item-action" style="border:1px solid #e5e7eb;border-radius:8px;margin-bottom:10px;">
                                        <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px;">
                                            <div>
                                                <h6 style="margin:0 0 6px;font-weight:700;">{{ html_decode($otherUser?->name) }}</h6>
                                                <p style="margin:0;color:#6b7280;font-size:14px;">
                                                    {{ $lastMessage ? \Illuminate\Support\Str::limit($lastMessage->body, 90) : __('Chat started') }}
                                                </p>
                                            </div>
                                            <div style="text-align:right;min-width:90px;">
                                                <div style="font-size:12px;color:#6b7280;">
                                                    {{ optional($lastMessage?->created_at ?? $conversation->last_message_at ?? $conversation->created_at)->format('M d, h:i A') }}
                                                </div>
                                                @if($conversation->unread_count > 0)
                                                    <span class="badge bg-danger" style="margin-top:8px;">{{ $conversation->unread_count }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center" style="padding:60px 20px;">
                                <p style="font-size:18px;color:#6b7280;margin:0;">{{ __('No messages yet.') }}</p>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </section>
</main>

@include('profile.logout')

@endsection

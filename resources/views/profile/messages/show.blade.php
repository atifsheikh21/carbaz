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
                    <h1 class="inner-banner-taitel">{{ __('translate.Messages') }}</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('translate.Home') }}</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('user.messages.index') }}">{{ __('translate.Messages') }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ html_decode($otherUser?->name) }}</li>
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
                        <div class="dashboard-inner-text" style="display:flex;align-items:center;justify-content:space-between;gap:12px;">
                            <h5>{{ html_decode($otherUser?->name) }}</h5>
                            <a href="{{ route('user.messages.index') }}" class="thm-btn-two">{{ __('translate.Back') }}</a>
                        </div>

                        <div style="border:1px solid #e5e7eb;border-radius:10px;padding:12px;max-height:420px;overflow:auto;background:#fff;">
                            @foreach($conversation->messages as $m)
                                @php
                                    $isMe = $m->sender_id === $user->id;
                                @endphp
                                <div style="display:flex;justify-content:{{ $isMe ? 'flex-end' : 'flex-start' }};margin-bottom:10px;">
                                    <div style="max-width:75%;padding:10px 12px;border-radius:12px;background:{{ $isMe ? 'var(--brand-primary)' : '#f3f4f6' }};color:{{ $isMe ? '#fff' : '#111827' }};">
                                        <div style="font-size:13px;line-height:1.4;">{{ $m->body }}</div>
                                        <div style="font-size:11px;opacity:.8;margin-top:4px;">{{ $m->created_at?->format('M d, Y h:i A') }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <form method="POST" action="{{ route('user.messages.store', $conversation->id) }}" style="margin-top:12px;">
                            @csrf
                            <div class="row g-3">
                                <div class="col-12">
                                    <textarea name="body" class="form-control" rows="3" placeholder="{{ __('translate.Message') }}" required>{{ old('body') }}</textarea>
                                </div>
                                <div class="col-12 text-end">
                                    <button type="submit" class="thm-btn-two">{{ __('translate.Send') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </section>
</main>
@endsection

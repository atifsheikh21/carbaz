@extends('layout')
@section('title')
    <title>{{ __('translate.Car Part Requests') }}</title>
@endsection

@section('body-content')
@php
    $categories = $categories ?? ['Engine', 'Electrical', 'Body', 'Radiator', 'Suspension', 'Transmission', 'Interior', 'Exterior', 'Wheels', 'Other'];
    $category = $category ?? '';
    $sorts = ['latest' => 'Latest', 'most_replied' => 'Most Answered', 'unanswered' => 'Unanswered', 'trending' => 'Trending'];
    $topContributors = $topContributors ?? collect();
    $trendingParts = $trendingParts ?? collect();
    $authUserId = auth('web')->id();
@endphp
<main>
    <section class="forum-shell">
        <div class="container">
            <div class="forum-topbar" id="top">
                <a href="{{ route('car-part-requests.index') }}" class="forum-logo">Part Help Forum</a>
                <form action="{{ route('car-part-requests.index') }}" method="GET" class="forum-search">
                    <input type="text" name="q" value="{{ $search }}" placeholder="Search questions, parts, models...">
                    <input type="hidden" name="sort" value="{{ $sort }}">
                </form>
                <a href="{{ route('car-part-requests.create') }}" class="forum-ask">Send Part Request / Question</a>
                <div class="forum-avatar">@if(auth('web')->user()?->image)<img src="{{ getImageOrPlaceholder(auth('web')->user()->image,'40x40') }}" alt="" style="width:40px;height:40px;border-radius:50%;object-fit:cover;">@else{{ strtoupper(substr(auth('web')->user()?->name ?? 'G', 0, 1)) }}@endif</div>
            </div>
            <div class="forum-grid">
                <aside class="forum-left"><div class="forum-widget"><h3>Categories</h3><a href="{{ route('car-part-requests.index', array_filter(['q' => $search ?: null, 'sort' => $sort])) }}" class="{{ $category === '' ? 'active' : '' }}">All</a>@foreach ($categories as $cat)<a href="{{ route('car-part-requests.index', array_filter(['category' => $cat, 'q' => $search ?: null, 'sort' => $sort])) }}" class="{{ $category === $cat ? 'active' : '' }}">{{ $cat }}</a>@endforeach</div></aside>
                <section class="forum-feed-column">
                    <div class="forum-hero"><span>Community Q&A</span><h1>Find help for car part requests</h1></div>
                    <div class="forum-sortbar">@foreach ($sorts as $key => $label)<a href="{{ route('car-part-requests.index', array_filter(['q' => $search ?: null, 'sort' => $key])) }}" class="{{ $sort === $key ? 'active' : '' }}">{{ $label }}</a>@endforeach</div>
                    @forelse ($requests as $item)
                        <article class="forum-post-card">
                            <div class="forum-card-tag">{{ trim(($item->car_make ?: 'Part Help') . ' · ' . ($item->car_model ?: 'General'), ' ·') }}</div>
                            @if($item->category)
                                <div class="forum-card-category">{{ $item->category }}</div>
                            @endif
                            <a href="{{ route('car-part-requests.show', $item->id) }}" class="forum-card-title">{{ \Illuminate\Support\Str::title($item->title) }}</a>
                            @if($item->image)
                                <a href="{{ route('car-part-requests.show', $item->id) }}" class="forum-card-image">
                                    <img src="{{ getImageOrPlaceholder($item->image, '520x300') }}" alt="{{ \Illuminate\Support\Str::title($item->title) }}">
                                </a>
                            @endif
                            <p class="forum-card-description">{{ \Illuminate\Support\Str::ucfirst(\Illuminate\Support\Str::limit($item->part_description, 180)) }}</p>
                            @if($authUserId && $authUserId === (int) $item->user_id)
                                <div class="forum-owner-actions">
                                    <a href="{{ route('car-part-requests.edit', $item->id) }}">Edit</a>
                                    <form method="POST" action="{{ route('car-part-requests.delete', $item->id) }}" onsubmit="return confirm('Delete this post and all its replies?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit">Delete</button>
                                    </form>
                                </div>
                            @endif
                            <div class="forum-card-meta"><div class="forum-author"><span>@if($item->user?->image)<img src="{{ getImageOrPlaceholder($item->user->image,'40x40') }}" alt="" style="width:40px;height:40px;border-radius:50%;object-fit:cover;">@else{{ strtoupper(substr($item->user?->name ?? 'U', 0, 1)) }}@endif</span><strong>{{ $item->user?->name ?? 'Community member' }}</strong><em>{{ $item->created_at?->diffForHumans() }}</em></div><div class="forum-actions"><span>▲ 0</span><span>{{ $item->replies_count }} replies</span><button type="button" aria-label="Bookmark">♡</button></div></div>
                        </article>
                    @empty
                        <div class="forum-post-card"><p class="mb-0">{{ __('translate.No Item Found') }}</p></div>
                    @endforelse
                    <div class="forum-pagination">{{ $requests->links('pagination_box') }}</div>
                </section>
                <aside class="forum-right">
                    <div class="forum-widget"><h3>Top Contributors</h3>@forelse ($topContributors as $contributor)<div class="forum-mini-user"><span>@if($contributor->image)<img src="{{ getImageOrPlaceholder($contributor->image,'40x40') }}" alt="" style="width:40px;height:40px;border-radius:50%;object-fit:cover;">@else{{ strtoupper(substr($contributor->name ?? 'U', 0, 1)) }}@endif</span><div><strong>{{ $contributor->name }}</strong><small>{{ $contributor->car_part_request_replies_count }} replies</small></div></div>@empty<p>No contributors yet.</p>@endforelse</div>
                    <div class="forum-widget"><h3>Trending Parts</h3>@forelse ($trendingParts as $part)<a href="{{ route('car-part-requests.index', ['q' => trim(($part->car_make ?? '') . ' ' . ($part->car_model ?? ''))]) }}">{{ trim(($part->car_make ?? '') . ' ' . ($part->car_model ?? '')) ?: 'General parts' }}</a>@empty<p>No trending parts yet.</p>@endforelse</div>
                </aside>
            </div>
            <nav class="forum-mobile-tabs"><a href="{{ route('car-part-requests.index') }}">Feed</a><a href="{{ route('car-part-requests.create') }}">Ask</a><a href="#top">Search</a></nav>
        </div>
    </section>
</main>
@endsection

@push('style_section')
<style>
    .forum-shell{background:#F9FAFB;padding:24px 0 80px;font-family:Inter,system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif;font-size:15px;color:#111827}.forum-topbar{position:sticky;top:0;z-index:20;display:flex;align-items:center;gap:14px;background:#fff;border:1px solid #E5E7EB;border-radius:12px;padding:12px;box-shadow:0 1px 3px rgba(0,0,0,.08);margin-bottom:24px}.forum-logo{font-weight:800;color:#111827;text-decoration:none;white-space:nowrap}.forum-search{flex:1}.forum-search input{width:100%;min-height:44px;border:1px solid #E5E7EB;border-radius:999px;padding:0 18px;background:#F9FAFB}.forum-ask{min-height:44px;display:inline-flex;align-items:center;justify-content:center;padding:0 18px;border-radius:8px;background:#b60304;color:#fff;font-weight:700;text-decoration:none}.forum-avatar,.forum-author span,.forum-mini-user span{width:40px;height:40px;border-radius:50%;background:#fde8e8;color:#b60304;display:inline-flex;align-items:center;justify-content:center;font-weight:800;flex:0 0 auto}.forum-grid{display:grid;grid-template-columns:220px minmax(0,1fr) 280px;gap:22px;align-items:start}.forum-left,.forum-right{position:sticky;top:92px}.forum-widget,.forum-post-card,.forum-hero,.forum-sortbar{background:#fff;border:1px solid #E5E7EB;border-radius:8px;box-shadow:0 1px 3px rgba(0,0,0,.08)}.forum-widget{padding:18px;margin-bottom:18px}.forum-widget h3{font-size:16px;font-weight:800;margin:0 0 14px}.forum-widget a{min-height:44px;display:flex;align-items:center;color:#374151;text-decoration:none;border-radius:8px;padding:0 10px}.forum-widget a.active,.forum-widget a:hover{background:#fff1f1;color:#b60304;font-weight:700}.forum-mini-user{display:flex;align-items:center;gap:10px;margin-bottom:12px}.forum-mini-user small{display:block;color:#6B7280;font-size:13px}.forum-hero{padding:22px;margin-bottom:14px}.forum-hero span{color:#b60304;font-weight:800}.forum-hero h1{font-size:24px;margin:6px 0 0}.forum-sortbar{display:flex;flex-wrap:wrap;gap:8px;padding:10px;margin-bottom:14px}.forum-sortbar a{min-height:44px;display:inline-flex;align-items:center;padding:0 14px;border-radius:999px;color:#4B5563;text-decoration:none}.forum-sortbar a.active{background:#b60304;color:#fff;font-weight:700}.forum-post-card{padding:22px;margin-bottom:14px}.forum-card-tag{display:inline-flex;color:#b60304;background:#fff1f1;border-radius:999px;padding:6px 10px;font-size:12px;font-weight:800;margin-bottom:10px}.forum-card-title{display:block;color:#111827;text-decoration:none;font-size:18px;line-height:1.4;font-weight:700;margin-bottom:8px}.forum-post-card p{color:#4B5563;line-height:1.6;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;margin:0 0 16px}.forum-card-meta{display:flex;align-items:center;justify-content:space-between;gap:14px;flex-wrap:wrap}.forum-author,.forum-actions{display:flex;align-items:center;gap:10px}.forum-author em{color:#6B7280;font-style:normal;font-size:13px}.forum-actions span,.forum-actions button{min-height:36px;border:1px solid #E5E7EB;border-radius:999px;background:#fff;padding:0 12px;color:#4B5563}.forum-actions button{width:44px;padding:0}.forum-pagination{margin-top:24px}.forum-mobile-tabs{display:none}@media(max-width:991px){.forum-topbar{flex-wrap:wrap}.forum-search{order:5;flex:0 0 100%}.forum-grid{grid-template-columns:1fr}.forum-left,.forum-right{position:static;display:none}.forum-post-card,.forum-hero{padding:18px}.forum-hero h1{font-size:20px}.forum-mobile-tabs{position:fixed;left:12px;right:12px;bottom:12px;z-index:30;display:flex;justify-content:space-around;background:#fff;border:1px solid #E5E7EB;border-radius:999px;box-shadow:0 1px 3px rgba(0,0,0,.08);padding:8px}.forum-mobile-tabs a{min-height:44px;display:flex;align-items:center;color:#b60304;font-weight:700;text-decoration:none}}}
    .forum-owner-actions{display:inline-flex!important;align-items:center;gap:8px;margin:0 0 14px;white-space:nowrap}
    .forum-owner-actions form{display:inline-flex!important;margin:0}
    .forum-owner-actions a,.forum-owner-actions button{width:auto!important;min-height:32px;display:inline-flex;align-items:center;justify-content:center;padding:0 14px;border:1px solid #D1D5DB;border-radius:999px;background:#fff;color:#374151;font-size:13px;font-weight:800;line-height:1;text-decoration:none;cursor:pointer}
    .forum-owner-actions button{border-color:#fca5a5;color:#dc2626}
    .forum-owner-actions button:hover{background:#fef2f2}
    .forum-owner-actions a:hover{border-color:#4B5563;color:#111827}
    .forum-card-category{display:inline-flex;align-items:center;min-height:24px;padding:0 10px;border-radius:999px;background:#eef2ff;color:#3730a3;font-size:12px;font-weight:800;margin:0 0 8px;text-transform:capitalize}
    .forum-card-image{display:flex;align-items:center;justify-content:center;width:100%;max-width:460px;margin:12px 0 14px;border-radius:8px;overflow:hidden;background:#F8FAFC;border:1px solid #E5E7EB}
    .forum-card-image img{display:block;width:100%;height:220px;object-fit:contain;background:#F8FAFC}
    .forum-post-card .forum-card-title{font-size:22px;line-height:1.25;font-weight:850;color:#111827;margin:4px 0 8px;text-transform:capitalize}
    .forum-post-card .forum-card-description{font-size:15px;line-height:1.65;color:#6B7280;margin:0 0 14px;padding-top:10px;border-top:1px solid #F3F4F6;text-transform:capitalize}
    @media(max-width:600px){.forum-card-image{max-width:none}.forum-card-image img{height:180px}.forum-post-card .forum-card-title{font-size:20px}}
</style>
@endpush

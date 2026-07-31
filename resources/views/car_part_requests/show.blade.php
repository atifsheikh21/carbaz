@extends('layout')
@section('title')
    <title>{{ $request->title }}</title>
@endsection

@section('body-content')
@php
    $relatedQuestions = $relatedQuestions ?? collect();
    $myRequestVote    = $myRequestVote ?? null;
    $myReplyVotes     = $myReplyVotes ?? collect();
    $authUserId       = auth('web')->id();
    $upvotes          = $request->votes->where('type','up')->count();
    $downvotes        = $request->votes->where('type','down')->count();
@endphp
<main>
    <section class="forum-shell">
        <div class="container">
            <div class="forum-topbar" id="top">
                <a href="{{ route('car-part-requests.index') }}" class="forum-logo">Part Help Forum</a>
                <form action="{{ route('car-part-requests.index') }}" method="GET" class="forum-search"><input type="text" name="q" placeholder="Search related questions..."></form>
                <a href="{{ route('car-part-requests.create') }}" class="forum-ask">Ask a Question</a>
                <div class="forum-avatar">@if(auth('web')->user()?->image)<img src="{{ getImageOrPlaceholder(auth('web')->user()->image,'40x40') }}" alt="" style="width:40px;height:40px;border-radius:50%;object-fit:cover;">@else{{ strtoupper(substr(auth('web')->user()?->name ?? 'G', 0, 1)) }}@endif</div>
            </div>
            <div class="forum-detail-grid">
                <section>
                    <article class="forum-question-card">
                        <div class="forum-card-tag">{{ trim(($request->car_make ?: 'Part Help') . ' · ' . ($request->car_model ?: 'General'), ' ·') }}</div>
                        @if($request->category)
                            <div class="forum-card-category">{{ $request->category }}</div>
                        @endif
                        <h1>{{ $request->title }}</h1>
                        @if($request->image)
                            <div class="forum-request-image">
                                <img src="{{ getImageOrPlaceholder($request->image, '960x540') }}" alt="{{ $request->title }}">
                            </div>
                        @endif
                        <div class="forum-author forum-detail-author">
                            <span>@if($request->user?->image)<img src="{{ getImageOrPlaceholder($request->user->image,'40x40') }}" alt="" style="width:40px;height:40px;border-radius:50%;object-fit:cover;">@else{{ strtoupper(substr($request->user?->name ?? 'U', 0, 1)) }}@endif</span>
                            <strong>{{ $request->user?->name ?? 'Community member' }}</strong>
                            <em>{{ $request->created_at?->format('d M, Y') }}</em>
                        </div>
                        <p>{{ $request->part_description }}</p>
                        @if ($request->additional_notes)<p>{{ $request->additional_notes }}</p>@endif
                        <div class="forum-spec-grid">
                            <div><small>Car Make</small><strong>{{ $request->car_make ?? '-' }}</strong></div>
                            <div><small>Car Model</small><strong>{{ $request->car_model ?? '-' }}</strong></div>
                            <div><small>Car Year</small><strong>{{ $request->car_year ?? '-' }}</strong></div>
                        </div>
                        <div class="forum-detail-actions">
                            @auth('web')
                                <form method="POST" action="{{ route('car-part-requests.vote', $request->id) }}" style="display:inline;">
                                    @csrf
                                    <input type="hidden" name="type" value="up">
                                    <button type="submit" class="forum-vote-btn {{ $myRequestVote === 'up' ? 'forum-vote-active' : '' }}">▲ Upvote <span class="forum-vote-count">{{ $upvotes }}</span></button>
                                </form>
                                <form method="POST" action="{{ route('car-part-requests.vote', $request->id) }}" style="display:inline;">
                                    @csrf
                                    <input type="hidden" name="type" value="down">
                                    <button type="submit" class="forum-vote-btn {{ $myRequestVote === 'down' ? 'forum-vote-active' : '' }}">▼ Downvote <span class="forum-vote-count">{{ $downvotes }}</span></button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="forum-vote-btn">▲ Upvote <span class="forum-vote-count">{{ $upvotes }}</span></a>
                                <a href="{{ route('login') }}" class="forum-vote-btn">▼ Downvote <span class="forum-vote-count">{{ $downvotes }}</span></a>
                            @endauth
                            <button type="button" onclick="forumShare(this)">Share</button>
                            <button type="button" aria-label="Report" onclick="forumReport()">⚑</button>
                            @if($authUserId && $authUserId === (int)$request->user_id)
                                <a href="{{ route('car-part-requests.edit', $request->id) }}" class="forum-action-btn forum-action-edit">✏ Edit</a>
                                <form method="POST" action="{{ route('car-part-requests.delete', $request->id) }}" style="display:inline;" onsubmit="return confirm('Delete this post and all its replies?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="forum-action-btn forum-action-delete">🗑 Delete</button>
                                </form>
                            @endif
                        </div>
                    </article>

                    <div class="forum-answer-header">
                        <h2>{{ $request->replies->count() }} Answers</h2>
                        <span>Best Answer → Most Upvoted → Chronological</span>
                    </div>

                    @forelse ($request->replies->sortByDesc(fn($r) => $r->votes->where('type','up')->count()) as $reply)
                        @php
                            $replyUpvotes   = $reply->votes->where('type','up')->count();
                            $replyDownvotes = $reply->votes->where('type','down')->count();
                            $myReplyVote    = $myReplyVotes[$reply->id] ?? null;
                        @endphp
                        <article class="forum-answer-card" id="reply-{{ $reply->id }}">
                            @if ($loop->first)<div class="forum-best-badge">✓ Best answer</div>@endif
                            <div class="forum-author">
                                <span>@if($reply->user?->image)<img src="{{ getImageOrPlaceholder($reply->user->image,'40x40') }}" alt="" style="width:40px;height:40px;border-radius:50%;object-fit:cover;">@else{{ strtoupper(substr($reply->user?->name ?? 'U', 0, 1)) }}@endif</span>
                                <strong>{{ $reply->user?->name ?? 'Community member' }}</strong>
                                <em>{{ $reply->created_at?->diffForHumans() }}</em>
                            </div>
                            @if($authUserId && $authUserId === (int)$reply->user_id)
                                <div id="reply-edit-form-{{ $reply->id }}" style="display:none;margin-bottom:12px;">
                                    <form method="POST" action="{{ route('car-part-requests.reply.update', $reply->id) }}">
                                        @csrf
                                        @method('PUT')
                                        <textarea name="message" class="forum-rich-editor" rows="4" style="margin-bottom:8px;">{{ $reply->message }}</textarea>
                                        <input type="number" step="0.01" name="offer_price" class="forum-offer-input" style="margin-bottom:8px;" value="{{ $reply->offer_price }}" placeholder="Offer Price (Optional)">
                                        <div style="display:flex;gap:8px;margin-top:8px;">
                                            <button type="submit" class="forum-ask" style="min-height:36px;font-size:13px;">Save</button>
                                            <button type="button" class="forum-action-btn" onclick="toggleReplyEdit({{ $reply->id }})">Cancel</button>
                                        </div>
                                    </form>
                                </div>
                            @endif
                            <div id="reply-body-{{ $reply->id }}">
                                <p>{{ $reply->message }}</p>
                                @if (!is_null($reply->offer_price))<div class="forum-price">{{ __('translate.Offer Price') }}: {{ currency($reply->offer_price) }}</div>@endif
                            </div>
                            <div class="forum-detail-actions" style="margin-top:12px;">
                                @auth('web')
                                    <form method="POST" action="{{ route('car-part-requests.reply.vote', $reply->id) }}" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="type" value="up">
                                        <button type="submit" class="forum-vote-btn {{ $myReplyVote === 'up' ? 'forum-vote-active' : '' }}">▲ {{ $replyUpvotes }}</button>
                                    </form>
                                    <form method="POST" action="{{ route('car-part-requests.reply.vote', $reply->id) }}" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="type" value="down">
                                        <button type="submit" class="forum-vote-btn {{ $myReplyVote === 'down' ? 'forum-vote-active' : '' }}">▼ {{ $replyDownvotes }}</button>
                                    </form>
                                @else
                                    <a href="{{ route('login') }}" class="forum-vote-btn">▲ {{ $replyUpvotes }}</a>
                                    <a href="{{ route('login') }}" class="forum-vote-btn">▼ {{ $replyDownvotes }}</a>
                                @endauth
                                @if($authUserId && $authUserId === (int)$reply->user_id)
                                    <button type="button" class="forum-action-btn forum-action-edit" onclick="toggleReplyEdit({{ $reply->id }})">✏ Edit</button>
                                    <form method="POST" action="{{ route('car-part-requests.reply.delete', $reply->id) }}" style="display:inline;" onsubmit="return confirm('Delete this reply?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="forum-action-btn forum-action-delete">🗑 Delete</button>
                                    </form>
                                @endif
                            </div>
                        </article>
                    @empty
                        <article class="forum-answer-card"><p class="mb-0">{{ __('translate.No replies yet') }}</p></article>
                    @endforelse

                    <article class="forum-answer-card">
                        @auth('web')
                            <h2>{{ __('translate.Reply') }}</h2>
                            <form method="POST" action="{{ route('car-part-requests.reply', $request->id) }}">
                                @csrf
                                <textarea name="message" class="forum-rich-editor" rows="7" placeholder="Write a helpful answer...">{{ old('message') }}</textarea>
                                <input type="number" step="0.01" name="offer_price" class="forum-offer-input" value="{{ old('offer_price') }}" placeholder="{{ __('translate.Offer Price') }} ({{ __('translate.Optional') }})">
                                <button type="submit" class="forum-ask" style="margin-top:12px;">{{ __('translate.Submit Reply') }}</button>
                            </form>
                        @else
                            <h2>Want to reply?</h2><p>Sign up or log in to respond directly to community members.</p><a href="{{ route('register') }}" class="forum-ask">Sign up to reply</a>
                        @endauth
                    </article>
                </section>
                <aside class="forum-right"><div class="forum-widget"><h3>Related Questions</h3>@forelse ($relatedQuestions as $related)<a href="{{ route('car-part-requests.show', $related->id) }}">{{ $related->title }}</a>@empty<p>No related questions yet.</p>@endforelse</div></aside>
            </div>
            <nav class="forum-mobile-tabs"><a href="{{ route('car-part-requests.index') }}">Feed</a><a href="{{ route('car-part-requests.create') }}">Ask</a><a href="#top">Top</a></nav>
        </div>
    </section>
</main>
<div id="forum-toast" style="display:none;position:fixed;bottom:28px;left:50%;transform:translateX(-50%);background:#222;color:#fff;padding:10px 22px;border-radius:8px;font-size:14px;z-index:9999;pointer-events:none;"></div>
@endsection

@push('style_section')
<style>
    .forum-shell{background:#F9FAFB;padding:24px 0 80px;font-family:Inter,system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif;font-size:15px;color:#111827}
    .forum-topbar{position:sticky;top:0;z-index:20;display:flex;align-items:center;gap:14px;background:#fff;border:1px solid #E5E7EB;border-radius:12px;padding:12px;box-shadow:0 1px 3px rgba(0,0,0,.08);margin-bottom:24px}
    .forum-logo{font-weight:800;color:#111827;text-decoration:none;white-space:nowrap}
    .forum-search{flex:1}
    .forum-search input,.forum-rich-editor,.forum-offer-input{width:100%;min-height:44px;border:1px solid #E5E7EB;border-radius:8px;padding:12px 14px;background:#fff;box-sizing:border-box;font-family:inherit;font-size:15px;}
    .forum-search input{border-radius:999px;background:#F9FAFB}
    .forum-ask{min-height:44px;display:inline-flex;align-items:center;justify-content:center;padding:0 18px;border:0;border-radius:8px;background:#b60304;color:#fff;font-weight:700;text-decoration:none;cursor:pointer;}
    .forum-avatar,.forum-author span{width:40px;height:40px;border-radius:50%;background:#fde8e8;color:#b60304;display:inline-flex;align-items:center;justify-content:center;font-weight:800;flex:0 0 auto}
    .forum-detail-grid{display:grid;grid-template-columns:minmax(0,1fr) 300px;gap:22px;align-items:start}
    .forum-right{position:sticky;top:92px}
    .forum-widget,.forum-question-card,.forum-answer-card{background:#fff;border:1px solid #E5E7EB;border-radius:8px;box-shadow:0 1px 3px rgba(0,0,0,.08)}
    .forum-widget{padding:18px}
    .forum-widget h3{font-size:16px;font-weight:800;margin:0 0 14px}
    .forum-widget a{min-height:44px;display:flex;align-items:center;color:#374151;text-decoration:none;border-radius:8px;padding:0 10px}
    .forum-card-tag{display:inline-flex;color:#b60304;background:#fff1f1;border-radius:999px;padding:6px 10px;font-size:12px;font-weight:800;margin-bottom:10px}
    .forum-card-category{display:inline-flex;align-items:center;min-height:24px;padding:0 10px;border-radius:999px;background:#eef2ff;color:#3730a3;font-size:12px;font-weight:800;margin:0 0 10px}
    .forum-request-image{margin:14px 0 18px;border-radius:8px;overflow:hidden;background:#F3F4F6;border:1px solid #E5E7EB}
    .forum-request-image img{display:block;width:100%;max-height:520px;object-fit:contain;background:#fff}
    .forum-question-card,.forum-answer-card{padding:24px;margin-bottom:16px}
    .forum-question-card h1{font-size:24px;line-height:1.35;margin:0 0 14px}
    .forum-question-card p,.forum-answer-card p{color:#4B5563;line-height:1.7}
    .forum-author{display:flex;align-items:center;gap:10px;margin-bottom:14px;font-size:14px}
    .forum-author strong{color:#111827}
    .forum-author em{color:#9CA3AF;font-style:normal}
    .forum-spec-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;background:#F9FAFB;border-radius:8px;padding:14px;margin:18px 0}
    .forum-spec-grid small{display:block;color:#9CA3AF;font-size:11px;font-weight:600;text-transform:uppercase;margin-bottom:4px}
    .forum-spec-grid strong{font-size:15px;color:#111827}
    .forum-detail-actions{display:flex;flex-wrap:wrap;gap:8px;margin-top:18px}
    .forum-detail-actions button,.forum-detail-actions a{min-height:36px;padding:0 14px;border:1px solid #E5E7EB;border-radius:999px;background:#fff;font-size:13px;font-weight:600;color:#374151;cursor:pointer;display:inline-flex;align-items:center;text-decoration:none;gap:4px}
    .forum-vote-btn{transition:background .15s,color .15s,border-color .15s}
    .forum-vote-btn:hover{border-color:#b60304;color:#b60304}
    .forum-vote-active{background:#b60304 !important;color:#fff !important;border-color:#b60304 !important}
    .forum-vote-count{font-weight:800}
    .forum-action-btn{min-height:34px;padding:0 12px;border:1px solid #E5E7EB;border-radius:999px;background:#fff;font-size:12px;font-weight:600;color:#374151;cursor:pointer;display:inline-flex;align-items:center;text-decoration:none;gap:4px}
    .forum-action-edit:hover{border-color:#4B5563;color:#111}
    .forum-action-delete{border-color:#fca5a5;color:#dc2626}
    .forum-action-delete:hover{background:#fef2f2}
    .forum-answer-header{display:flex;align-items:center;justify-content:space-between;margin:24px 0 14px}
    .forum-answer-header h2{font-size:18px;font-weight:800;margin:0}
    .forum-answer-header span{font-size:13px;color:#9CA3AF}
    .forum-best-badge{display:inline-flex;align-items:center;background:#d1fae5;color:#065f46;font-size:12px;font-weight:800;border-radius:999px;padding:5px 12px;margin-bottom:12px}
    .forum-price{background:#fff1f1;border:1px solid #fca5a5;color:#b60304;border-radius:8px;padding:8px 14px;font-weight:700;font-size:14px;margin:10px 0}
    .forum-offer-input{margin-top:10px}
    .forum-rich-editor{margin-bottom:0;resize:vertical}
    .forum-mobile-tabs{display:none}
    @media(max-width:767px){
        .forum-detail-grid{grid-template-columns:1fr}
        .forum-right{display:none}
        .forum-mobile-tabs{display:flex;position:fixed;bottom:0;left:0;right:0;background:#fff;border-top:1px solid #E5E7EB;z-index:30}
        .forum-mobile-tabs a{flex:1;display:flex;align-items:center;justify-content:center;min-height:52px;color:#374151;text-decoration:none;font-size:13px;font-weight:700}
        .forum-spec-grid{grid-template-columns:1fr 1fr}
    }
</style>
@endpush

@push('js_section')
<script>
function forumShare(btn) {
    var url = window.location.href;
    if (navigator.share) {
        navigator.share({ title: document.title, url: url }).catch(function(){});
    } else if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(url).then(function() {
            forumToast('Link copied to clipboard!');
        }).catch(function() { forumFallbackCopy(url); });
    } else {
        forumFallbackCopy(url);
    }
}
function forumFallbackCopy(text) {
    var ta = document.createElement('textarea');
    ta.value = text;
    ta.style.cssText = 'position:fixed;top:-999px;left:-999px';
    document.body.appendChild(ta);
    ta.focus(); ta.select();
    try { document.execCommand('copy'); forumToast('Link copied!'); }
    catch(e) { forumToast('Copy: ' + text); }
    document.body.removeChild(ta);
}
function forumToast(msg) {
    var t = document.getElementById('forum-toast');
    if (!t) return;
    t.textContent = msg;
    t.style.display = 'block';
    clearTimeout(t._timer);
    t._timer = setTimeout(function(){ t.style.display = 'none'; }, 2800);
}
function forumReport() {
    forumToast('Thank you — this post has been flagged for review.');
}
function toggleReplyEdit(id) {
    var form = document.getElementById('reply-edit-form-' + id);
    var body = document.getElementById('reply-body-' + id);
    if (!form || !body) return;
    var isHidden = form.style.display === 'none';
    form.style.display = isHidden ? 'block' : 'none';
    body.style.display = isHidden ? 'none' : 'block';
}
</script>
@endpush

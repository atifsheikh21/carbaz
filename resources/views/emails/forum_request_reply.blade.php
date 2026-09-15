<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>New reply on your part request</title>
</head>
<body style="margin:0;padding:0;background:#f5f5f5;font-family:Arial,sans-serif;color:#111827;">
    <div style="max-width:620px;margin:0 auto;padding:24px;">
        <div style="background:#ffffff;border-radius:8px;padding:24px;border:1px solid #e5e7eb;">
            <h2 style="margin:0 0 12px;color:#b60304;">New reply on your part request</h2>
            <p style="margin:0 0 14px;">{{ $reply->user?->name ?? 'A community member' }} replied to your request:</p>
            <h3 style="margin:0 0 14px;color:#111827;">{{ $post->title }}</h3>
            <div style="background:#f9fafb;border-left:4px solid #b60304;border-radius:6px;padding:14px;margin:0 0 18px;line-height:1.6;">
                {{ \Illuminate\Support\Str::limit($reply->message, 350) }}
            </div>
            @if(!is_null($reply->offer_price))
                <p style="margin:0 0 18px;"><strong>Offer Price:</strong> {{ currency($reply->offer_price) }}</p>
            @endif
            <a href="{{ $postUrl }}" style="display:inline-block;background:#b60304;color:#ffffff;text-decoration:none;padding:12px 18px;border-radius:8px;font-weight:bold;">View reply</a>
        </div>
    </div>
</body>
</html>

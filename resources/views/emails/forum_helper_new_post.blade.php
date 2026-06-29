<!doctype html>
<html>
<body style="margin:0;background:#F3F4F6;font-family:Inter,Arial,sans-serif;color:#111827;">
    <div style="max-width:640px;margin:0 auto;padding:32px 16px;">
        <div style="background:#fff;border:1px solid #E5E7EB;border-radius:12px;padding:28px;">
            <h1 style="font-size:24px;line-height:1.3;margin:0 0 14px;">
                <a href="{{ $postUrl }}" style="color:#2563EB;text-decoration:none;">{{ $post->title }}</a>
            </h1>
            <p style="font-size:15px;line-height:1.7;color:#4B5563;margin:0 0 22px;">{{ \Illuminate\Support\Str::limit($post->part_description, 200) }}</p>
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:24px;">
                <div style="width:42px;height:42px;border-radius:50%;background:#DBEAFE;color:#2563EB;display:inline-flex;align-items:center;justify-content:center;font-weight:700;text-align:center;line-height:42px;">{{ strtoupper(substr($post->user?->name ?? 'U', 0, 1)) }}</div>
                <div>
                    <div style="font-weight:700;">{{ $post->user?->name }}</div>
                    <div style="font-size:13px;color:#6B7280;">{{ $post->created_at?->format('d M, Y H:i') }}</div>
                </div>
            </div>
            <a href="{{ $postUrl }}" style="display:inline-block;background:#2563EB;color:#fff;text-decoration:none;border-radius:8px;padding:12px 18px;font-weight:700;">View & Reply</a>
        </div>
        <p style="font-size:12px;line-height:1.6;color:#6B7280;text-align:center;margin:18px 0 0;">
            You're receiving this because you opted in as a forum helper.
            <a href="{{ $unsubscribeUrl }}" style="color:#2563EB;">Unsubscribe from helper alerts.</a>
        </p>
    </div>
</body>
</html>

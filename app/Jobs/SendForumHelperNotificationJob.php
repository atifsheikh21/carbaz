<?php

namespace App\Jobs;

use App\Helpers\MailHelper;
use App\Mail\ForumHelperNewPostMail;
use App\Models\CarPartRequest;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendForumHelperNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $carPartRequestId)
    {
    }

    public function handle(): void
    {
        if (!(bool) env('FORUM_HELPER_EMAILS_ENABLED', true)) {
            return;
        }

        $post = CarPartRequest::with('user')->find($this->carPartRequestId);

        if (!$post) {
            return;
        }

        MailHelper::setMailConfig();

        User::where('is_forum_helper', true)
            ->where('id', '!=', $post->user_id)
            ->chunkById(100, function ($users) use ($post) {
                foreach ($users as $user) {
                    try {
                        Mail::to($user->email)->send(new ForumHelperNewPostMail($post, $user));
                    } catch (\Exception $e) {
                        Log::error('Forum helper mail send error: ' . $e->getMessage());
                    }
                }
            });
    }
}

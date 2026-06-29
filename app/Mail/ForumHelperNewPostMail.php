<?php

namespace App\Mail;

use App\Models\CarPartRequest;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class ForumHelperNewPostMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $postUrl;
    public string $unsubscribeUrl;

    public function __construct(public CarPartRequest $post, public User $recipient)
    {
        $this->postUrl = route('car-part-requests.show', $post->id);
        $this->unsubscribeUrl = URL::signedRoute('forum-helper.unsubscribe', ['id' => $recipient->id]);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New part request: ' . $this->post->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.forum_helper_new_post',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

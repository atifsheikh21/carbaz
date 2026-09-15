<?php

namespace App\Mail;

use App\Models\CarPartRequest;
use App\Models\CarPartRequestReply;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ForumRequestReplyMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $postUrl;

    public function __construct(public CarPartRequest $post, public CarPartRequestReply $reply)
    {
        $this->postUrl = route('car-part-requests.show', $post->id) . '#reply-' . $reply->id;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New reply on your part request: ' . $this->post->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.forum_request_reply',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

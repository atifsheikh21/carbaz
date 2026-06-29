<?php

namespace App\Notifications;

use App\Models\AdReport;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Config;

class AdReported extends Notification
{
    use Queueable;

    public function __construct(public AdReport $report, public string $adUrl)
    {
    }

    public function via(object $notifiable): array
    {
        $channels = ['database'];

        $defaultMailer = Config::get('mail.default');
        $mailers = (array) Config::get('mail.mailers', []);
        $mailerConfig = $defaultMailer ? ($mailers[$defaultMailer] ?? null) : null;

        $transport = is_array($mailerConfig) ? ($mailerConfig['transport'] ?? null) : null;
        $host = is_array($mailerConfig) ? ($mailerConfig['host'] ?? null) : null;

        if (!empty($transport) && (strtolower((string) $transport) !== 'smtp' || !empty($host))) {
            $channels[] = 'mail';
        }

        return $channels;
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'ad_report_id' => $this->report->id,
            'reason' => $this->report->reason,
            'details' => $this->report->details,
            'reportable_type' => $this->report->reportable_type,
            'reportable_id' => $this->report->reportable_id,
            'url' => $this->adUrl,
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $subject = 'Ad Reported';

        return (new MailMessage)
            ->subject($subject)
            ->line('An ad has been reported.')
            ->line('Reason: ' . $this->report->reason)
            ->line('Details: ' . ($this->report->details ?: '-'))
            ->action('View Ad', $this->adUrl);
    }
}

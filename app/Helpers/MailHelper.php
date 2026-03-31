<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;
use Modules\GeneralSetting\Entities\EmailConfiguration;

class MailHelper
{

    public static function setMailConfig(){

        $email_setting=EmailConfiguration::first();

        if (!$email_setting) {
            Log::error('EmailConfiguration is missing: cannot configure SMTP settings for outgoing mail.');
            return;
        }

        $host = (string) $email_setting->mail_host;
        $port = (int) $email_setting->mail_port;
        $isLocalRelay = in_array($host, ['localhost', '127.0.0.1'], true) && $port === 25;

        $mailConfig = [
            'transport' => 'smtp',
            'host' => $host,
            'port' => $port,
            'encryption' => $isLocalRelay ? null : $email_setting->mail_encryption,
            'username' => $isLocalRelay ? null : $email_setting->smtp_username,
            'password' => $isLocalRelay ? null : $email_setting->smtp_password,
            'timeout' => null
        ];

        config(['mail.mailers.smtp' => $mailConfig]);
        config(['mail.from.address' => $email_setting->email]);
    }
}

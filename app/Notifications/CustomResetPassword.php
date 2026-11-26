<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class CustomResetPassword extends ResetPassword
{
    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        // Ensure the URL uses APP_URL from env if set, even in local dev
        $appUrl = config('app.url');
        if ($appUrl && ! str_starts_with($url, $appUrl)) {
            $path = parse_url($url, PHP_URL_PATH);
            $query = parse_url($url, PHP_URL_QUERY);
            $url = rtrim($appUrl, '/').$path.($query ? '?'.$query : '');
        }

        return (new MailMessage)
            ->subject('Reset Password - SIBATU - Sistem Bantuan Tepat Sasaran')
            ->view('emails.reset-password', [
                'url' => $url,
                'user' => $notifiable,
            ]);
    }
}

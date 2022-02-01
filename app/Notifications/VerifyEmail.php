<?php
namespace App\Notifications;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Lang;
use Illuminate\Auth\Notifications\VerifyEmail as VerifyEmailBase;
use Illuminate\Support\Facades\DB;

class VerifyEmail extends VerifyEmailBase
{
//    use Queueable;
    /**
     * Get the verification URL for the given notifiable.
     *
     * @param  mixed  $notifiable
     * @return string
     */
    protected function verificationUrl($notifiable)
    {
        $prefix = getenv('VUE'). 'user/verify';

        $token = str_random(50) ;
        DB::table('password_resets')->insert(['token' => $token, 'email' => $notifiable->email,'created_at' => now() ]);
        return $prefix .'/'. urlencode($notifiable->id).'?token='.$token;
    }
}
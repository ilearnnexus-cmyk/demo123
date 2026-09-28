<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;

class Sms
{
    /**
     * @param $notifiable
     * @param Notification $notification
     * @throws \Twilio\Exceptions\TwilioException
     */
    public function send($notifiable, Notification $notification)
    {
        dd($notifiable, $notification)
        //sendSMS();
    }
}

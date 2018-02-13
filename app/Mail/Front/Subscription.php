<?php

namespace App\Mail\Front;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Models\Setting;

class Subscription extends Mailable
{
    public $token;
    
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject, $user, $usermh)
    {
        $this->subject = $subject;
        $this->user = $user;
        $this->usermh = $usermh;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $setting = Setting::first();

        $data['subject'] = $this->subject;
        $data['user'] = $this->user;
        $data['usermh'] = $this->usermh;
        return $this->subject($this->subject)
                    ->from($setting->sender_email, $setting->sender_email_name)
                    ->view('mails.front.subscription', $data);
    }
}

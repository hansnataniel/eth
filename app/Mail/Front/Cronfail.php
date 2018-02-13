<?php

namespace App\Mail\Front;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Models\Setting;

class Cronfail extends Mailable
{
    public $token;
    
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->subject = "Cron Tidak Jalan";
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
        return $this->subject($this->subject)
                    ->from($setting->sender_email, $setting->sender_email_name)
                    ->view('mails.front.cronfail', $data);
    }
}

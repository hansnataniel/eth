<?php

namespace App\Mail\Front;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Models\Setting;

class Cron extends Mailable
{
    public $token;
    
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($mininghistory, $avg, $users)
    {
        $this->subject = "Cron Jalan";
        $this->mininghistory = $mininghistory;
        $this->avg = $avg;
        $this->users = $users;
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
        $data['mininghistory'] = $this->mininghistory;
        $data['avg'] = $this->avg;
        $data['users'] = $this->users;
        return $this->subject($this->subject)
                    ->from($setting->sender_email, $setting->sender_email_name)
                    ->view('mails.front.cron', $data);
    }
}

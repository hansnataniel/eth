<?php

namespace App\Mail\Front;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Models\Setting;
use App\Models\User;

class Pay extends Mailable
{
    public $token;
    
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject, $payment, $usermh)
    {
        $this->subject = $subject;
        $this->payment = $payment;
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
        $data['payment'] = $this->payment;
        $data['usermh'] = $this->usermh;

        $payment = $this->payment;
        $user = User::find($payment->user_id);

        return $this->subject($this->subject)
                    ->from($user->email, $user->name)
                    ->view('mails.front.payment', $data);
    }
}

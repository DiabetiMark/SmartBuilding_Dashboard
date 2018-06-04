<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class newUser extends Mailable
{
    use Queueable, SerializesModels;

    public $url;
    public $hash;
    public $name;
    public $username;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $hash, $username)
    {
        $this->name = $name;
        $this->hash = $hash;
        $this->username = $username;
        $this->url = env('APP_URL') . '/login';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Welkom bij Aareon')
                    ->markdown('emails.newUser');
    }
}

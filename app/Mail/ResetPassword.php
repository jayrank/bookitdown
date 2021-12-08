<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ResetPassword extends Mailable
{
    use Queueable, SerializesModels;
	
	public $token;
	public $name;
    public $email;
	
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($token,$name,$email)
	{
		$this->token    = $token;
		$this->name     = $name;
        $this->email    = $email;
	}

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(env("MAIL_FROM_ADDRESS", "info@ikotel.ca"), 'Scheduledown')
	                ->view('emailTemplates.resetPassword')
	                ->subject('Change Password');
    }
}

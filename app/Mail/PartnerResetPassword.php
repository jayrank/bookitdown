<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PartnerResetPassword extends Mailable
{
    use Queueable, SerializesModels;
	
	public $token;
	public $name;
    public $email;
    public $locationName;
	
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($token,$name,$email,$locationName)
	{
		$this->token    = $token;
		$this->name     = $name;
        $this->email    = $email;
        $this->locationName    = $locationName;
	}

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(env("MAIL_FROM_ADDRESS", "info@ikotel.ca"), 'Scheduledown')
	                ->view('emailTemplates.partnerResetPassword')
	                ->subject('Reset password for '.$this->locationName);
    }
}

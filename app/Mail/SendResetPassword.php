<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendResetPassword extends Mailable
{
    use Queueable, SerializesModels;
	
	public $from_email;
	public $from_name;
	public $subject;
	
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($FROM_EMAIL,$FROM_NAME,$SUBJECT)
	{
		$this->from_email    = $FROM_EMAIL;
		$this->from_name     = $FROM_NAME;
		$this->subject       = $SUBJECT;
	}

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->from_email,$this->from_name)
	                ->view('emailTemplates.setStaffPasswordMail')
	                ->subject($this->subject);
    }
}

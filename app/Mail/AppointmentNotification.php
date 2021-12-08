<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AppointmentNotification extends Mailable
{
    use Queueable, SerializesModels;
	
	public $from_email;
	public $from_name;
	public $subject;
	public $content;
	public $location;
	public $customer;
	 
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($FROM_EMAIL,$FROM_NAME,$SUBJECT,$MESSAGE,$LOCATION,$CUSTOMER)
	{
		$this->from_email    = $FROM_EMAIL;
		$this->from_name     = $FROM_NAME;
		$this->subject       = $SUBJECT;
		$this->content	 	 = $MESSAGE;
		$this->location	 	 = $LOCATION;
		$this->customer	 	 = $CUSTOMER;
	}

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
		/* echo view('emailTemplates.appointmentNotification');
		die; */
        return $this->from($this->from_email,$this->from_name)
	                ->view('emailTemplates.appointmentNotification')
	                ->subject($this->subject);
    }
}

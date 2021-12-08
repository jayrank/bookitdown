<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class newAppointments extends Mailable
{
    use Queueable, SerializesModels;
	
	public $from_email;
	public $from_name;
	public $subject;
	public $AppointmentServices;
	public $client;
	public $Service;
	public $remidernoti;
	public $unique_id;
	public $getappo;

	
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($FROM_EMAIL,$FROM_NAME,$SUBJECT,$AppointmentServices,$client,$UniqueId,$Getappo,$Service,$remidernoti)
	{
		$this->from_email    = $FROM_EMAIL;
		$this->from_name     = $FROM_NAME;
		$this->subject       = $SUBJECT;
		$this->AppointmentServices   = $AppointmentServices;
		$this->client        = $client;
		$this->Service       = $Service;
		$this->remidernoti   = $remidernoti;
		$this->unique_id     = $UniqueId;
		$this->getappo    = $Getappo;
	}

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->withSwiftMessage(function ($message) {
            $message->getHeaders()
                    ->addTextHeader('X-APIHEADER',$this->unique_id);
        });
		
        return $this->from($this->from_email,$this->from_name)
	                ->view('emailTemplates.newAppointmentNotification')
                    ->with(['AppointmentServices' => $this->AppointmentServices,'client' => $this->client,'service' => $this->Service,'appo' => $this->getappo, 'remider' => $this->remidernoti,])
	                ->subject($this->subject);
    }
}

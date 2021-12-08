<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class noShowAppointments extends Mailable
{
    use Queueable, SerializesModels;
	
	public $from_email;
	public $from_name;
	public $subject;
	public $AppointmentServices;
	public $client;
	public $service;
	public $appointment;
	public $remidernoti;
	public $unique_id;
	
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($FROM_EMAIL,$FROM_NAME,$SUBJECT,$AppointmentServices,$appointment,$UniqueId,$client,$service,$remidernoti)
	{
		$this->from_email    = $FROM_EMAIL;
		$this->from_name     = $FROM_NAME;
		$this->subject       = $SUBJECT;
		$this->AppointmentServices   = $AppointmentServices;
		$this->unique_id     = $UniqueId;
		$this->client        = $client;
		$this->service       = $service;
		$this->remidernoti   = $remidernoti;
		$this->appointment    = $appointment;
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
	                ->view('emailTemplates.noshowNotification')
                    ->with(['AppointmentServices' => $this->AppointmentServices,'client' => $this->client,'service' => $this->service,'appointment' => $this->appointment, 'remider' => $this->remidernoti,])
	                ->subject($this->subject);
    }
}

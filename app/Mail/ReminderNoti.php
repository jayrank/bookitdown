<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReminderNoti extends Mailable
{
    use Queueable, SerializesModels;
	
	public $from_email;
	public $from_name;
	public $subject;
	public $Appointment;
	public $client;
	public $service;
	public $getappoint;
	public $remidernoti;
	public $unique_id;
	
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($FROM_EMAIL,$FROM_NAME,$SUBJECT,$Appointment,$getappoint,$UniqueId,$client,$service,$remidernoti)
	{
		$this->from_email    = $FROM_EMAIL;
		$this->from_name     = $FROM_NAME;
		$this->subject       = $SUBJECT;
		$this->Appointment   = $Appointment;
		$this->unique_id     = $UniqueId;
		$this->client        = $client;
		$this->service       = $service;
		$this->remidernoti   = $remidernoti;
		$this->getappoint    = $getappoint;
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
	                ->view('emailTemplates.remiderNotification')
                    ->with(['app' => $this->Appointment,'client' => $this->client,'service' => $this->service,'appointment' => $this->getappoint, 'remider' => $this->remidernoti,])
	                ->subject($this->subject);
    }
}

<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ConsultationFormReminder extends Mailable
{
    use Queueable, SerializesModels;
	
	public $from_email;
	public $from_name;
	public $subject;
	public $email_message;
	public $unique_id;
	public $client_consultation_form;
	public $appointment;
	public $client_services;
	
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($FROM_EMAIL,$FROM_NAME,$SUBJECT,$MESSAGE,$UniqueId,$ClientConsultationFormGet,$Appointment,$ClientServices)
	{
		$this->from_email               = $FROM_EMAIL;
		$this->from_name                = $FROM_NAME;
		$this->subject                  = $SUBJECT;
		$this->email_message            = $MESSAGE;
		$this->unique_id                = $UniqueId;
		$this->client_consultation_form = $ClientConsultationFormGet;
		$this->appointment              = $Appointment;
		$this->client_services          = $ClientServices;
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
	                ->view('emailTemplates.consultationFormReminderMail')
	                ->subject($this->subject);
    }
}

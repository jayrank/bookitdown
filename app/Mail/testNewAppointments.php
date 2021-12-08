<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class testNewAppointments extends Mailable
{
    use Queueable, SerializesModels;
	
	public $from_email;
	public $from_name;
	public $subject;
	public $first_name;
	public $is_price_view;
	public $note;
	public $unique_id;
	public $locationData;

	
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($FROM_EMAIL,$FROM_NAME,$SUBJECT,$first_name,$is_price_view,$note,$UniqueId,$locationData,$type)
	{
		$this->from_email    = $FROM_EMAIL;
		$this->from_name     = $FROM_NAME;
		$this->subject       = $SUBJECT;
		$this->first_name       = $first_name;
		$this->is_price_view   = $is_price_view;
		$this->note        	= $note;
		$this->unique_id        = $UniqueId;
		$this->locationData        = $locationData;
		$this->type        = $type;
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
	                ->view('emailTemplates.testNewAppointmentNotification')
                    ->with(['first_name' => $this->first_name,'is_price_view' => $this->is_price_view,'note' => $this->note,'locationData' => $this->locationData,'type' => $this->type])
	                ->subject($this->subject);
    }
}

<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailBlast extends Mailable
{
    use Queueable, SerializesModels;
	
	public $from_email;
	public $from_name;
	public $subject;
	public $email_message;
	public $location;
	public $message_data;
	public $type;
    public $unique_id;
	public $voucher_list;
	
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($FROM_EMAIL,$FROM_NAME,$SUBJECT,$MESSAGE,$LOCATION,$message_data,$TYPE,$UniqueId,$voucherLists)
	{
		$this->from_email                   = $FROM_EMAIL;
		$this->from_name                    = $FROM_NAME;
		$this->subject                      = $SUBJECT;
		$this->email_message                = $MESSAGE;
		$this->location       				= $LOCATION;
		$this->message_data       			= $message_data;
		$this->type       					= $TYPE;
        $this->unique_id                    = $UniqueId;
		$this->voucher_list                 = $voucherLists;
	}

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
    	if($this->type == 'Campaign'){
    		$emailBody = "campaign_mail";
    	}else if($this->type == 'quick_update'){
    		$emailBody = "quick_update_mail";	
    	}else if($this->type == 'special_offer'){
    		$emailBody = "special_offer";	
    	} else if($this->type == 'buy_voucher'){
			$emailBody = "buyVoucherBlast";	
		}
		
        $this->withSwiftMessage(function ($message) {
            $message->getHeaders()
                    ->addTextHeader('X-APIHEADER',$this->unique_id);
        });
        return $this->from($this->from_email,$this->from_name)
	                ->view('emailTemplates.'.$emailBody)
	                ->subject($this->subject);
    }
}

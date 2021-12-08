<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PurchaseOrder extends Mailable
{
    use Queueable, SerializesModels;
	
	public $from_email;
	public $from_name;
	public $subject;
	public $email_message;
	public $orderid;
	public $reply_to;
	
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($FROM_EMAIL,$FROM_NAME,$SUBJECT,$MESSAGE,$ORDERID,$REPLY_TO = '')
	{
		$this->from_email    = $FROM_EMAIL;
		$this->from_name     = $FROM_NAME;
		$this->subject       = $SUBJECT;
		$this->email_message = $MESSAGE;
		$this->orderid       = $ORDERID;
		$this->reply_to 	 = $REPLY_TO;
	}

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->from_email,$this->from_name)
	                ->view('emailTemplates.purchaseOrderMail')
	                ->replyTo($this->reply_to)
	                ->subject($this->subject);
    }
}

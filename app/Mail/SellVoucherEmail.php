<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SellVoucherEmail extends Mailable
{
    use Queueable, SerializesModels;
	
	public $from_email;
	public $from_name;
	public $subject;
	public $email_message;
	public $voucher_sold_data;
	public $location_info;
	public $client_info;
	public $recipient_first_name;
	public $recipient_last_name;
	public $recipient_personalized_email;
	
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($FROM_EMAIL,$FROM_NAME,$SUBJECT,$MESSAGE,$VoucherSoldData,$LocationInfo,$ClientInfo,$RecipientFirstName,$RecipientLastName)
	{
		$this->from_email                   = $FROM_EMAIL;
		$this->from_name                    = $FROM_NAME;
		$this->subject                      = $SUBJECT;
		$this->email_message                = $MESSAGE;
		$this->voucher_sold_data            = $VoucherSoldData;
		$this->location_info                = $LocationInfo;
		$this->client_info                  = $ClientInfo;
		$this->recipient_first_name         = $RecipientFirstName;
		$this->recipient_last_name          = $RecipientLastName;
	}

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->from_email,$this->from_name)
	                ->view('emailTemplates.sellVoucherMail')
	                ->subject($this->subject);
    }
}

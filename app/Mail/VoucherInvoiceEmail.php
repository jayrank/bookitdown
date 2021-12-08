<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class VoucherInvoiceEmail extends Mailable
{
    use Queueable, SerializesModels;
	
	public $from_email;
	public $from_name;
	public $subject;
	public $email_message;
	public $Invoice;
	public $InvoiceItemsInfo;
	public $ClientInfo;
	public $InvoiceTaxes;
	public $TotalStaffTip;
	public $LocationInfo;
	public $PaymentDoneBy;
	public $VoucherSold;
	public $PlanSold;
	public $isRefunded;
	public $PreviousAppointment;
	public $PreviousServices;
	public $TotalSpend;
	public $soldProduct;
	public $ClientProducts;
	public $clientInvoices;
	public $ClientInovices;
	public $UserInfo;
	
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($FROM_EMAIL,$FROM_NAME,$SUBJECT,$MESSAGE,$Invoice,$InvoiceItemsInfo,$ClientInfo,$InvoiceTaxes,$TotalStaffTip,$LocationInfo,$PaymentDoneBy,$VoucherSold,$PlanSold,$isRefunded,$PreviousAppointment,$PreviousServices,$TotalSpend,$soldProduct,$ClientProducts,$clientInvoices,$ClientInovices,$UserInfo)
	{
		$this->from_email                   = $FROM_EMAIL;
		$this->from_name                    = $FROM_NAME;
		$this->subject                      = $SUBJECT;
		$this->email_message                = $MESSAGE;
		$this->Invoice                		= $Invoice;
		$this->InvoiceItemsInfo             = $InvoiceItemsInfo;
		$this->ClientInfo                	= $ClientInfo;
		$this->InvoiceTaxes                	= $InvoiceTaxes;
		$this->TotalStaffTip                = $TotalStaffTip;
		$this->LocationInfo                	= $LocationInfo;
		$this->PaymentDoneBy                = $PaymentDoneBy;
		$this->VoucherSold                	= $VoucherSold;
		$this->PlanSold                		= $PlanSold;
		$this->isRefunded                	= $isRefunded;
		$this->PreviousAppointment         	= $PreviousAppointment;
		$this->PreviousServices             = $PreviousServices;
		$this->TotalSpend                	= $TotalSpend;
		$this->soldProduct                	= $soldProduct;
		$this->ClientProducts               = $ClientProducts;
		$this->clientInvoices               = $clientInvoices;
		$this->ClientInovices               = $ClientInovices;
		$this->UserInfo                		= $UserInfo;

	}

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->from_email,$this->from_name)
	                ->view('emailTemplates.voucherInvoice')
	                ->subject($this->subject);
    }
}

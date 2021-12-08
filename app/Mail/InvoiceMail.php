<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;
	
	public $from_email;
	public $from_name;
	public $subject;
	public $email_message;
	public $invoice;
	public $invoice_item_info;
	public $invoice_taxes;
	public $total_staff_tip;
	public $location_info;
	public $invoice_template;
	public $client_info;
	public $pdf_output;
	public $pdf_file;
	
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($FROM_EMAIL,$FROM_NAME,$SUBJECT,$MESSAGE,$Invoice,$InvoiceItemsInfo,$InvoiceTaxes,$TotalStaffTip,$LocationInfo,$InvoiceTemplate,$ClientInfo,$PDF_OUTPUT,$PDF_FILE)
	{
		$this->from_email        = $FROM_EMAIL;
		$this->from_name         = $FROM_NAME;
		$this->subject           = $SUBJECT;
		$this->email_message     = $MESSAGE;
		$this->invoice           = $Invoice;
		$this->invoice_item_info = $InvoiceItemsInfo;
		$this->invoice_taxes     = $InvoiceTaxes;
		$this->total_staff_tip   = $TotalStaffTip;
		$this->location_info     = $LocationInfo;
		$this->invoice_template  = $InvoiceTemplate;
		$this->client_info       = $ClientInfo;
		$this->pdf_output        = $PDF_OUTPUT;
		$this->pdf_file          = $PDF_FILE;
	}

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->from_email,$this->from_name)
	                ->view('emailTemplates.invoiceDetailsMail')
	                ->subject($this->subject)
					->attachData($this->pdf_output,$this->pdf_file);
    }
}

<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class forTippingNotification extends Mailable
{
    use Queueable, SerializesModels;
	
	public $from_email;
	public $from_name;
	public $subject;
	public $firstName;
	public $note;
	public $unique_id;
	public $is_price_view;
	public $locationData;
	public $AppointmentServices;
	public $service;
	public $appointment;
	public $remidernoti;
	public $InvoiceItemsInfo;
	public $Invoice;
	public $InvoiceTaxes;
	public $TotalStaffTip;
	
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($FROM_EMAIL,$FROM_NAME,$SUBJECT,$AppointmentServices = null,$Appointment = null,$UniqueId,$client = null, $service = null,$remidernoti = null,$firstName = null,$is_price_view = null,$note = null,$locationData = null, $InvoiceItemsInfo = null, $Invoice = null, $InvoiceTaxes = null, $TotalStaffTip = null)
	{
		$this->from_email    = $FROM_EMAIL;
		$this->from_name     = $FROM_NAME;
		$this->subject       = $SUBJECT;
		$this->unique_id     = $UniqueId;
		$this->firstName     = $firstName;
		$this->is_price_view = $is_price_view;
		$this->note          = $note;
		$this->locationData  = $locationData;
		$this->AppointmentServices   = $AppointmentServices;
		$this->service       = $service;
		$this->remidernoti   = $remidernoti;
		$this->appointment    = $Appointment;
		$this->InvoiceItemsInfo = $InvoiceItemsInfo;
		$this->Invoice 		 = $Invoice;
		$this->InvoiceTaxes  = $InvoiceTaxes;
		$this->TotalStaffTip = $TotalStaffTip;
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
		
		if(empty($appointment)) {
            return $this->from($this->from_email,$this->from_name)
	                ->view('emailTemplates.tippingNotification')
                    ->with(['first_name' => $this->firstName,'is_price_view' => $this->is_price_view,'note' => $this->note,'locationData' => $this->locationData, 'InvoiceItemsInfo' => $this->InvoiceItemsInfo,'Invoice' => $this->Invoice,'InvoiceTaxes'=>$this->InvoiceTaxes,'AppointmentServices'=>$this->AppointmentServices, 'TotalStaffTip'=>$this->TotalStaffTip])
	                ->subject($this->subject);
		}else{
			return $this->from($this->from_email,$this->from_name)
	                ->view('emailTemplates.tippingNotification')
                    ->with(['invoiceItem' => $this->invoiceItem,'firstName' => $this->firstName,'invoice' => $this->invoice, 'remider' => $this->remidernoti])
	                ->subject($this->subject);
		}
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceSequencing extends Model
{
    protected $table = 'invoice_sequencing';
    
    protected $fillable = [
        'user_id',
        'location_id',
        'invoice_no_prefix',
		'next_invoice_number',
    ];

	public static function getInvoiceSequence($userId = null, $locationId = null){
		$invoiceSequence = InvoiceSequencing::select('*')->where('user_id', $userId)->where('location_id', $locationId)->first();
		
		if(!empty($invoiceSequence)) {
			$invoiceNo = $invoiceSequence->next_invoice_number;
			$nextInvoiceNo = $invoiceNo + 1;
			
			$invoiceSequence->next_invoice_number = $nextInvoiceNo;
			$invoiceSequence->save();
			
			return array("prefix" => $invoiceSequence->invoice_no_prefix, "invoiceNo" => $invoiceNo);
		} else {
			$insInvoiceSequencing = InvoiceSequencing::create([
				'user_id' => $userId,
				'location_id' => $locationId,
				'invoice_no_prefix' => NULL,
				'next_invoice_number' => 2,
				'created_at' => date("Y-m-d H:i:s"),
				'updated_at' => date("Y-m-d H:i:s")
			]);
			
			return array("prefix" => NULL, "invoiceNo" => 1);
		}		
	}
}

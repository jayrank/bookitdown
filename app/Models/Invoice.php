<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table = 'invoice';

    protected $fillable = [
        'invoice_prefix',
        'invoice_id',
		'appointment_id',
        'location_id',
        'is_voucher_apply',
        'payment_id',
        'payment_type',
        'client_id',
        'invoice_sub_total',
        'invoice_total',
        'inovice_final_total',
        'tax_title',
        'tax_amount',
        'invoice_status',
        'notes',
        'original_invoice_id',
        'user_id',
        'created_by',
        'updated_by',
		'payment_date',
        'created_at',
        'updated_at',
    ];
	
	public static function getInvoicebyID($id = null){
		$Invoice = Invoice::select('*')->where('id', $id)->first();
		return $Invoice; 
	}
}

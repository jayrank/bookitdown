<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

	

class InvoiceTemplate extends Model
{
    protected $table = 'invoice_template';
    protected $fillable = [
        'user_id',
        'autoPrint',
        'showMobile',
        'showAddress',
		'title',
		'receiptLine1', 
		'receiptLine2', 
		'receiptfooter', 
    ];
}
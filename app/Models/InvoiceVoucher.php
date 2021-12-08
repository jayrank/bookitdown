<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceVoucher extends Model
{
    protected $table = 'invoice_voucher';

    protected $fillable = [
        'invoice_id',
        'location_id',
        'voucher_id',
        'voucher_code',
        'voucher_amount',
        'created_at',
        'updated_at',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceTaxes extends Model
{
    protected $table = 'invoice_taxes';

    protected $fillable = [
        'invoice_id',
        'location_id',
        'tax_id',
        'tax_rate',
        'tax_amount',
        'status',
        'created_at',
        'updated_at',
    ];
}

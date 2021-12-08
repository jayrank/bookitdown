<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceItems extends Model
{
    protected $table = 'invoice_items';

    protected $fillable = [
        'invoice_id',
        'item_id',
        'client_id',
        'item_type',
        'quantity',
        'item_og_price',
        'item_price',
        'staff_id',
        'item_discount_id',
        'item_discount_text',
        'is_void',
        'appointment_services_id',
        'item_tax_id',
        'item_tax_rate',
        'item_tax_amount',
        'created_at',
        'updated_at',
    ];
}

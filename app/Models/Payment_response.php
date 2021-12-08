<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;


class Payment_response extends Model
{
    protected $table = 'payment_response';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'invoice_id',
        'user_id',
        'type',
		'email_sms_id',
        'charge_id',
        'amount',
        'transaction_id',
		'card_brand',
		'card_last4',
        'currency',
		'card_id',
		'module_type',
		'module_text',
		'status',
        'status_text',
		'payment_response',
        'created_at'
    ];

    
}

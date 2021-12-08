<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class paymentType extends Model
{
    protected $table = 'payment_type';

    protected $fillable = [
        'user_id',
        'payment_type',
        'order_id',
        'is_deleted',
    ];
}
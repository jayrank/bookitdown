<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $table = 'discount';

    protected $fillable = [
        'user_id',
        'name',
        'value',
        'prType',
        'is_service',
        'is_product',
        'is_voucher',
        'is_plan',
        'is_deleted',
    ];
}
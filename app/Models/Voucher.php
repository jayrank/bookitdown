<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'value',
        'services_ids',
        'name',
        'retailprice',
		'validfor',
		'enable_sale_limit', 
		'numberofsales', 
		'title',
		'description', 
		'color',
		'button',
		'note',
		'voucher_type',
		'created_from',
		'isdelete',
        'is_online',
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];
}

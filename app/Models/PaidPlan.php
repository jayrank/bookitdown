<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;


class PaidPlan extends Model
{
    protected $table = 'paid_plan';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'name',
		'description',
        'services_ids',
        'sessions',
        'sessions_num',
        'price',
		'valid_for',
		'tax',
		'color',
		'online_sales',
        'is_deleted',
    ];

    
}

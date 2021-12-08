<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class StaffServices extends Model
{
    protected $table = 'staff_services';

    protected $fillable = [
        'staff_id',
		'staff_user_id', 
        'service_id',
        'status',
        'created_at',
        'updated_at',
    ];
}
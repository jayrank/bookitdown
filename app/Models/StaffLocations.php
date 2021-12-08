<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class StaffLocations extends Model
{
    protected $table = 'staff_locations';

    protected $fillable = [
        'staff_id',
		'staff_user_id', 
        'location_id',
        'created_at',
    ];
}
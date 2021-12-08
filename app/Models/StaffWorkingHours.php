<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffWorkingHours extends Model
{
   
    protected $table = 'staff_working_hours';
    protected $fillable = [
        'user_id',
        'location_id',
        'business_id',
        'staff_id',
        'date',
        'day',
        'start_time',
        'end_time',
        'repeats',
        'endrepeat',
        'remove_date',
        'remove_type',
    ];
    
   
}

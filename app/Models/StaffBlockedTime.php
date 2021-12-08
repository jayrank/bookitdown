<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffBlockedTime extends Model
{
   
    protected $table = 'staff_blocked_time';
    protected $fillable = [
        'user_id',
        'staff_user_id',
        'date',
        'start_time',
        'end_time',
        'allow_online_booking',
        'description'
    ];
    
   
}

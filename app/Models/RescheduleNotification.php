<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RescheduleNotification extends Model
{
    protected $table = 'reschedule_notification';
   
    protected $fillable = [
        'user_id',
        'is_rescheduleMessage',
        'is_displayServicePrice',
		'important_info',
    ];

}

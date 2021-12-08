<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReminderNotification extends Model
{
    protected $table = 'reminder_notification';
   
    protected $fillable = [
        'user_id',
        'is_reminderMessage',
        'is_displayServicePrice',
        'advance_notice',
        'additional_reminder',
		'important_info',
    ];

}

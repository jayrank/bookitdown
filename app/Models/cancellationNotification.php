<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class cancellationNotification extends Model
{
    protected $table = 'cancellation_notification';
   
    protected $fillable = [
        'user_id',
        'is_cancellationMessage',
        'is_displayServicePrice',
		'important_info',
    ];

}

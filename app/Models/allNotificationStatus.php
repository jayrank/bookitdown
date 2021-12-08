<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class allNotificationStatus extends Model
{
    protected $table = 'all_notification_status';
   
    protected $fillable = [
        'user_id',
        'status',
    ];

}

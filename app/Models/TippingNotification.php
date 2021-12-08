<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TippingNotification extends Model
{
    protected $table = 'tipping_notification';
   
    protected $fillable = [
        'user_id',
        'is_message',
        'is_displayServicePrice',
		'important_info',
    ];

}

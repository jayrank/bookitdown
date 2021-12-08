<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class thankyouNotification extends Model
{
    protected $table = 'thankyou_notification';
   
    protected $fillable = [
        'user_id',
        'is_thankyouMessage',
        'is_displayServicePrice',
		'important_info',
    ];

}

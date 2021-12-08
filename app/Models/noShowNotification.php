<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class noShowNotification extends Model
{
    protected $table = 'noshow_notification';
   
    protected $fillable = [
        'user_id',
        'is_noShowMessage',
        'is_displayServicePrice',
		'important_info',
    ];

}

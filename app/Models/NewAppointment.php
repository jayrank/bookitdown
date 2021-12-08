<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewAppointment extends Model
{
    protected $table = 'new_appointment';
   
    protected $fillable = [
        'user_id',
        'is_manuallyBook',
        'is_displayServicePrice',
		'important_info',
    ];

   
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Online_setting extends Model
{
    use HasFactory; //Import The Trait

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table="online_setting";
    protected $fillable = [
        'user_id',
        'appointment_cancel_time',
        'before_start_time',
		'future_time',
		'time_slot_interval', 
		'is_allowed_staff_selection',
        'important_info',
        'is_send_booked_staff',
        'is_specific_email',
        'email',
        'is_show_feature',
        'is_rating',
		'created_at',
		'updated_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];
}

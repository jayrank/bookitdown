<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store_timetable extends Model
{
    use HasFactory; //Import The Trait

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'store_timetable';
    protected $fillable = [
        'user_id',
        'location_id',
        'is_open_sunday',
        'sunday_open_time',
        'sunday_close_time',
        'is_open_monday',
        'monday_open_time',
        'monday_close_time',
        'is_open_tuesday',
        'tuesday_open_time',
        'tuesday_close_time',
        'is_open_wednesday',
        'wednesday_open_time',
        'wednesday_close_time',
        'is_open_thursday',
        'thursday_open_time',
        'thursday_close_time',
        'is_open_friday',
        'friday_open_time',
        'friday_close_time',
        'is_open_saturday',
        'saturday_open_time',
        'saturday_close_time',
        'created_at',
        'updated_at',
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

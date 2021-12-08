<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;


class Smslog extends Model
{
    protected $table = 'Smslog';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sms_id',
        'response',
        'sms_status',
    ];

    
}

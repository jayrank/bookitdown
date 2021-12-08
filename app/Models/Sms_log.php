<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;


class Sms_log extends Model
{
    protected $table = 'sms_log';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'client_id',
        'appointment_id',
		'group_sms_blast_id',
        'sms_id',
        'send_from',
        'send_to',
        'client_name',
        'message',
        'direction',
        'sms_status',
        'error_message',
        'module_type',
        'module_type_text',
        'sms_response',
        'created_at'
    ];

    
}

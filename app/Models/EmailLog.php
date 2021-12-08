<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class EmailLog extends Model
{
    protected $table = 'email_log';
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
		'unique_id',
        'campaign_id',
		'group_email_blast_id',
        'trans_id',
        'from_email',
        'to_email',
        'module_type',
        'module_type_text',
        'email_status',
		'is_sent',
        'response',
        'created_at',
    ];

    
}

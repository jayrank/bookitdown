<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group_sms_blast extends Model
{
    use HasFactory; //Import The Trait
    protected $table = 'group_sms_blast';
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'blast_sms_id',
        'client_id',
		'is_sent',
		'is_delivered',
		'is_clicked',
		'short_url',
		'long_url',
		'short_code',
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

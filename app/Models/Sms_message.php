<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sms_message extends Model
{
    use HasFactory; //Import The Trait

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
		'voucher_id',
        'message_name',
        'message_description',
        'is_link',
        'btn_url',
        'title',
        'discount_type',
        'discount_value',
        'appointment_limit',
        'valid_for',
        'services',
        'sms_type_int',
        'client_type',
        'group_type',
        'sms_type_text',
        'payment_status',
		'created_by',
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
	
	public function getGroupSmsBlast(){
        return $this->hasMany('App\Models\Group_sms_blast', 'blast_sms_id');
    }
	
	public function getCreator(){
        return $this->hasOne('App\Models\User', 'id','created_by');
    }
}

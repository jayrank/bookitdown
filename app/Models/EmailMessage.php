<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailMessage extends Model
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
        'email_subject',
        'reply_email',
        'title',
        'message',
        'is_button',
        'button_text',
        'button_link',
        'social_media_enable',
        'facebook_link',
        'instagram_link',
        'website',
        'discount_value',
        'discount_type',
        'appointment_limit',
        'valid_for',
        'message_price',
        'total_payable_price',
        'is_sended',
        'message_type_int',
        'message_type_text',
        'is_image',
        'services',
        'image_path',
        'client_type_int',
        'client_type',
        'background_color',
        'foreground_color',
        'font_color', 
        'line_color',       
        'button_color',
        'botton_text_color',
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
	
	public function getGroupEmailBlast(){
        return $this->hasMany('App\Models\Group_email_blast', 'blast_email_id');
    }
	
	public function getCreator(){
        return $this->hasOne('App\Models\User', 'id','created_by');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group_email_blast extends Model
{
    use HasFactory; //Import The Trait
    protected $table = 'group_email_blast';
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'blast_email_id',
        'client_id',
		'is_sent',
		'is_delivered',
		'is_opened',
		'is_clicked',
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
	
	public function getEmailMessage(){
        return $this->hasOne('App\Models\EmailMessage', 'blast_email_id');
    }
}

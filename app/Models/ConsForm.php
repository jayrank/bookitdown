<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsForm extends Model
{
   
    protected $table = 'consultation_form';

    protected $fillable = [
        'user_id',
        'name',
        'send_request',
		'status',
		'complete',
		'signature',
		'service_id', 
		'is_delete',
    ];

    public function qna(){
        return $this->hasMany('App\Models\conFormCustomSection', 'form_id');
    }

    public function client(){
        return $this->hasOne('App\Models\conFormClientDetails', 'form_id');
    }
}

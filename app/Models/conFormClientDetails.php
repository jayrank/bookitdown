<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class conFormClientDetails extends Model
{
   
    protected $table='conForm_clientDetails';
	public $timestamps = true;
    protected $fillable = [
        'user_id',
		'form_id',
		'section_id',
        'section_title',
        'section_des',
		'first_name',
		'last_name', 
		'email',
		'birthday', 
		'country_code',
		'mobile', 
		'gender', 
		'address', 
		'is_delete',
    ];

	public function form(){
        return $this->hasOne('App\Models\ConsForm', 'form_id');
    }
   
}

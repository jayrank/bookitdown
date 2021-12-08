<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class conFormCustomSection extends Model
{
   
    protected $table='conForm_custom_section';
	public $timestamps = true;
    protected $fillable = [
        'user_id',
		'form_id',
		'section_id',
        'title',
        'des',
        'required',
		'answer_type',
		'question', 
		'1ans',
		'2ans', 
		'3ans', 
		'4ans', 
		'5ans', 
		'6ans',
		'7ans',
		'8ans',
		'is_delete',
		
    ];

	public function form(){
        return $this->belongsTo('App\Models\ConsForm', 'form_id');
    }
}

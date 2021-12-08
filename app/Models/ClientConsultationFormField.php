<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientConsultationFormField extends Model
{
    use HasFactory; //Import The Trait
	use SoftDeletes; //add this line
	
	protected $table = 'client_consultation_form_field';
	
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'client_consultation_form_id',
		'fuser_id',
		'client_id',
		'user_id',
		'appointment_id',
		'location_id',
		'consultation_from_field_id',
		'section_id',
		'section_title',
		'section_description',
		'question',
		'is_required',
		'field_type',
		'field_values',
		'client_answer',
		'created_at',
		'updated_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];
	
	
	protected $dates = ['deleted_at'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];
	
	public function mainConsultationForm(){
        return $this->hasOne('App\Models\ClientConsultationForm', 'client_consultation_form_id');
    }
}

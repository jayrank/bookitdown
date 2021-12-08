<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientConsultationForm extends Model
{
    use HasFactory; //Import The Trait
	use SoftDeletes; //add this line
	
	protected $table = 'client_consultation_form';
	
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fuser_id',
		'client_id',
		'user_id',
		'appointment_id',
		'location_id',
		'complete_before',
		'consultation_from_id',
		'consultation_from_client_detail_id',
		'is_first_name',
		'client_first_name',
		'is_last_name',
		'client_last_name',
		'is_email',
		'client_email',
		'is_birthday',
		'client_birthday',
		'is_mobile',
		'country_code',
		'client_mobile',
		'is_gender',
		'client_gender',
		'is_address',
		'client_address',
		'status',
		'completed_at',
		'is_signature',
		'created_at',
		'updated_at',
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
	
	public function clientConsultationFields(){
        return $this->hasMany('App\Models\ClientConsultationFormField', 'client_consultation_form_id');
    }
}

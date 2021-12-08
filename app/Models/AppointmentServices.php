<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppointmentServices extends Model
{
    use HasFactory; //Import The Trait
	
	protected $table = 'appointment_services';
	
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'appointment_id',
		'user_id',
		'appointment_date',
		'start_time',
		'end_time',
		'service_price_id',
		'duration',
		'is_extra_time',
		'extra_time',
		'extra_time_duration',
		'staff_user_id',
		'special_price',
		'created_at',
		'updated_at'
    ];

    public function cat(){
        return $this->belongsTo('App\Models\Appointments', 'appointment_id');
    }
}

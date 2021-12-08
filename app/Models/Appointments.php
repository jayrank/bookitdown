<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointments extends Model
{
    use HasFactory; //Import The Trait
	
	protected $table = 'appointments';
	
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
		'location_id',
		'invoice_id',
		'staff_user_id',
		'appointment_date',
		'appointment_notes',
		'client_id',
		'total_amount',
		'voucher_id',
		'voucher_code',
		'discount_amount',
		'tip_type',
		'tip_amount',
		'final_total',
		'appointment_status',
		'payment_method',
		'is_paid',
		'is_online_appointment',
		'payment_received_by',
		'invoice_note',
		'created_by',
		'created_at',
		'updated_at'
    ];

    public function apservice(){
        return $this->hasMany('App\Models\AppointmentServices', 'appointment_id')
        		->leftJoin('services_price', 'services_price.id', 'appointment_services.service_price_id')
        		->leftJoin('services', 'services.id', 'services_price.service_id')
        		->select('appointment_services.*', 'services.service_name', 'services.service_description', 'services_price.price')
        		->orderBy('appointment_services.start_time','ASC');
    }

    public function invoice()
    {
    	return $this->belongsTo('App\Models\Invoice', 'invoice_id');
    }
}

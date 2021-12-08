<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
	
class Services extends Model
{
	protected $table = 'services';	
	
	protected $fillable = [
        'user_id','order_id','service_name','treatment_type','service_category','service_description','available_for','is_online_booking','staff_ids','is_staff_commision_enable','is_extra_time','extra_time','extra_time_duration','tax_id','is_voucher_enable','voucher_expiry_day','voucher_expiry_month','is_deleted','location_id'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ]; 
	 
    public function cat(){
        return $this->belongsTo('App\Models\ServiceCategory', 'service_category');
    }

    public function servicePrice(){
        return $this->hasMany('App\Models\ServicesPrice', 'service_id');
    }
}
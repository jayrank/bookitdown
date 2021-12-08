<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Staff extends Model
{
    use HasFactory; //Import The Trait

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'staff_user_id',
        'firstname',
        'lastname',
		'mobileno',
		'telephoneno', 
		'email', 
		'user_permission', 
		'staff_title', 
		'staff_notes',
		'is_appointment_booked',
		'appointment_color',
		'employment_start_date',
		'service_commission',
		'product_commission',
		'voucher_commission',
        'order_id',
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
	
    public function user(){
        return $this->belongsTo(User::class, 'staff_user_id');
    }
	
	public static function getStaffDetailByID($id = null){
		$Staff = Staff::select('staff.id as staff_id','staff.staff_user_id as user_id','users.first_name','users.last_name')->leftJoin('users', 'staff.staff_user_id', '=', 'users.id')->where('staff.staff_user_id', $id)->first();
		return $Staff; 
	}
	
	public static function getStaffDetailByStaffID($id = null){
		$Staff = Staff::select('staff.id as staff_id','staff.staff_user_id as user_id','users.first_name','users.last_name')->leftJoin('users', 'staff.staff_user_id', '=', 'users.id')->where('staff.id', $id)->first();
		return $Staff; 
	}
}

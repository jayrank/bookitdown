<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SoldPaidPlan extends Model
{
	/**
     * The table associated with the model.
     *
     * @var string
     */

    protected $table = 'sold_paidplan';
	
	protected $fillable = [
        'user_id','location_id','invoice_id','paidplan_id','client_id','service_id','price','valid_for','no_of_sessions','used_sessions','start_date','end_date','status'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

}
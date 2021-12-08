<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SoldVoucher extends Model
{
	/**
     * The table associated with the model.
     *
     * @var string
     */

    protected $table = 'sold_voucher';
	
	protected $fillable = [
        'user_id','location_id','invoice_id','client_id','service_id','voucher_id','voucher_code','total_value','redeemed','price','validfor','start_date','end_date','voucher_type','created_from','status','recipient_as','first_name','last_name','message','email'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function voucher()
    {
        return $this->belongsTo('App\Models\Voucher', 'voucher_id');
    }
}
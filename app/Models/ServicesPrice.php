<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
	
class ServicesPrice extends Model
{
	use HasFactory; //Import The Trait
	use SoftDeletes; //add this line
	
	protected $table = 'services_price';	
	
	protected $fillable = [
        'user_id','service_id','duration','price_type','price','special_price','is_staff_price','lowest_price','staff_prices','pricing_name'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ]; 

	protected $dates = ['deleted_at'];

    public function service(){
        return $this->belongsTo('App\Models\Services', 'service_id');
    }
	 
   
}
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
	
class ServiceCategory extends Model
{
	protected $table = 'service_category';	
	
	protected $fillable = [
        'user_id','order_id','category_title','appointment_color','category_description','is_deleted'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ]; 

    public function service(){
        return $this->hasMany('App\Models\Services', 'service_category')->where('is_deleted', 0);
    }
	 
   
}
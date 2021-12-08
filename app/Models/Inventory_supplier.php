<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory_supplier extends Model
{
    use HasFactory; //Import The Trait

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'supplier_name',
        'supplier_description',
        'first_name',
        'last_name',
        'mobile_country_code',
        'mobile',
        'email',
        'tel_country_code',
        'telephone',
        'website',
        'address',
        'suburb',
        'city',
        'state',
        'zip_code',
        'country',
		'is_postal_same',
		'postal_address',
        'postal_suburb',
        'postal_city',
        'postal_state',
        'postal_zip_code',
        'postal_country',
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
	
	public static function getSupplierbyID($id = null){
		$Inventory_supplier = Inventory_supplier::select('id','supplier_name','address','suburb','city','state','zip_code','country')->where('id', $id)->first();
		return $Inventory_supplier; 
	}
}

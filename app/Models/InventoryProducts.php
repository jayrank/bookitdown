<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryProducts extends Model
{
    use HasFactory; //Import The Trait
	use SoftDeletes; //add this line
	
	protected $table = 'inventory_products';
	
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
		'product_name',
		'category_id',
		'brand_id',
		'enable_retail_sale',
		'retail_price',
		'special_rate',
		'tax_id',
		'enable_commission',
		'barcode',
		'sku',
		'description',
		'enable_stock_control',
		'supply_price',
		'initial_stock',
		'average_price',
		'supplier_id',
		'reorder_point',
		'reorder_qty',
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
}

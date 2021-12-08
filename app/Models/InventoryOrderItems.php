<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryOrderItems extends Model
{
    use HasFactory; //Import The Trait
	
	protected $table = 'inventory_order_items';
	
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_id',
		'category_id',
		'product_id',
		'order_qty',
		'received_qty',
		'supply_price',
		'total_cost',
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
	
	public function inventory_orders() 
    {
        return $this->belongsTo(InventoryOrders::class);
    }
}

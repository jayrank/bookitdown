<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryOrders extends Model
{
    use HasFactory; //Import The Trait
	
	protected $table = 'inventory_orders';
	
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
		'supplier_id',
		'location_id',
		'order_date',
		'order_total',
		'order_status',
		'order_pdf',
		'order_created_by',
		'order_received_by',
		'order_cancelled_by',
		'received_at',
		'cancelled_at',
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
	
	public function inventory_order_tems() 
    {
        return $this->belongsToMany(InventoryOrderItems::class);
    }
}

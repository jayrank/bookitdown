<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryOrderLogs extends Model
{
    use HasFactory; //Import The Trait
	
	protected $table = 'inventory_order_logs';
	
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'item_id',
		'received_date',
		'received_by',
		'location_id',
		'supplier_id',
		'order_id',
		'invoice_id',
		'order_type',
		'order_action',
		'order_status',
		'qty_adjusted',
		'enable_stock_control',
		'is_void_invoice',
		'cost_price',
		'stock_on_hand',
		'average_price'
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
}

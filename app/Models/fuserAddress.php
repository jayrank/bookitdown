<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class fuserAddress extends Model
{
    use HasFactory; //Import The Trait
	
	protected $table = 'fuser_addresses';
	
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fuser_id',
		'address_type',
		'delivery_area',
		'complete_address',
		'delivery_instructions',
		'created_at',
		'updated_at'
    ];
}

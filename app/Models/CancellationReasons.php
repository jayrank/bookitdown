<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CancellationReasons extends Model
{
    use HasFactory; //Import The Trait
	
	protected $table = 'cancellation_reasons';
	
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
		'reason',
		'is_default',
		'created_at',
		'updated_at'
    ];
}

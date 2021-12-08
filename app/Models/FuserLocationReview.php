<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FuserLocationReview extends Model
{
    use HasFactory; //Import The Trait
	
	protected $table = 'fuser_location_review';
	
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fuser_id',
		'location_id',
        'appointment_id',
		'rating',
		'feedback',
		'created_at',
		'updated_at'
    ];

    public function fuser()
    {
    	return $this->belongsTo('App\Models\frontUser');
    }
}

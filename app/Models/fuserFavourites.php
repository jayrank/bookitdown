<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class fuserFavourites extends Model
{
    use HasFactory; //Import The Trait
	
	protected $table = 'fuser_favourites';
	
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fuser_id',
		'location_id',
		'created_at',
		'updated_at'
    ];
}

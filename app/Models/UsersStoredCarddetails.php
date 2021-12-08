<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UsersStoredCarddetails extends Model
{
    use HasFactory; //Import The Trait
	use SoftDeletes; //add this line
	
	protected $table = 'users_stored_carddetails';
	
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
		'customer_code',
		'card_token',
		'card_number',
		'card_expiry',
		'is_default',
		'response',
		'created_at',
		'updated_at'
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

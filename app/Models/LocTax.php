<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
	
class LocTax extends Model
{
    protected $table = 'loc_taxes';

    protected $fillable = [
        'user_id',
        'loc_id',
        'poducts_default_tax',
        'service_default_tax',
        'is_deleted',
    ];
	 
   
}
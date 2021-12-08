<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Taxes extends Model
{
   
    protected $table = 'taxes';

    protected $fillable = [
        'user_id',
        'tax_name',
        'tax_rates',
        'is_group',
        'is_deleted',
    ];

   
}

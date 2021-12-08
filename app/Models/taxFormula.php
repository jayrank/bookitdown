<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class taxFormula extends Model
{
    protected $table = 'tax_formula';

    protected $fillable = [
        'user_id',
        'tax_formula',
        'is_deleted',
    ];

}

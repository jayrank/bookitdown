<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class referralSources extends Model
{
    protected $table = 'referral_sources';

    protected $fillable = [
        'user_id',
        'name',
        'is_active',
        'order_id',
        'is_deleted',
    ];
}

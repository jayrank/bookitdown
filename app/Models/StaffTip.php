<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffTip extends Model
{
    protected $table = 'staff_tip';

    protected $fillable = [
        'staff_id',
        'location_id',
        'inovice_id',
        'tip_percentage',
        'tip_amount',
        'type',
        'status',
        'created_at',
        'updated_at',
    ];
}

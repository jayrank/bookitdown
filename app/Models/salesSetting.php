<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class salesSetting extends Model
{
    protected $table = 'sales_setting';

    protected $fillable = [
        'user_id',
        'salePriceBeforeDis',
        'salePriceIncludTax',
        'servicePriceBeforePaidPlanDis',
        'expiryPeriod',
    ];
}

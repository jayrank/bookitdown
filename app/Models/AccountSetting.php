<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class AccountSetting extends Model

{

    protected $table = 'account_settings';

    protected $fillable = [
        'user_id',
		'business_name', 
		'timezone', 
        'timeformat',
        'weekStart',
        'appointmentColorSource',
        'communicationLanguageCode',
		'employeeLanguageCode', 
		'website', 
		'facebook', 
		'Instagram', 
    ];
}
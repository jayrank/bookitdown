<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Smart_campaign extends Model
{
    use HasFactory; //Import The Trait

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'email_subject',
        'default_campaign_id',
        'headline_text',
        'body_text',
        'discount_value',
        'discount_type',
        'day_before_birthday',
        'appoinment_limit',
        'min_sales_count',
        'max_month_considered',
        'min_month_since_last_sale',
        'min_amount_type',
        'at_least_spent_amount',
        'client_type',
        'valid_for',
        'services',
        'image_path',
        'enable_sales',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];
}

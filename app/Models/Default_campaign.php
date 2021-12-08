<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Default_campaign extends Model
{
    protected $table = 'default_campaign';

    protected $fillable = [
        'type',
        'default_content',
        'email_subject',
        'headline_text',
        'body_text',
        'discount_type',
        'discount_value',
        'appoinment_limit',
        'valid_for',
        'client_title',
        'client_content',
        'is_editable_client',
        'edit_model_type',
        'impage_path',
        'is_enable',
        'card_icon',
    ];
}
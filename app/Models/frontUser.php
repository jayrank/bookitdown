<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;

class frontUser extends Model implements Authenticatable
{
    use AuthenticableTrait;

    protected $guard = 'fuser';

    protected $table = 'fuser';

    protected $fillable = ['name','last_name','email','mobile', 'password'];
}

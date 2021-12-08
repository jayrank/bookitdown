<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Staff;

class Permission extends Model
{
    use HasFactory;
	
	public function roles() {
		
		$CurrentUser = auth::user();
		$is_admin = $CurrentUser->is_admin;
		
		if($is_admin == 1){
			$CurrentStaff = Staff::select('*')->where('staff_user_id',$CurrentUser->id)->first();
			$AdminId = $CurrentStaff->user_id;
			$UserId  = Auth::id();
		} else {
			$AdminId = Auth::id();
			$UserId  = Auth::id();
		}
		
		//return $this->belongsToMany(Role::class,'roles_permissions')->where('roles_permissions.user_id', '=', $AdminId);
		return $this->belongsToMany(Role::class,'roles_permissions');
	}

	public function users() {

		return $this->belongsToMany(User::class,'users_permissions');
		   
	}
}

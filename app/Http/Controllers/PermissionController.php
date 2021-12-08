<?php
namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Models\RolePermission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{   

    public function Permission()
    {   
		$admin_role = Role::where('slug','admin')->first();
		$admin_permission = Permission::select('*')->get();
		
		$INSERTED_USER_ID = 39;
		
		$permissionLists = Permission::select('id','slug')->get();
		
		foreach($permissionLists as $key => $val) {
			
			$insRolePermission1 = new RolePermission();
			$insRolePermission1->user_id = $INSERTED_USER_ID;
			$insRolePermission1->role_id = 1;
			$insRolePermission1->permission_id = $val->id;
			$insRolePermission1->timestamps = false;
			$insRolePermission1->save();
			
			$insRolePermission2 = new RolePermission();
			$insRolePermission2->user_id = $INSERTED_USER_ID;
			$insRolePermission2->role_id = 2;
			$insRolePermission2->permission_id = $val->id;
			$insRolePermission2->timestamps = false;
			$insRolePermission2->save();
			
			$insRolePermission3 = new RolePermission();
			$insRolePermission3->user_id = $INSERTED_USER_ID;
			$insRolePermission3->role_id = 3;
			$insRolePermission3->permission_id = $val->id;
			$insRolePermission3->timestamps = false;
			$insRolePermission3->save();
		
			$insRolePermission4 = new RolePermission();
			$insRolePermission4->user_id = $INSERTED_USER_ID;
			$insRolePermission4->role_id = 4;
			$insRolePermission4->permission_id = $val->id;
			$insRolePermission4->timestamps = false;
			$insRolePermission4->save();
		
			$insRolePermission5 = new RolePermission();
			$insRolePermission5->user_id = $INSERTED_USER_ID;
			$insRolePermission5->role_id = 5;
			$insRolePermission5->permission_id = $val->id;
			$insRolePermission5->timestamps = false;
			$insRolePermission5->save();
		}
		
		$userInsert = User::find(39);
		$userInsert->roles()->attach($admin_role);
		$userInsert->permissions()->attach($admin_permission);
		
		/* $userUpd = User::find(14);
		$permision = array(1,2,3);

		$userUpd->roles()->sync(2);
		$userUpd->permissions()->sync($permision);
		echo "<pre>";
		die; */
		
    	/* $dev_permission = Permission::where('slug','create-tasks')->first();
		$manager_permission = Permission::where('slug', 'edit-users')->first();

		//RoleTableSeeder.php
		$admin_role = new Role();
		$admin_role->slug = 'admin';
		$admin_role->name = 'Admin';
		$admin_role->save();
		$admin_role->permissions()->attach($dev_permission);

		$staff_role = new Role();
		$staff_role->slug = 'staff';
		$staff_role->name = 'Staff';
		$staff_role->save();
		$staff_role->permissions()->attach($manager_permission);

		$admin_role = Role::where('slug','admin')->first();
		$staff_role = Role::where('slug', 'staff')->first();

		$createTasks = new Permission();
		$createTasks->slug = 'client';
		$createTasks->name = 'Create Client';
		$createTasks->save();
		$createTasks->roles()->attach($admin_role);
		
		$editUsers = new Permission();
		$editUsers->slug = 'inventory';
		$editUsers->name = 'Inventroy';
		$editUsers->save();
		$editUsers->roles()->attach($admin_role);
		$editUsers->roles()->attach($staff_role);

		$admin_role = Role::where('slug','admin')->first();
		$staff_role = Role::where('slug', 'staff')->first();
		$admin_perm = Permission::where('slug','client')->first();
		$staff_perm = Permission::where('slug','inventory')->first();

		$admin = new User();
		$admin->name = 'Admin';
		$admin->email = 'admin@gmail.com';
		$admin->password = bcrypt('12345678');
		$admin->save();
		$admin->roles()->attach($admin_role);
		$admin->permissions()->attach($admin_perm);
		$admin->permissions()->attach($staff_perm);

		$staff = new User();
		$staff->name = 'DG Staff';
		$staff->email = 'staff@gmail.com';
		$staff->password = bcrypt('12345678');
		$staff->save();
		$staff->roles()->attach($staff_role);
		$staff->permissions()->attach($staff_perm); */

		echo "Done";
		die;
		//return redirect()->back();
    }
}

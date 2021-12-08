<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\SalesClient;
use DB;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot(Request $request)
    {
        $this->registerPolicies();
		
		/*if( $request->has('test') ) 
		{*/
			View::composer('*', function($view)
			{
				if (Auth::check()) 
				{ 
					$curretn = Auth::user();
					$checkData = array();
					if($curretn->is_admin == 0) {
						$checkData = SalesClient::select('id','is_hub_access','is_menu_access','username','domain_name')->where('sd_user_id',$curretn->id)->where('username',$curretn->email)->first();							
					}
					
					$view->with('switchData', $checkData);
				}
			});
		//}	
		
        //
    }
}

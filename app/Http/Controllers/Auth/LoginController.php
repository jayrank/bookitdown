<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
	
	protected function attemptLogin(\Illuminate\Http\Request $request)
	{
		return $this->guard()->attempt(
			$this->credentials($request), $request->has('remember')
		);
	}
	
	protected function authenticated(\Illuminate\Http\Request $request, $user)
	{
		if ($request->ajax())
		{	
			if($user->is_active == 0) {
				$request->session()->flush();
				$request->session()->regenerate();
				$arr = array(
					'email' => array("You account deactivate.")
				);
				return response()->json(['success' => false, 'errors' => $arr], 422);
			} else {		
				return response()->json([
					'auth' => auth()->check(),
					'user' => $user,
					'intended' => $this->redirectPath(),
				]);
			}
		}
	}
}

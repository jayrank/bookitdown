<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\frontUser;
use App\Models\ServiceCategory;
use App\JsonReturn;
use Socialite;
use Exception;
use Hash;
use App\Models\Businesstype;
use App\Models\Location;
use App\Models\Appointments;
use App\Models\User;
use Str;
use App\Mail\ResetPassword;
use Mail;

class frontController extends Controller
{
    
    public function __construct()
    {
        // $this->middleware('FUser');
    }

   
    public function index()
    {
        return view('frontend.index',compact('locationData'));
    }
	
	public function login(Request $request){

        if ($request->isMethod('post')) {
            $rules = [
                'email' => 'required',
                'password' => 'required'
            ];
    
            $input = $request->only(
                'email',
                'password'
            );
            
            $validator = Validator::make($input, $rules);
            if ($validator->fails()) {
                return JsonReturn::error($validator->messages());
            }
            $data = $request->all();

            if (Auth::guard('fuser')->attempt(['email' => $data['email'], 'password' => $data['password']])) {

                $user = Auth::guard('fuser')->user();
                $cat = Businesstype::get();
                $ap = Appointments::get();
                $salon = User::where('is_admin',1)->get();
                $staff = User::where('is_admin',0)->get();
                $locationData = Location::where('is_deleted',0)->get();

                return view('frontend/dashboard',compact('cat','ap','salon','staff','locationData'));
                
            } else {
                return redirect()->back()->with('worngpass','Email Or Password was wrong!');
            }
        } else {
            return view('frontend.login');
        }
    }

    public function signup(Request $request){

        if ($request->isMethod('post')) {
            $rules = [
                'email' => 'required|unique:fuser',
                'name' => 'required',
                'password' => 'required'
            ];
    
            $message = ([
                "email.required" => "Enter Email",
                "name.required" => "Enter Name",
                "password.required" => "Enter Password",
            ]);
            
            $validator = Validator::make($request->all(), $rules, $message);

            if ($validator->fails()) {
                $messages = $validator->messages();
                return redirect()->back()->withErrors($messages);
            }

            $fuser = frontUser::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            return redirect('flogin')->with('signup','Signup Successfully!');
        } else {
            return view('frontend.signup');
        }
    }

    public function redirect($service) {
        return Socialite::driver($service)->redirect();
    }

    public function callback($service) 
	{
        try {
            $user = Socialite::driver($service)->user();
            $getuser = frontUser::where('email',$user->getEmail())->first();
            if($getuser){
                Auth::guard('fuser')->loginUsingId($getuser->id);
            } else {
                $create['name'] = $user->getName();
                $create['email'] = $user->getEmail();
                $create['passwor'] = $user->getId();

                $userModel = new frontUser;
                $createdUser = $userModel->addNew($create);
                Auth::guard('fuser')->loginUsingId($createdUser->id);
            }

            // return view('frontend.index')->withDetails($user)->withService($service);
            return redirect('/');

        } catch (Exception $e) {
            return redirect('flogin');
        }
        
    }

    public function logout()
    {
        Auth::guard('fuser')->logout();

        return redirect('/')->with('logout', 'Logout Successfully!');
    }

    public function forgot_password(Request $request)
    {
        if($request->ajax()) {

            $rules = [
                'email' => 'required'
            ];
            
            $input = $request->only(
                'email'
            );
            
            $validator = Validator::make($input, $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false, 
                    'message' => implode("<br>",$validator->messages()->all())
                ], 200);
            }

            $token = Str::random(64);

            frontUser::where('email', $request->email)->update(['token' => $token]);
            $frontUser = frontUser::where('email', $request->email)->first();
                                        
            $SendMail = Mail::to($request->email)->send(new ResetPassword($token, $request->name, $request->email)); 

            return response()->json([
                'status' => true,
                'message' => 'Password reset link sent to email.'
            ], 200);
        }
        return view('frontend.forgot_password');
    }

    public function reset_password(Request $request, $token, $email)
    {
        $email = urldecode($email);
        $frontUser = frontUser::where([
            'email' => $email,
            'token' => $token
        ]);

        if($request->isMethod('post')) {
            $rules = [
                'password' => 'required|confirmed'
            ];
            
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $messages = $validator->messages();
                return redirect()->back()->with('error',implode("<br>",$validator->messages()->all()));
            }

            frontUser::where([
                        'email' => $email,
                        'token' => $token
                    ])->update([
                        'password' => Hash::make($request->password),
                        'token' => NULL
                    ]);

            return redirect(route('flogin'))->with('success','Password updated successfully.');
        }

        return view('frontend.reset_password', compact('token', 'email'));
    }

    public function privacy_policy()
    {
        return view('frontend/privacy_policy');
    }

    public function website_terms()
    {
        return view('frontend/website_terms');
    }

    public function booking_terms()
    {
        return view('frontend/booking_terms');
    }

    public function for_partners()
    {
        return view('frontend/for_partners');
    }

    public function pricing()
    {
        return view('frontend/pricing');
    }
}

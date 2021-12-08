<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\AccountSetting;
use App\Models\Staff;
use Str;
use App\Mail\PartnerResetPassword;
use Mail;
use Hash;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;



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

            $user = User::where('email', $request->email)->first();
            if(empty($user)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Email not found in records.'
                ], 200);                
            }


            $is_admin = $user->is_admin;
            
            if($is_admin == 1){
                $CurrentStaff = Staff::select('user_id')->where('staff_user_id',$user->id)->first();
                $AdminId = $CurrentStaff->user_id;
                $UserId  = $user->id;
            } else {
                $AdminId = $user->id;
                $UserId  = $user->id;
            }
            $ac = AccountSetting::where('user_id',$AdminId)->first();
            if(!empty($ac)) {
                $locationName = $ac->business_name;
            } else {
                $locationName = '';
            }

            $user->token = $token;
            $user->save();
                                        
            $SendMail = Mail::to($request->email)->send(new PartnerResetPassword($token, $user->first_name, $request->email, $locationName)); 

            return response()->json([
                'status' => true,
                'message' => 'Password reset link sent to email.'
            ], 200);
        }
        return view('auth.forgot_password');
    }

    public function reset_password(Request $request, $token, $email)
    {
        $email = urldecode($email);
        $user = User::where([
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

            User::where([
                        'email' => $email,
                        'token' => $token
                    ])->update([
                        'password' => Hash::make($request->password),
                        'token' => NULL
                    ]);

            return redirect(route('login'))->with('success','Password updated successfully.');
        }

        return view('auth.reset_password', compact('token', 'email'));
    }
}

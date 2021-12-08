<?php
namespace App\Http\Controllers\Front;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

use App\JsonReturn;
use App\Models\frontUser;
use App\Models\fuserAddress;
use DB;
use Crypt;
use Session;
  
class ProfileController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
		$currentRoute = Route::currentRouteName();
		$this->middleware('FUser');
    }

    public function index()
    {
        $fuserId = Auth::guard('fuser')->user()->id;

        $frontUser = frontUser::find($fuserId);

        return view('frontend.profile.index', compact('frontUser'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'last_name' => 'max:255',
            'mobile' => 'max:12',
        ]);

        $fuserId = Auth::guard('fuser')->user()->id;

        $frontUser = frontUser::find($fuserId);
        $frontUser->name = $request->name;
        $frontUser->last_name = $request->last_name;
        $frontUser->mobile = $request->mobile;
        $frontUser->save();


        Session::flash('message', 'Profile has been updated successfully.');
        return redirect(url('profile'));

    }

    public function getAddresses()
    {
        $data = [];
        $data['data'] = [];
        $data["status"] = false;
        $data["message"] = "Something went wrong!";

        $fuserId = Auth::guard('fuser')->user()->id;

        if(empty($fuserId)) {

            $data["status"] = false;
            $data["message"] = "Please login first.";
        } else {
            $addresses = fuserAddress::where('fuser_id', $fuserId)->get();

            $data['data'] = $addresses;
            $data["status"] = true;
            $data["message"] = "Addresses fetched successfully!";
        }

        return JsonReturn::success($data);
    }

    public function addAddress(Request $request)
    {

        $data = [];
        $data["status"] = false;
        $data["message"] = "Something went wrong!";

        $validator = Validator::make($request->all(), [
            'delivery_area' => 'required',
            'complete_address' => 'required',
            'delivery_instructions' => 'required',
            'address_type' => 'required'
        ]);
        
        if ($validator->fails()) {
            $errors = $validator->errors()->first();

            $data["status"] = false;
            $data["message"] = $errors;

            return JsonReturn::success($data);
        }

        $fuserAddress = fuserAddress::create([
            'fuser_id' => Auth::guard('fuser')->user()->id,
            'address_type' => $request->address_type,
            'delivery_area' => $request->delivery_area,
            'complete_address' => $request->complete_address,
            'delivery_instructions' => $request->delivery_instructions,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        
        $data["status"] = true;
        $data["message"] = 'Address has been added successfully.';

        return JsonReturn::success($data);

    }

    public function updateAddress(Request $request)
    {

        $data = [];
        $data["status"] = false;
        $data["message"] = "Something went wrong!";

        $validator = Validator::make($request->all(), [
            'delivery_area' => 'required',
            'complete_address' => 'required',
            'delivery_instructions' => 'required',
            'address_type' => 'required'
        ]);
        
        if ($validator->fails()) {
            $errors = $validator->errors()->first();

            $data["status"] = false;
            $data["message"] = $errors;

            return JsonReturn::success($data);
        }

        if(!empty($request->address_id)) {

            $fuserAddress = fuserAddress::find($request->address_id);

            if($fuserAddress->fuser_id == Auth::guard('fuser')->user()->id) {
                $fuserAddress->address_type = $request->address_type;
                $fuserAddress->delivery_area = $request->delivery_area;
                $fuserAddress->complete_address = $request->complete_address;
                $fuserAddress->delivery_instructions = $request->delivery_instructions;
                $fuserAddress->created_at = date('Y-m-d H:i:s');
                $fuserAddress->updated_at = date('Y-m-d H:i:s');

                $fuserAddress->save();
                
                $data["status"] = true;
                $data["message"] = 'Address has been added successfully.';
            } else {
                $data["status"] = false;
                $data["message"] = 'You are not authorized.';
            }
        } else {
            $data["status"] = false;
            $data["message"] = 'Something went wrong. Please try again.';
        }

        return JsonReturn::success($data);

    }

    public function deleteAddress(Request $request)
    {

        $data = [];
        $data["status"] = false;
        $data["message"] = "Something went wrong!";

        if(!empty($request->address_id)) {

            $fuserAddress = fuserAddress::find($request->address_id);

            if($fuserAddress->fuser_id != Auth::guard('fuser')->user()->id) {
                $data["status"] = false;
                $data["message"] = 'You are not authorized.';
            } else {

                $fuserAddress->delete();
                
                $data["status"] = true;
                $data["message"] = 'Address has been deleted successfully.';
            }
        } else {
            $data["status"] = false;
            $data["message"] = 'Received empty request.';
        }

        return JsonReturn::success($data);
    }

    public function savePaymentDetail(Request $request)
    {
        if($request->ajax())
        {
            if($fuserId = Auth::guard('fuser')->user()->id) {
                $frontUser = frontUser::find($fuserId);
                $frontUser->card_number = $request->card_number;
                $frontUser->expiry_date = $request->expiry_date;
                $frontUser->cvv = $request->cvv;
                $frontUser->name_on_card = $request->name_on_card;
                $frontUser->updated_at = date('Y-m-d H:i:s');
                $frontUser->save();
                
                $data["status"] = true;
                $data["message"] = 'Payment detail has been saved.';
            } else {
                $data["status"] = false;
                $data["message"] = 'You are not authorized.';
            }
        }
        else {
            $data["status"] = false;
            $data["message"] = 'Something went wrong.';
        }
        return JsonReturn::success($data);
    }

    public function changePassword(Request $request)
    {

        $data = [];
        $data["status"] = false;
        $data["message"] = "Something went wrong!";

        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required',
            'confirm_password' => 'required|same:new_password'
        ]);
        
        if ($validator->fails()) {
            $errors = $validator->errors()->first();

            $data["status"] = false;
            $data["message"] = $errors;

            return JsonReturn::success($data);
        }

        $frontUser = frontUser::find(Auth::guard('fuser')->user()->id);

        if(Hash::check($request->current_password, $frontUser->password)) {
            $frontUser->password = Hash::make($request->new_password);
            $frontUser->save();
            
            $data["status"] = true;
            $data["message"] = 'Password has been updated successfully.';
        } else {
            $data["status"] = false;
            $data["message"] = 'Current password is incorrect.';
        }

        return JsonReturn::success($data);

    }
}

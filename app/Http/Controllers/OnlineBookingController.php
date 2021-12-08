<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Location;
use App\Models\AccountSetting;
use App\Models\Businesstype;
use App\Models\Store_timetable;
use App\Models\Online_setting;
use App\Models\User;
use App\Models\Time;
use App\Models\Services;
use App\Models\Location_image;
use App\Models\FuserLocationReview;
use App\Models\Staff;
use Session;
use App\JsonReturn;
use File;
use DB;
use Crypt;


class OnlineBookingController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
        date_default_timezone_set('Asia/Kolkata');
    }

	public function index()
	{
		return redirect(route('online_profile'));
		return view('online_booking.index');
	}   

	public function online_profile()
	{
		$CurrentUser = auth::user();
		$is_admin = $CurrentUser->is_admin;
		
		if($is_admin == 1){
			$CurrentStaff = Staff::select('user_id')->where('staff_user_id',$CurrentUser->id)->first();
			$AdminId = $CurrentStaff->user_id;
			$UserId  = Auth::id();
		} else {
			$AdminId = Auth::id();
			$UserId  = Auth::id();
		}
		
		$locationData = Location::where(['user_id' => $AdminId,'is_deleted'=>0])->get();
		$locationImages = Location_image::where('user_id', $AdminId)->get()->toArray();
		return view('online_booking.online_profile',compact('locationData','locationImages'));
	}

	public function edit_online_profile($id = null, $makeLocationOnline = false)
	{
		if($id != "")
		{
			$CurrentUser = auth::user();
			$is_admin = $CurrentUser->is_admin;
			
			if($is_admin == 1){
				$CurrentStaff = Staff::select('user_id')->where('staff_user_id',$CurrentUser->id)->first();
				$AdminId = $CurrentStaff->user_id;
				$UserId  = Auth::id();
			} else {
				$AdminId = Auth::id();
				$UserId  = Auth::id();
			}
			
			$user = User::where('id', $AdminId)->first();
			$locationData = Location::where(['user_id' => $AdminId,'id'=>$id,'is_deleted'=>0])->first();
			$storeTimetable = Store_timetable::where(['user_id' => $AdminId,'location_id'=>$id])->first();
			$times = Time::get();
			$bussinessData = Businesstype::select('id','name')->where('id',$user->business_type)->first();
			$locationImages = Location_image::where('location_id',$id)->get()->toArray();
			if(!empty($locationData))
			{
				return view('online_booking.edit_online_profile',compact('locationData','bussinessData','storeTimetable','times','locationImages', 'makeLocationOnline'));
			}
			else
			{
				Session::flash('error','Sorry! Location data not found');
				return redirect(url('partners/online_booking/online_profile'));		
			}
		}
		else
		{
			return redirect(url('partners/online_booking/online_profile'));
		}
	}

	public function saveOnlineProfile(Request $request)
	{
		if($request->location_id != "")
		{
			$CurrentUser = auth::user();
			$is_admin = $CurrentUser->is_admin;
			
			if($is_admin == 1){
				$CurrentStaff = Staff::select('user_id')->where('staff_user_id',$CurrentUser->id)->first();
				$AdminId = $CurrentStaff->user_id;
				$UserId  = Auth::id();
			} else {
				$AdminId = Auth::id();
				$UserId  = Auth::id();
			}
			
			$location = Location::find($request->location_id);
			$location->no_business_address = ($request->no_business_address) ? $request->no_business_address : 0;
			
			if($location->no_business_address == 1)
			{
				$location->location_address = NULL;
				$location->location_latitude = NULL;
				$location->location_longitude = NULL;
				$location->loc_address = NULL;
				$location->loc_apt = NULL;
				$location->loc_district = NULL;
				$location->loc_city = NULL;
				$location->loc_region = NULL;
				$location->loc_county = NULL;
				$location->loc_postcode = NULL;
				$location->loc_country = NULL;
			} else {
				
				$location->location_address = $request->location_address;
				$location->location_latitude = $request->lat;
				$location->location_longitude = $request->lng;
				$location->loc_address = $request->loc_address;
				$location->loc_apt = $request->loc_apt;
				$location->loc_district = $request->loc_district;
				$location->loc_city = $request->loc_city;
				$location->loc_region = $request->loc_region;
				$location->loc_county = $request->loc_county;
				$location->loc_postcode = $request->loc_postcode;
				$location->loc_country = $request->loc_country;
			}
			$location->is_same_billing_addr = $request->is_same_billing_addr;
			$location->billing_company_name = $request->billing_company_name;
            $location->billing_address = $request->billing_address;
            $location->billing_apt = $request->billing_apt;
            $location->billing_city = $request->billing_city;
            $location->billing_region = $request->billing_region;
            $location->billing_postcode = $request->billing_postcode;
            $location->billing_notes = $request->billing_notes;	
            $location->location_description = $request->location_description;
            $location->available_for = $request->available_for;

            $serviceData = Services::whereRaw('find_in_set(\''.$request->location_id.'\',location_id)')->get();
            $updateService['available_for'] = $request->available_for;
            Services::whereRaw('find_in_set(\''.$request->location_id.'\',location_id)')
            		->update($updateService);
           
			if($files=$request->file('files'))
	        {
	            foreach($files as $key => $file)
	            {
	                $name = rand().'.'.$file->extension();
	                $destination = base_path() . '/public/uploads/location_images/';
	                $file->move($destination, $name);
	                Location_image::create([
	                	'user_id'                 => $AdminId,
	                	'location_id'			  => $request->location_id,
	                	'image_path'			  => '/public/uploads/location_images/'.$name,
	                ]);
	            }
	        }
	        if($coverimage=$request->file('profile_avatar'))
	        {
	        	$coverName = rand().'.'.$coverimage->extension();
                $destination = base_path() . '/public/uploads/location_images/';
                $coverimage->move($destination, $coverName);
                $location->location_image = '/public/uploads/location_images/'.$coverName;
	        }
	        $location->save();
	        if($request->storetimetableid == "")
	        {
		        $storeStatus = Store_timetable::create([
		        	'user_id'              => 	$AdminId,
			        'location_id'          => 	$request->location_id,
			        'is_open_sunday'       => 	($request->is_open_sunday) ?  $request->is_open_sunday : 1,
			        'sunday_open_time'     => 	$request->sunday_open_time,
			        'sunday_close_time'    => 	$request->sunday_close_time,
			        'is_open_monday'       => 	($request->is_open_monday) ?  $request->is_open_monday : 1,
			        'monday_open_time'     => 	$request->monday_open_time,
			        'monday_close_time'    => 	$request->monday_close_time,
			        'is_open_tuesday'      => 	($request->is_open_tuesday) ?  $request->is_open_tuesday : 1,
			        'tuesday_open_time'    => 	$request->tuesday_open_time,
			        'tuesday_close_time'   => 	$request->tuesday_close_time,
			        'is_open_wednesday'    => 	($request->is_open_wednesday) ?  $request->is_open_wednesday : 1,
			        'wednesday_open_time'  => 	$request->wednesday_open_time,
			        'wednesday_close_time' => 	$request->wednesday_close_time,
			        'is_open_thursday'     => 	($request->is_open_thursday) ?  $request->is_open_thursday : 1,
			        'thursday_open_time'   => 	$request->thursday_open_time,
			        'thursday_close_time'  => 	$request->thursday_close_time,
			        'is_open_friday'       => 	($request->is_open_friday) ?  $request->is_open_friday : 1,
			        'friday_open_time'     => 	$request->friday_open_time,
			        'friday_close_time'    => 	$request->friday_close_time,
			        'is_open_saturday'     => 	($request->is_open_saturday) ?  $request->is_open_saturday : 1,
			        'saturday_open_time'   => 	$request->saturday_open_time,
			        'saturday_close_time'  => 	$request->saturday_close_time,
			        'created_at'           => 	date('Y-m-d H:i:s'),
			        'updated_at'           => 	date('Y-m-d H:i:s'),
		        ]);
	        } else {
	        	$storeUpdate = Store_timetable::find($request->storetimetableid);
				$storeUpdate->is_open_sunday       = 	($request->is_open_sunday) ?  $request->is_open_sunday : 0;
				$storeUpdate->sunday_open_time     = 	$request->sunday_open_time;
				$storeUpdate->sunday_close_time    = 	$request->sunday_close_time;
				$storeUpdate->is_open_monday       = 	($request->is_open_monday) ?  $request->is_open_monday : 0;
				$storeUpdate->monday_open_time     = 	$request->monday_open_time;
				$storeUpdate->monday_close_time    = 	$request->monday_close_time;
				$storeUpdate->is_open_tuesday      = 	($request->is_open_tuesday) ?  $request->is_open_tuesday : 0;
				$storeUpdate->tuesday_open_time    = 	$request->tuesday_open_time;
				$storeUpdate->tuesday_close_time   = 	$request->tuesday_close_time;
				$storeUpdate->is_open_wednesday    = 	($request->is_open_wednesday) ?  $request->is_open_wednesday : 0;
				$storeUpdate->wednesday_open_time  = 	$request->wednesday_open_time;
				$storeUpdate->wednesday_close_time = 	$request->wednesday_close_time;
				$storeUpdate->is_open_thursday     = 	($request->is_open_thursday) ?  $request->is_open_thursday : 0;
				$storeUpdate->thursday_open_time   = 	$request->thursday_open_time;
				$storeUpdate->thursday_close_time  = 	$request->thursday_close_time;
				$storeUpdate->is_open_friday       = 	($request->is_open_friday) ?  $request->is_open_friday : 0;
				$storeUpdate->friday_open_time     = 	$request->friday_open_time;
				$storeUpdate->friday_close_time    = 	$request->friday_close_time;
				$storeUpdate->is_open_saturday     = 	($request->is_open_saturday) ?  $request->is_open_saturday : 0;
				$storeUpdate->saturday_open_time   = 	$request->saturday_open_time;
				$storeUpdate->saturday_close_time  = 	$request->saturday_close_time;
				$storeUpdate->updated_at           = 	date('Y-m-d H:i:s');
				$storeUpdate->save();
	        }

	        $makeLocationOnline = isset($request->makeLocationOnline) ? $request->makeLocationOnline : false;
	        if($makeLocationOnline == 1) {
	        	return redirect(route('confirmOnlineLocation', ['locationId' => $request->location_id]));
	        } else {
		        Session::flash('success', 'Online profile has been saved successfully.');
	            return redirect(route('online_profile'));
	        }
		}
	}
	
	public function deleteLocationImage(Request $request)
	{
		if($request->id != "")
		{
			$status = Location_image::where('id',$request->id)->delete();
			if($status)
			{
				$data['status'] = true;
		        $data['message'] = 'Image has been deleted';
		        return JsonReturn::success($data);
			} else {
				$data['status'] = false;
		        $data['message'] = 'Something went wrong.';
		        return JsonReturn::success($data);
			}
		}
	}
	
	public function settings()
	{
		$CurrentUser = auth::user();
		$is_admin = $CurrentUser->is_admin;
		
		if($is_admin == 1){
			$CurrentStaff = Staff::select('user_id')->where('staff_user_id',$CurrentUser->id)->first();
			$AdminId = $CurrentStaff->user_id;
			$UserId  = Auth::id();
		} else {
			$AdminId = Auth::id();
			$UserId  = Auth::id();
		}
		
		$online_booking = Online_setting::where('user_id', $AdminId)->first();
		return view('online_booking.online_booking_setting',compact('online_booking'));		
	}
	
	public function saveOnlineSetting(Request $request)
	{
		$CurrentUser = auth::user();
		$is_admin = $CurrentUser->is_admin;
		
		if($is_admin == 1){
			$CurrentStaff = Staff::select('user_id')->where('staff_user_id',$CurrentUser->id)->first();
			$AdminId = $CurrentStaff->user_id;
			$UserId  = Auth::id();
		} else {
			$AdminId = Auth::id();
			$UserId  = Auth::id();
		}
		
		if($request->id == "")
		{
			$status = Online_setting::create([
				'user_id'                     => $AdminId,
				'appointment_cancel_time'     => $request->appointment_cancel_time,
				'before_start_time'           => $request->before_start_time,
				'future_time'                 => $request->future_time,
				'time_slot_interval'          => $request->time_slot_interval,
				'is_allowed_staff_selection'  => ($request->is_allowed_staff_selection) ? $request->is_allowed_staff_selection : 0,
				'important_info'              => ($request->important_info) ? $request->important_info : NULL,
				'is_specific_email'           => ($request->is_specific_email) ? $request->is_specific_email : 0,
				'email'                       => ($request->email) ? $request->email : "",
				'is_send_booked_staff'        => ($request->is_send_booked_staff) ? $request->is_send_booked_staff : 0,
				'is_show_feature'             => ($request->is_show_feature) ? $request->is_show_feature : 0,
				'is_rating'                   => ($request->is_rating) ? $request->is_rating : 0
			]);
			if($status){
				Session::flash('success','Data saved successfully.');
				return redirect(route('online_settings'));
			}
			else
			{
				Session::flash('error','Sorry! Something went wrong');
				return redirect(route('online_settings'));		
			}
		} else {
			$editSetting = Online_setting::find($request->id);
			$editSetting->user_id                    = $AdminId;
			$editSetting->appointment_cancel_time    = $request->appointment_cancel_time;
			$editSetting->before_start_time          = $request->before_start_time;
			$editSetting->future_time                = $request->future_time;
			$editSetting->time_slot_interval         = $request->time_slot_interval;
			$editSetting->is_allowed_staff_selection = ($request->is_allowed_staff_selection) ? $request->is_allowed_staff_selection : 0;
			$editSetting->important_info             = ($request->important_info) ? $request->important_info : NULL;
			$editSetting->is_specific_email          = ($request->is_specific_email) ? $request->is_specific_email : 0;
			$editSetting->email                      = ($request->email) ? $request->email : "";
			$editSetting->is_send_booked_staff       = ($request->is_send_booked_staff) ? $request->is_send_booked_staff : 0;
			$editSetting->is_show_feature            = ($request->is_show_feature) ? $request->is_show_feature : 0;
			$editSetting->is_rating                  = ($request->is_rating) ? $request->is_rating : 0;
			
			if($editSetting->save()){
				Session::flash('success','Data saved successfully.');
				return redirect(route('online_settings'));
			}
			else
			{
				Session::flash('error','Sorry! Something went wrong');
				return redirect(route('online_settings'));		
			}
		}
	}

	public function makeLocationOffline(Request $request)
	{
		$location_id = isset($request->location_id) ? $request->location_id : '';

		Location::where('id', $location_id)->update(['is_online' => 0]);

		$data['status'] = true;
        $data['message'] = 'Profile unlisted successfully.';
        return JsonReturn::success($data);
	}

	public function confirmOnlineLocation($locationId)
	{
		return view('online_booking.confirm_online_location',compact('locationId'));
	}

	public function confirmOnlineLocationPost(Request $request)
	{
		$location_id = isset($request->location_id) ? $request->location_id : '';

		Location::where('id', $location_id)->update(['is_online' => 1]);

		// Session::flash('success','Profile listed successfully.');
        return redirect(route('profileListingSuccessful', ['locationId' => $location_id]));
	}

	public function profileListingSuccessful($locationId)
	{
		return view('online_booking.profile_listing_successful',compact('locationId'));
	}

	public function clientReview(Request $request)
	{
		$locationId = !empty($request->locationId) ? $request->locationId : 0;
		$status = !empty($request->status) ? $request->status : 0;
		$rating = !empty($request->rating) ? $request->rating : 0;

		$CurrentUser = auth::user();
		$is_admin = $CurrentUser->is_admin;
		
		if($is_admin == 1){
			$CurrentStaff = Staff::select('user_id')->where('staff_user_id',$CurrentUser->id)->first();
			$AdminId = $CurrentStaff->user_id;
			$UserId  = Auth::id();
		} else {
			$AdminId = Auth::id();
			$UserId  = Auth::id();
		}
		
		$locationWhere = ['user_id' => $AdminId,'is_deleted'=>0];

		if(!empty($locationId)) {
			$locationWhere['id'] = $locationId;
		}
		$locationIdArray = Location::where($locationWhere)->pluck('id');

		// Review Configuration
		$fuserLocationReviewWhere = [];

		if(!empty($rating)) {
			$fuserLocationReviewWhere['rating'] = $rating;
		}
		if(!empty($status)) {
			$fuserLocationReviewWhere['status'] = $status;
		}
		$ratingArray = FuserLocationReview::whereIn('location_id', $locationIdArray)->where($fuserLocationReviewWhere)->pluck('rating')->toArray();

		$averageRating = 0;
		if(is_array($ratingArray)) {
		    $totalRatings = count($ratingArray);

		    $ratingArray = array_filter($ratingArray);
		    if($totalRatings) {
		        $averageRating = array_sum($ratingArray)/$totalRatings;
		    }
		} else {
		    $totalRatings = 0;
		}
		// $averageRating = floor($averageRating);

		$locationReview = FuserLocationReview::whereIn('fuser_location_review.location_id', $locationIdArray)
							->where($fuserLocationReviewWhere)
							->leftJoin('fuser','fuser.id', 'fuser_location_review.fuser_id')
							->select('fuser_location_review.*', 'fuser.name', 'fuser.last_name')
							->get();
		// End Review Configuration

		$locations = Location::where(['user_id' => $AdminId,'is_deleted'=>0])->get();

		return view('online_booking.booking_client_review', compact('averageRating', 'locationReview', 'totalRatings', 'locations', 'locationId', 'rating', 'status'));
	}

	public function button_links()
	{
		return view('online_booking.button_links');
	}

	public function updateReviewStatus(Request $request)
	{
		$CurrentUser = auth::user();
		$is_admin = $CurrentUser->is_admin;
		
		if($is_admin == 1){
			$CurrentStaff = Staff::select('user_id')->where('staff_user_id',$CurrentUser->id)->first();
			$AdminId = $CurrentStaff->user_id;
			$UserId  = Auth::id();
		} else {
			$AdminId = Auth::id();
			$UserId  = Auth::id();
		}
		
		$locationIdArray = Location::where(['user_id' => $AdminId])->pluck('id');
		$totalUpdatedRows = FuserLocationReview::where('id', $request->id)->whereIn('location_id', $locationIdArray)->update(['status' => $request->status]);

		if($totalUpdatedRows > 0) {
			$data['status'] = true;
	        $data['message'] = 'Status has been updated successfully.';
	        return JsonReturn::success($data);
		} else {
			$data['status'] = false;
	        $data['message'] = 'Status has not been updated.';
	        return JsonReturn::success($data);
		}
	}

	public function bookingLink(){
		
		$CurrentUser = auth::user();
		$is_admin = $CurrentUser->is_admin;
		
		if($is_admin == 1){
			$CurrentStaff = Staff::select('user_id')->where('staff_user_id',$CurrentUser->id)->first();
			$AdminId = $CurrentStaff->user_id;
			$UserId  = Auth::id();
		} else {
			$AdminId = Auth::id();
			$UserId  = Auth::id();
		}
		$enuserid = Crypt::encryptString($UserId);

		if($enuserid){
			$data['status'] = true;
			$data['link'] = 'https://schedulethat.tjcg.in/BookNow/'.$enuserid;
			return JsonReturn::success($data);
		} else {
			$data['status'] = false;
	        $data['message'] = 'Link was not generate';
	        return JsonReturn::success($data);
		}
		
	}
}
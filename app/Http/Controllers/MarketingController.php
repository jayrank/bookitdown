<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Group_email_blast;
use App\Models\Payment_response;
use App\Models\ServiceCategory;
use App\Models\Group_sms_blast;
use App\Notification\TelnyxSms;
use App\Models\Smart_campaign;
use App\Models\ServicesPrice;
use Illuminate\Http\Request;
use App\Models\EmailMessage;
use App\Models\Sms_message;
use App\Models\Permission;
use App\Models\Location;
use App\Models\Default_campaign;
use App\Models\Services;
use App\Models\Sms_log;
use App\Models\Setting;
use App\Models\Clients;
use App\Models\Invoice;
use App\Models\Smslog;
use App\Models\EmailLog;
use App\Models\Role;
use App\Models\User;
use App\Models\History;
use App\Models\UsersStoredCarddetails;
use App\Models\Shortener;
use App\Models\Voucher;
use App\Models\Staff;
use App\Mail\EmailBlast;
use App\JsonReturn;
use DateInterval;
use DataTables;
use DatePeriod;
use DateTime;
use Session;
use Carbon;
use Stripe;
use File;
use Mail;
use Crypt;
use DB;

class MarketingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        date_default_timezone_set('Asia/Kolkata');
    }

    public function index()
    {
        return redirect(route('smart_campaigns'));
        return view('marketing.index');
    }

    public function smart_campaigns()
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
        
        $defaultCampaign = Default_campaign::get()->toArray();
        foreach($defaultCampaign as $key => $value){
            $SmartCampaign = Smart_campaign::select('*')->where(['user_id' => $AdminId,'default_campaign_id'=>$value['id']])->get()->toArray();
            if(!empty($SmartCampaign)){
                $SmartCampaign = $SmartCampaign[0]; 
            }
            $defaultCampaign[$key]['campaign_data'] = $SmartCampaign;
        }

        /*echo "<pre>";
        print_r($defaultCampaign);
        exit();*/
        return view('marketing.smart_campaigns',compact('SmartCampaign','defaultCampaign'));    
    }

    public function add_email_message()
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
        
        $getUserDetails = User::getUserbyID($AdminId);  
        
        $allClients = Clients::where('user_id',Auth::id())->get();
        $serviceCategory = ServiceCategory::where(['user_id'=>Auth::id(),'is_deleted'=>0])->get()->toArray();
        $locationData = Location::where('user_id',$AdminId)->get()->toArray();
        $setting = Setting::first();
        foreach ($serviceCategory as $key => $serviceCat) 
        {
            $services = Services::where(['service_category'=>$serviceCat['id'],'is_deleted'=>0])->get()->toArray();
            foreach ($services as $serviceKey => $service)
            {
                $servicesPrice = ServicesPrice::where(['service_id'=>$service['id']])->get()->toArray();
                $services[$serviceKey]['service_price'] = $servicesPrice;
            }
            $serviceCategory[$key]['service'] = $services;
        }
        
        $cardList = array();
        if($getUserDetails->stripe_customer_code != ''){
            $cardListResponse = $this->getStripeCardList($getUserDetails->stripe_customer_code);

            if(!empty($cardListResponse) && $cardListResponse['status'] == true){
                $cardList = $cardListResponse['card_list'];
            }
        }
        
        $voucherLists = Voucher::select('id','name','services_ids','value','retailprice','validfor','enable_sale_limit','numberofsales')->where('user_id', $AdminId)->where('is_online',1)->orderBy('id', 'desc')->get()->toArray();
        
        return view('marketing.add_email_message', compact('allClients','serviceCategory','locationData','setting','cardList','voucherLists'));
    }
    public function add_campaigns($id)
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
        
        $defaultCampaign = Default_campaign::where('id',Crypt::decryptString($id))->first();
        $locationData = Location::where('user_id',$AdminId)->get()->toArray();
        $serviceCategory = array();
        $serviceCategory = ServiceCategory::where(['user_id' => $AdminId, 'is_deleted' => 0])->get()->toArray();
        foreach ($serviceCategory as $key => $serviceCat) 
        {
            $services = Services::where(['service_category'=>$serviceCat['id'],'is_deleted'=>0])->get()->toArray();
            foreach ($services as $serviceKey => $service)
            {
                $servicesPrice = ServicesPrice::where(['service_id'=>$service['id']])->get()->toArray();
                $services[$serviceKey]['service_price'] = $servicesPrice;
            }
            $serviceCategory[$key]['service'] = $services;
        }
        return view('marketing.add_campaign',compact('serviceCategory','locationData','defaultCampaign')); 
    }
    
    public function edit_campaigns($id)
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
        
        $serviceCategory = array();
        $smartCampaign = array();
        if(!empty($id))
        {
            $smartCampaign = Smart_campaign::where('id',Crypt::decryptString($id))->first();
            $defaultCampaign = Default_campaign::where('id',$smartCampaign->default_campaign_id)->first();
        }
        $serviceCategory = ServiceCategory::where(['user_id' => $AdminId,'is_deleted'=>0])->get()->toArray();
        $locationData = Location::where('user_id', $AdminId)->get()->toArray();
        foreach ($serviceCategory as $key => $serviceCat) 
        {
            $services = Services::where(['service_category'=>$serviceCat['id'],'is_deleted'=>0])->get()->toArray();
            foreach ($services as $serviceKey => $service)
            {
                $servicesPrice = ServicesPrice::where(['service_id'=>$service['id']])->get()->toArray();
                $services[$serviceKey]['service_price'] = $servicesPrice;
            }
            $serviceCategory[$key]['service'] = $services;
        }
        return view('marketing.edit_campaign',compact('serviceCategory','smartCampaign','locationData','defaultCampaign')); 
    }
    
    public function uploadCampaignImage(Request $request)
    {
        if($files=$request->file('files'))
        {
            foreach($files as $file)
            {
                $name = time().'.'.$file->extension();
                $destination = base_path() . '/public/uploads/campaign_images';
                $replacedName = time();
                $file->move($destination, $name);
            }
            $data["status"] = true;
            $data["message"] = "File(s) uploaded successfull";
            $data["filename"] = $name;
        }
        else
        {
            $data["status"] = false;
            $data["message"] = "File(s) uploading failed";
            $data["filename"] = '';
        }
        return JsonReturn::success($data);
    }
    
    public function deleteCampaignImage(Request $request)
    {
        $data = [];
        $data["status"] = false;
        $data["message"] = "Something went wrong.";
        $filename = trim($request->filename);

        if($filename != '') {
            $image_path = base_path() . '/public/uploads/campaign_images/'.$filename;

            if (File::exists($image_path)) {
                unlink($image_path);

                $data["status"] = true;
                $data["message"] = "File deleted successfully.";
            } else {
                $data["status"] = false;
                $data["message"] = "File not found.";
            }
        } else {
            $data["status"] = false;
            $data["message"] = "Received empty request.";
        }
        return JsonReturn::success($data);
    }

    public function saveCampaign(Request $request)
    {
        $postData = $request->all();
        
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
        
        if($postData['edit_id'] == 0 || $postData['edit_id'] == "")
        {
            $addStatus = Smart_campaign::create([
                'user_id'                   => $AdminId,    
                'email_subject'             => $postData['email_subject'],
                'default_campaign_id'       => $postData['default_campaign_id'],
                'headline_text'             => $postData['headline_text'],
                'body_text'                 => nl2br($postData['body_text']),
                'discount_value'            => $postData['discount_value'],
                'discount_type'             => $postData['discount_type'],
                'day_before_birthday'       => $postData['daysBeforeBirthday'],
                'appoinment_limit'          => ($postData['appoinment_limit'] == 'unlimited') ? 0 : $postData['appoinment_limit'],
                'valid_for'                 => $postData['valid_for'],
                'services'                  => json_encode($postData['service']),
                'image_path'                => $postData['image_path'],
                'min_sales_count'           => $postData['min_sales_count'],
                'max_month_considered'      => $postData['max_month_considered'],
                'min_month_since_last_sale' => $postData['min_month_since_last_sale'],
                'min_amount_type'           => $postData['minAmountType'],
                'at_least_spent_amount'     => $postData['at_least_spent_amount'],
                'client_type'               => $postData['client_type'],
                'enable_sales'              => 1,
                'created_at'                => date("Y-m-d H:i:s"),
                'updated_at'                => date("Y-m-d H:i:s"),
            ]);
            
            if($addStatus)
            {
                History::create([
                    'user_id' => $AdminId,
                    'module_type' => 1,
                    'module_type_text' => 'Smart Campaign',
                    'message' => 'Add Campaign',
                    'action' => 'Add',
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
                $data["status"] = true;
                $data["message"] = "Smart campaign has been created succesfully.";
                $data['redirect'] = route('smart_campaigns');
                return JsonReturn::success($data);
            } 
            else
            {
                $data["status"] = false;
                $data["message"] = "Smart campaign operation is failed.";
                $data['redirect'] = route('smart_campaigns');
                return JsonReturn::success($data);
            }
        }
        else
        {
            $smartCampaign = Smart_campaign::find($postData['edit_id']);
            $smartCampaign->email_subject            = $postData['email_subject'];
            $smartCampaign->headline_text            = $postData['headline_text'];
            $smartCampaign->body_text                = nl2br($postData['body_text']);
            $smartCampaign->discount_value           = $postData['discount_value'];
            $smartCampaign->discount_type            = $postData['discount_type'];
            $smartCampaign->day_before_birthday      = $postData['daysBeforeBirthday'];
            $smartCampaign->min_sales_count          = $postData['min_sales_count'];
            $smartCampaign->max_month_considered     = $postData['max_month_considered'];
            $smartCampaign->min_month_since_last_sale= $postData['min_month_since_last_sale'];
            $smartCampaign->min_amount_type          = $postData['minAmountType'];
            $smartCampaign->at_least_spent_amount    = ($postData['minAmountType'] == 2) ? $postData['at_least_spent_amount'] : 0;
            $smartCampaign->appoinment_limit        = ($postData['appoinment_limit'] == 'unlimited') ? 0 : $postData['appoinment_limit'];
            $smartCampaign->valid_for               = $postData['valid_for'];
            $smartCampaign->services                = json_encode($postData['service']);
            $smartCampaign->image_path              = $postData['image_path'];
            $smartCampaign->updated_at              = date("Y-m-d H:i:s");
            
            if($smartCampaign->save())
            {
                History::create([
                    'user_id' => $AdminId,
                    'module_type' => 1,
                    'module_type_text' => 'Smart Campaign',
                    'message' => 'Update Campaign',
                    'action' => 'Update',
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
                $data["status"] = true;
                $data["message"] = "Smart campaign has been updated succesfully.";
                $data['redirect'] = route('smart_campaigns');
                return JsonReturn::success($data);
            }
            else
            {
                $data["status"] = false;
                $data["message"] = "Smart campaign operation is failed.";
                $data['redirect'] = route('smart_campaigns');
                return JsonReturn::success($data);
            }
        }
    }
    
    function sendCampaignEmail(Request $request)
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
        
        $postData = $request->all();
        $smartCampaign = Smart_campaign::where('id',$postData['id'])->first();
        $locationData = Location::where('user_id', $AdminId)->get()->toArray();
       
        if(!empty($smartCampaign))
        {
            $FROM_EMAIL     = env("MAIL_FROM_ADDRESS", "info@ikotel.ca");
            $FROM_NAME      = $smartCampaign->campaign_type;
            $TO_EMAIL       = $postData['email'];
            $CC_EMAIL       = '';//info@ikotel.ca
            $SUBJECT        = $smartCampaign->email_subject;
            $MESSAGE        = $smartCampaign->email_subject;
            $LOCATION       = $locationData;
            $CampaignData   = $smartCampaign;
            // echo "Entered smartCampaign<br> To CampaignData :- "; print_r($CampaignData);die;
            $TYPE           = 'Campaign';
            $UniqueId       = $this->unique_code(30).time();

            $voucherLists = array();
            if($smartCampaign->voucher_id > 0){
                $voucherLists = Voucher::select('id','name','services_ids','value','retailprice','validfor','enable_sale_limit','numberofsales')->where('user_id', $AdminId)->where('id',$smartCampaign->voucher_id)->where('is_online',1)->orderBy('id', 'desc')->get()->first()->toArray();
            }
            // $SendMail = Mail::to($TO_EMAIL)->cc([$CC_EMAIL])->send(new EmailBlast($FROM_EMAIL,$FROM_NAME,$SUBJECT,$MESSAGE,$LOCATION,$MessageData,$TYPE,$UniqueId,$voucherLists));
            $SendMail = Mail::to($TO_EMAIL)->send(new EmailBlast($FROM_EMAIL,$FROM_NAME,$SUBJECT,$MESSAGE,$LOCATION,$CampaignData,$TYPE,$UniqueId,$voucherLists));//->cc([$CC_EMAIL])

            if (Mail::failures()) {
                foreach(Mail::failures() as $email_address) {
                    echo " - $email_address <br />";
                }
                $data['status'] = true;
                $data['message'] = "Error Occured!";

                return JsonReturn::success($data);
            }
            EmailLog::create([
                'user_id'   => $AdminId,
                'unique_id' => $UniqueId,
                'campaign_id'=>$postData['id'],
                'from_email' => $FROM_EMAIL,
                'to_email'  => $TO_EMAIL,
                'module_type' => 1,
                'module_type_text' => 'Campaign Email',
                'created_at' => date('Y-m-d H:i:s')
            ]);
            /*$updateCampaign = Smart_campaign::find('id',$postData['id']);
            $updateCampaign->email_send_counter = $smartCampaign->email_send_counter + 1;
            $updateCampaign->save();*/
            $data["status"] = true;
            $data["message"] = "Email has been sent successfully.";
            return JsonReturn::success($data);
        }
    }

    public function marketing_blast_messages()
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
        
        $allSmsMessges = Sms_message::where('user_id', $AdminId)->get();
        $allEmailMessges = EmailMessage::where('user_id', $AdminId)->get();
        return view('marketing.marketing_blast_messages', compact('allSmsMessges','allEmailMessges'));
    }
    function changeSalesStatus(Request $request)
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
        
        $postData = $request->all();
        $smartCampaign = Smart_campaign::find($postData['id']);
        $smartCampaign->enable_sales = $postData['val'];
        
        if($smartCampaign->save())
        {
            History::create([
                'user_id' => $AdminId,
                'module_type' => 1,
                'module_type_text' => 'Smart Campaign',
                'message' => 'Active Campaign',
                'action' => 'Active',
                'created_at' => date('Y-m-d H:i:s'),
            ]);
            $data["status"] = true;
            $data["message"] = "Smart campaign has been updated succesfully.";
            return JsonReturn::success($data);
        }
        else
        {
            $data["status"] = false;
            $data["message"] = "Smart campaign operation is failed.";
            return JsonReturn::success($data);
        }
    }
    
    public function loadMarketingPreview(Request $request)
    {
        $smartCampaign = Smart_campaign::where('id',$request->id)->first();
        $html = "";
        if(!empty($smartCampaign))
        {
            $image_path = $smartCampaign->image_path;
            $headline_text = $smartCampaign->headline_text;
            $body_text = $smartCampaign->body_text;
            $discount_type = $smartCampaign->discount_type;
            $discount_value = $smartCampaign->discount_value;
            $valid_for = $smartCampaign->valid_for;
            // url($image_path)
            // dd($body_text);
            $html .= '<div class="card-body">';
                $html .= '<div class="card-img" style="margin:20px auto;height: 100px;width:100px">';
                    $html .= '<img alt="img" width="100%" height="auto" src="'.url($image_path).'" />';
                $html .= '</div>';
                $html .= '<h3 class="font-weight-bolder">'.$headline_text.'</h3>';
                $html .= '<h6 class="text-dark-50 my-4"> '.$body_text.' </h6>';
                $html .= '<hr>';
                $html .= '<div class="preview-card-discount">';
                    $html .= '<div class="detail">';
                        if($discount_type == 1)
                        {
                            $discountHtml = $discount_value."% Off";
                        }
                        else
                        {
                            $discountHtml = "$".$discount_value." Off";
                        }
                        $html .= '<h1 class="my-4 font-weight-bolder text-uppercase">'.$discountHtml.'</h1>';
                            $html .= '<p>your next appointment</p>';
                        $html .= '<div class="text-uppercase btn btn-light">';
                            $html .= 'Book Now';
                    $html .= '</div>';
                $html .= '</div>';
            $html .= '</div>';
            $html .= '<p class="text-center my-4">Applies to all services, valid for '.$valid_for.' days</p>';
            $html .= '</div>';
            $data["status"] = true;
            $data["html"] = $html;
            return JsonReturn::success($data);
        }
        else
        {
            $data["status"] = false;
            $data["message"] = "Smart campaign operation is failed.";
            return JsonReturn::success($data);
        }
    }
    public function saveEmailBlast(Request $request)
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
        
        $lastId = 0;
        $postData = $request->all();
        
        $setting = Setting::first();
        $fromNumber = $setting->telnyx_number;
        if($postData['edit_id'] == 0 || $postData['edit_id'] == "")
        {
            $status = 0;
            if($postData['message-type'] == 1)
            {
                $addEmailStatus = EmailMessage::create([
                    'user_id'              => $AdminId, 
                    'message_name'         => $postData['message_name'],
                    'email_subject'        => $postData['email_subject'],
                    'reply_email'          => $postData['email_reply'],
                    'title'                => $postData['title'],
                    'message'              => nl2br($postData['desc']),
                    'is_button'            => $postData['isbutton'],
                    'button_text'          => ($postData['isbutton'] == 2) ? $postData['btn-text'] : "",
                    'button_link'          => ($postData['isbutton'] == 2) ? $postData['btn_url'] : "",
                    'social_media_enable'  => isset($postData['isSocial'])?1:0,
                    'facebook_link'        => isset($postData['isSocial']) ? $postData['facebook'] : "",
                    'instagram_link'       => isset($postData['isSocial']) ? $postData['instagram'] : "",
                    'website'              => isset($postData['isSocial']) ? $postData['yoursite'] : "",
                    'message_price'        => $postData['message_price'],
                    'total_payable_price'  => $postData['total_payable_price'],
                    'is_sended'            => 0,
                    'message_type_int'     => 1,
                    'message_type_text'    => 'Quick update',
                    'is_image'             => isset($postData['enable_image']) ? 1: 0,
                    'image_path'           => isset($postData['enable_image']) ? $postData['image_path'] : "",
                    'client_type_int'      => $postData['audience'],
                    'client_type'          => ($postData['audience'] == 2) ? $postData['isclient'] : 'All Clients',
                    'created_by'           => $UserId,
                    'background_color'     => $postData['background_color'],
                    'foreground_color'     => $postData['foreground_color'],
                    'font_color'           => $postData['font_color'],
                    'line_color'           => $postData['line_color'],
                    'button_color'         => $postData['button_color'],
                    'botton_text_color'    => $postData['botton_text_color'],
                    'created_at'           => date("Y-m-d H:i:s"),
                    'updated_at'           => date("Y-m-d H:i:s"),
                ]);
                $lastId = $addEmailStatus->id;
                if($lastId)
                {
                    foreach ($postData['clients'] as $key => $value) 
                    {
                        $addGroupEmailBlast = Group_email_blast::create([
                            'user_id'       => $AdminId,  
                            'blast_email_id'=> $lastId,
                            'client_id'     => $value,
                            'created_at'    => date("Y-m-d H:i:s"),
                        ]);
                    }
                    $status = 1;
                }
            }
            else if($postData['message-type'] == 2)
            {
                $addEmailStatus = EmailMessage::create([
                    'user_id'              => $AdminId,     
                    'message_name'         => $postData['message_name'],
                    'email_subject'        => $postData['email_subject'],
                    'reply_email'          => $postData['email_reply'],
                    'title'                => $postData['title'],
                    'message'              => nl2br($postData['desc']),
                    'is_button'            => $postData['isbutton'],
                    'button_text'          => ($postData['isbutton'] == 2) ? $postData['btn-text'] : "",
                    'button_link'          => ($postData['isbutton'] == 2) ? $postData['btn_url'] : "",
                    'social_media_enable'  => isset($postData['isSocial'])?1:0,
                    'facebook_link'        => isset($postData['isSocial']) ? $postData['facebook'] : "",
                    'instagram_link'       => isset($postData['isSocial']) ? $postData['instagram'] : "",
                    'website'              => isset($postData['isSocial']) ? $postData['yoursite'] : "",
                    'discount_value'       => $postData['discount_value'],
                    'discount_type'        => $postData['discount_type'],
                    'message_price'        => $postData['message_price'],
                    'total_payable_price'  => $postData['total_payable_price'],
                    'appointment_limit'     => $postData['appoinmentsLimits'],
                    'valid_for'             => $postData['validFor'],
                    'is_sended'            => 0,
                    'message_type_int'     => 2,
                    'message_type_text'    => 'Special offer',
                    'services'             => json_encode($postData['service']),
                    'is_image'             => isset($postData['enable_image']) ? 1: 0,
                    'image_path'           => isset($postData['enable_image']) ? $postData['image_path'] : "",
                    'client_type_int'      => $postData['audience'],
                    'client_type'          => ($postData['audience'] == 2) ? $postData['isclient'] : 'All Clients',
                    'created_by'           => $UserId,
                    'background_color'     => $postData['background_color'],
                    'foreground_color'     => $postData['foreground_color'],
                    'font_color'           => $postData['font_color'],
                    'line_color'           => $postData['line_color'],
                    'button_color'         => $postData['button_color'],
                    'botton_text_color'    => $postData['botton_text_color'],
                    'created_at'           => date("Y-m-d H:i:s"),
                    'updated_at'           => date("Y-m-d H:i:s"),
                ]);
                $lastId = $addEmailStatus->id;
                if($lastId)
                {
                    foreach ($postData['clients'] as $key => $value) 
                    {
                        $addGroupEmailBlast = Group_email_blast::create([
                            'user_id'       => $AdminId,  
                            'blast_email_id'=> $lastId,
                            'client_id'     => $value,
                            'created_at'    => date("Y-m-d H:i:s"),
                        ]);
                    }
                    $status = 1;
                }
            } 
            else if($postData['message-type'] == 3)
            {
                $addEmailStatus = EmailMessage::create([
                    'user_id'              => $AdminId,  
                    'voucher_id'           => $postData['voucher_id'],      
                    'message_name'         => $postData['message_name'],
                    'email_subject'        => $postData['email_subject'],
                    'reply_email'          => $postData['email_reply'],
                    'title'                => $postData['title'],
                    'message'              => nl2br($postData['desc']),
                    'is_button'            => $postData['isbutton'],
                    'button_text'          => ($postData['isbutton'] == 2) ? $postData['btn-text'] : "",
                    'button_link'          => ($postData['isbutton'] == 2) ? $postData['btn_url'] : "",
                    'social_media_enable'  => isset($postData['isSocial'])?1:0,
                    'facebook_link'        => isset($postData['isSocial']) ? $postData['facebook'] : "",
                    'instagram_link'       => isset($postData['isSocial']) ? $postData['instagram'] : "",
                    'website'              => isset($postData['isSocial']) ? $postData['yoursite'] : "",
                    'message_price'        => $postData['message_price'],
                    'total_payable_price'  => $postData['total_payable_price'],
                    'is_sended'            => 0,
                    'message_type_int'     => 3,
                    'message_type_text'    => 'Buy Voucher',
                    'is_image'             => isset($postData['enable_image']) ? 1: 0,
                    'image_path'           => isset($postData['enable_image']) ? $postData['image_path'] : "",
                    'client_type_int'      => $postData['audience'],
                    'client_type'          => ($postData['audience'] == 2) ? $postData['isclient'] : 'All Clients',
                    'created_by'           => $UserId,
                    'background_color'     => $postData['background_color'],
                    'foreground_color'     => $postData['foreground_color'],
                    'font_color'           => $postData['font_color'],
                    'line_color'           => $postData['line_color'],
                    'button_color'         => $postData['button_color'],
                    'botton_text_color'    => $postData['botton_text_color'],
                    'created_at'           => date("Y-m-d H:i:s"),
                    'updated_at'           => date("Y-m-d H:i:s"),
                ]);
                $lastId = $addEmailStatus->id;
                if($lastId)
                {
                    foreach ($postData['clients'] as $key => $value) 
                    {
                        $addGroupEmailBlast = Group_email_blast::create([
                            'user_id'       => $AdminId,  
                            'blast_email_id'=> $lastId,
                            'client_id'     => $value,
                            'created_at'    => date("Y-m-d H:i:s"),
                        ]);
                    }
                    $status = 1;
                }
            }
        }
        else
        {
            if($postData['message-type'] == 1)
            {
                $addEmailStatus = EmailMessage::find($postData['edit_id']);
                $addEmailStatus->message_name       = $postData['message_name'];
                $addEmailStatus->email_subject      = $postData['email_subject'];
                $addEmailStatus->reply_email        = $postData['email_reply'];
                $addEmailStatus->title              = $postData['title'];
                $addEmailStatus->message            = $postData['desc'];
                $addEmailStatus->is_button          = $postData['isbutton'];
                $addEmailStatus->button_text        = ($postData['isbutton'] == 2) ? $postData['btn-text'] : "";
                $addEmailStatus->button_link        = ($postData['isbutton'] == 2) ? $postData['btn_url'] : "";
                $addEmailStatus->social_media_enable= isset($postData['isSocial'])?1:0;
                $addEmailStatus->facebook_link      = isset($postData['isSocial']) ? $postData['facebook'] : "";
                $addEmailStatus->instagram_link     = isset($postData['isSocial']) ? $postData['instagram'] : "";
                $addEmailStatus->website            = isset($postData['isSocial']) ? $postData['yoursite'] : "";
                $addEmailStatus->message_price      = $postData['message_price'];
                $addEmailStatus->total_payable_price= $postData['total_payable_price'];
                $addEmailStatus->is_sended          = 0;
                $addEmailStatus->message_type_int   = 1;
                $addEmailStatus->message_type_text  = 'Special offer';
                $addEmailStatus->is_image           = isset($postData['enable_image']) ? 1: 0;
                $addEmailStatus->image_path         = isset($postData['enable_image']) ? $postData['image_path'] : "";
                $addEmailStatus->created_by         = $UserId;
                $addEmailStatus->background_color   = $postData['background_color'];
                $addEmailStatus->foreground_color   = $postData['foreground_color'];
                $addEmailStatus->font_color         = $postData['font_color'];
                $addEmailStatus->line_color         = $postData['line_color'];
                $addEmailStatus->button_color       = $postData['button_color'];
                $addEmailStatus->botton_text_color  = $postData['botton_text_color'];
                $addEmailStatus->updated_at         = date("Y-m-d H:i:s");
                Group_email_blast::where('blast_email_id',$postData['edit_id'])->delete();
                $lastId = $postData['edit_id'];
                if($addEmailStatus->save())
                {
                    foreach ($postData['clients'] as $key => $value) 
                    {
                        $addGroupEmailBlast = Group_email_blast::create([
                            'user_id'       => $AdminId,  
                            'blast_email_id'=> $lastId,
                            'client_id'     => $value,
                            'created_at'    => date("Y-m-d H:i:s"),
                        ]);
                    }
                    $status = 1;
                }
            }
            else if($postData['message-type'] == 2)
            {
                $addEmailStatus = EmailMessage::find($postData['edit_id']); 
                $addEmailStatus->message_name       =$postData['message_name'];
                $addEmailStatus->email_subject      =$postData['email_subject'];
                $addEmailStatus->reply_email        =$postData['email_reply'];
                $addEmailStatus->title              =$postData['title'];
                $addEmailStatus->message            =$postData['desc'];
                $addEmailStatus->is_button          =$postData['isbutton'];
                $addEmailStatus->button_text        =($postData['isbutton'] == 2) ? $postData['btn-text'] : "";
                $addEmailStatus->button_link        =($postData['isbutton'] == 2) ? $postData['btn_url'] : "";
                $addEmailStatus->social_media_enable=isset($postData['isSocial'])?1:0;
                $addEmailStatus->facebook_link      =isset($postData['isSocial']) ? $postData['facebook'] : "";
                $addEmailStatus->instagram_link     =isset($postData['isSocial']) ? $postData['instagram'] : "";
                $addEmailStatus->website            =isset($postData['isSocial']) ? $postData['yoursite'] : "";
                $addEmailStatus->discount_value     =$postData['discount_value'];
                $addEmailStatus->discount_type      =$postData['discount_type'];
                $addEmailStatus->message_price      =$postData['message_price'];
                $addEmailStatus->total_payable_price=$postData['total_payable_price'];
                $addEmailStatus->appointment_limit  =$postData['appoinmentsLimits'];
                $addEmailStatus->valid_for          =$postData['validFor'];
                $addEmailStatus->is_sended          =0;
                $addEmailStatus->message_type_int   =2;
                $addEmailStatus->message_type_text  ='Special offer';
                $addEmailStatus->services           =json_encode($postData['service']);
                $addEmailStatus->is_image           =isset($postData['enable_image']) ? 1: 0;
                $addEmailStatus->image_path         =isset($postData['enable_image']) ? $postData['image_path'] : "";
                $addEmailStatus->created_by         =$UserId;
                $addEmailStatus->background_color   = $postData['background_color'];
                $addEmailStatus->foreground_color   = $postData['foreground_color'];
                $addEmailStatus->font_color         = $postData['font_color'];
                $addEmailStatus->line_color         = $postData['line_color'];
                $addEmailStatus->button_color       = $postData['button_color'];
                $addEmailStatus->botton_text_color  = $postData['botton_text_color'];
                $addEmailStatus->updated_at         =date("Y-m-d H:i:s");
                Group_email_blast::where('blast_email_id',$postData['edit_id'])->delete();
                $lastId = $postData['edit_id'];
                if($addEmailStatus->save())
                {
                    foreach ($postData['clients'] as $key => $value) 
                    {
                        $addGroupEmailBlast = Group_email_blast::create([
                            'user_id'       => $AdminId,  
                            'blast_email_id'=> $lastId,
                            'client_id'     => $value,
                            'created_at'    => date("Y-m-d H:i:s"),
                        ]);
                    }
                    $status = 1;
                }
            } 
            else if($postData['message-type'] == 3)
            {
                $addEmailStatus = EmailMessage::find($postData['edit_id']);
                $addEmailStatus->voucher_id         =$postData['voucher_id'];
                $addEmailStatus->message_name       =$postData['message_name'];
                $addEmailStatus->email_subject      =$postData['email_subject'];
                $addEmailStatus->reply_email        =$postData['email_reply'];
                $addEmailStatus->title              =$postData['title'];
                $addEmailStatus->message            =$postData['desc'];
                $addEmailStatus->is_button          =$postData['isbutton'];
                $addEmailStatus->button_text        =($postData['isbutton'] == 2) ? $postData['btn-text'] : "";
                $addEmailStatus->button_link        =($postData['isbutton'] == 2) ? $postData['btn_url'] : "";
                $addEmailStatus->social_media_enable=isset($postData['isSocial'])?1:0;
                $addEmailStatus->facebook_link      =isset($postData['isSocial']) ? $postData['facebook'] : "";
                $addEmailStatus->instagram_link     =isset($postData['isSocial']) ? $postData['instagram'] : "";
                $addEmailStatus->website            =isset($postData['isSocial']) ? $postData['yoursite'] : "";
                $addEmailStatus->message_price      =$postData['message_price'];
                $addEmailStatus->total_payable_price=$postData['total_payable_price'];
                $addEmailStatus->is_sended          =0;
                $addEmailStatus->message_type_int   =3;
                $addEmailStatus->message_type_text  ='Buy Voucher';
                $addEmailStatus->is_image           =isset($postData['enable_image']) ? 1: 0;
                $addEmailStatus->image_path         =isset($postData['enable_image']) ? $postData['image_path'] : "";
                $addEmailStatus->created_by         =$UserId;
                $addEmailStatus->background_color   = $postData['background_color'];
                $addEmailStatus->foreground_color   = $postData['foreground_color'];
                $addEmailStatus->font_color         = $postData['font_color'];
                $addEmailStatus->line_color         = $postData['line_color'];
                $addEmailStatus->button_color       = $postData['button_color'];
                $addEmailStatus->botton_text_color  = $postData['botton_text_color'];
                $addEmailStatus->updated_at         =date("Y-m-d H:i:s");
                Group_email_blast::where('blast_email_id',$postData['edit_id'])->delete();
                $lastId = $postData['edit_id'];
                if($addEmailStatus->save())
                {
                    foreach ($postData['clients'] as $key => $value) 
                    {
                        $addGroupEmailBlast = Group_email_blast::create([
                            'user_id'       => $AdminId,  
                            'blast_email_id'=> $lastId,
                            'client_id'     => $value,
                            'created_at'    => date("Y-m-d H:i:s"),
                        ]);
                    }
                    $status = 1;
                }
            }
        }
        
        if($status)
        {
            $getUserDetails = User::getUserbyID($AdminId);  
            
            $chargeData = array(
                'amount'      => round($postData['amount'] * 100),
                'description' => 'Email Blast Message',
                'metadata'    => array('email' => $getUserDetails->email,'user_id' => $getUserDetails->id,'campaign_id' => $lastId),
                'currency'    => $postData['currency'],
                'customer'    => $getUserDetails->stripe_customer_code,
                'card'        => $postData['paymentCardId']
            );
            
            $chargeResponse = $this->chargeStripeCustomer($chargeData);
            
            if(!empty($chargeResponse) && $chargeResponse['status'] == true)
            {
                if($chargeResponse['charge_id'] != '' && $chargeResponse['transaction_id'] != '')
                {
                    $updateBlastMessage = EmailMessage::find($lastId);
                    $updateBlastMessage->payment_status = 1;
                    
                    $payment_response = Payment_response::create([
                        'user_id'           => $AdminId,
                        'type'              => 'Email',
                        'email_sms_id'      => $lastId,
                        'charge_id'         => $chargeResponse['charge_id'],
                        'currency'          => 'CAD',
                        'amount'            => $postData['amount'],
                        'transaction_id'    => $chargeResponse['transaction_id'],
                        'card_brand'        => $chargeResponse['full_response']->source->brand,
                        'card_last4'        => $chargeResponse['full_response']->source->last4,
                        'card_id'           => $chargeResponse['full_response']->payment_method,
                        'module_type'       => '1',
                        'module_text'       => 'Email Blast Payment',
                        'status'            => $chargeResponse['full_response']->paid,
                        'status_text'       => ($chargeResponse['full_response']->paid == 1) ? 'PAID' : 'UNPAID',
                        'payment_response'  => json_encode($chargeResponse['full_response']),
                        'created_at'        => date("Y-m-d H:i:s")
                    ]);
                    
                    $blastEmailClients = Group_email_blast::where('blast_email_id',$lastId)->get();
                    foreach ($blastEmailClients as $blastClient) 
                    {
                        $clientData = Clients::where('id',$blastClient->client_id)->first();
                        if(!empty($clientData->email))
                        {
                            $updateBlastMessage->is_sended = 1;
                            $locationData = Location::where('user_id', $AdminId)->get()->toArray();
                            $emailMessageData = EmailMessage::where('id',$lastId)->first();
                            
                            if($postData['message-type'] == 1){
                                $fromname = 'Quick Update';
                                $viewtype = 'quick_update';
                            } else if($postData['message-type'] == 2){
                                $fromname = 'Special Offer';
                                $viewtype = 'special_offer';
                            } else if($postData['message-type'] == 3){
                                $fromname = 'Buy Voucher';
                                $viewtype = 'buy_voucher';
                            }
                            
                            $FROM_EMAIL     = env("MAIL_FROM_ADDRESS", "info@ikotel.ca");
                            $FROM_NAME      = $fromname;
                            $TO_EMAIL       = ($clientData->email) ? $clientData->email : '';
                            $CC_EMAIL       = '';//info@ikotel.ca
                            $SUBJECT        = $postData['email_subject'];
                            $MESSAGE        = 'Hi  This is test email!';
                            $LOCATION       = $locationData;
                            $MessageData    = $emailMessageData;
                            $TYPE           = $viewtype;
                            $UniqueId       = $this->unique_code(30).time();
                            
                            $voucherLists = array();
                            if($emailMessageData->voucher_id > 0){
                                $voucherLists = Voucher::select('id','name','services_ids','value','retailprice','validfor','enable_sale_limit','numberofsales')->where('user_id', $AdminId)->where('id',$emailMessageData->voucher_id)->where('is_online',1)->orderBy('id', 'desc')->get()->first()->toArray();
                            }
                            
                            $SendMail = Mail::to($TO_EMAIL)->send(new EmailBlast($FROM_EMAIL,$FROM_NAME,$SUBJECT,$MESSAGE,$LOCATION,$MessageData,$TYPE,$UniqueId,$voucherLists));//->cc([$CC_EMAIL])
                            
                            $GroupEmailBlast = Group_email_blast::find($blastClient->id);
                            $GroupEmailBlast->is_sent              = 1;
                            $GroupEmailBlast->updated_at           = date("Y-m-d H:i:s");
                            $GroupEmailBlast->save();
                            
                            EmailLog::create([
                                'user_id'   => $AdminId,
                                'unique_id' => $UniqueId,
                                'campaign_id'=> 0,
                                'group_email_blast_id' => $blastClient->id,
                                'from_email' => $FROM_EMAIL,
                                'to_email'  => $TO_EMAIL,
                                'module_type' => 2,
                                'module_type_text' => 'Blast Email',
                                'created_at' => date('Y-m-d H:i:s')
                            ]);
                        }
                    }
                    $updateBlastMessage->save();
                    $data["status"]   = true;
                    $data["message"]  = 'Email has been send successfully.';
                    $data["redirect"] = route('marketing_blast_messages');
                    return JsonReturn::success($data);
                }
                else
                {
                    $data["status"]   = false;
                    $data["message"]  = $chargeResponse['message'];
                    $data["redirect"] = route('marketing_blast_messages');
                    return JsonReturn::success($data);
                }
            }
            else 
            {
                $data["status"]   = false;
                $data["message"]  = $chargeResponse['message'];
                $data["redirect"] = route('marketing_blast_messages');
                return JsonReturn::success($data);
            }
        }
        else
        {
            $data["status"]   = false;
            $data["message"]  = "Something went wrong.";
            $data["redirect"] = route('marketing_blast_messages');
            return JsonReturn::success($data);
        }
    }

    public function sendTestEmailBlast(Request $request)
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
        
        $postData = $request->all();
        // $smartCampaign = Smart_campaign::where('id',$postData['id'])->first();
        // $locationData = Location::where('user_id', $AdminId)->get()->toArray();
        
        /*if(!empty($smartCampaign))
        {*/
            $locationData = Location::where('user_id', $AdminId)->get()->toArray();
            $emailMessageData = json_decode( json_encode([
                    'user_id'              => $AdminId,     
                    'voucher_id'           => $postData['voucher_id'],     
                    'message_name'         => $postData['message_name'],
                    'email_subject'        => $postData['email_subject'],
                    'reply_email'          => $postData['email_reply'],
                    'title'                => $postData['title'],
                    'message'              => nl2br($postData['desc']),
                    'is_button'            => $postData['isbutton'],
                    'button_text'          => ($postData['isbutton'] == 2) ? $postData['btn-text'] : "",
                    'button_link'          => ($postData['isbutton'] == 2) ? $postData['btn_url'] : "",
                    'social_media_enable'  => isset($postData['isSocial'])?1:0,
                    'facebook_link'        => isset($postData['isSocial']) ? $postData['facebook'] : "",
                    'instagram_link'       => isset($postData['isSocial']) ? $postData['instagram'] : "",
                    'website'              => isset($postData['isSocial']) ? $postData['yoursite'] : "",
                    'discount_value'       => $postData['discount_value'],
                    'discount_type'        => $postData['discount_type'],
                    'message_price'        => $postData['message_price'],
                    'total_payable_price'  => $postData['total_payable_price'],
                    'appointment_limit'     => $postData['appoinmentsLimits'],
                    'valid_for'             => $postData['validFor'],
                    'is_sended'            => 0,
                    'message_type_int'     => 2,
                    'message_type_text'    => 'Special offer',
                    'services'             => !empty($postData['service']) ? json_encode($postData['service']) : NULL,
                    'is_image'             => isset($postData['enable_image']) ? 1: 0,
                    'image_path'           => isset($postData['enable_image']) ? $postData['image_path'] : "",
                    'client_type_int'      => $postData['audience'],
                    'client_type'          => ($postData['audience'] == 2) ? $postData['isclient'] : 'All Clients',
                    'payment_status'       => 0,
                    'created_by'           => $UserId,
                    'background_color'     => $postData['background_color'],
                    'foreground_color'     => $postData['foreground_color'],
                    'font_color'           => $postData['font_color'],
                    'line_color'           => $postData['line_color'],
                    'button_color'         => $postData['button_color'],
                    'botton_text_color'    => $postData['botton_text_color'],
                    'created_at'           => date("Y-m-d H:i:s"),
                    'updated_at'           => date("Y-m-d H:i:s"),
            ]) );

            if($postData['message-type'] == 1){
                $fromname = 'Quick Update';
                $viewtype = 'quick_update';
            } else if($postData['message-type'] == 2){
                $fromname = 'Special Offer';
                $viewtype = 'special_offer';
            } else if($postData['message-type'] == 3){
                $fromname = 'Buy Voucher';
                $viewtype = 'buy_voucher';
            }
            
            $FROM_EMAIL     = env("MAIL_FROM_ADDRESS", "info@ikotel.ca");
            $FROM_NAME      = $fromname;
            $TO_EMAIL       = ($postData['email']) ? $postData['email'] : '';
            $CC_EMAIL       = '';//info@ikotel.ca
            $SUBJECT        = $postData['email_subject'];
            $MESSAGE        = 'Hi  This is test email!';
            $LOCATION       = $locationData;
            $MessageData    = $emailMessageData;
            $TYPE           = $viewtype;
            $UniqueId       = $this->unique_code(30).time();
            
            $voucherLists = array();
            if($emailMessageData->voucher_id > 0){
                $voucherLists = Voucher::select('id','name','services_ids','value','retailprice','validfor','enable_sale_limit','numberofsales')->where('user_id', $AdminId)->where('id',$emailMessageData->voucher_id)->where('is_online',1)->orderBy('id', 'desc')->get()->first()->toArray();
            }
            
            $SendMail = Mail::to($TO_EMAIL)->send(new EmailBlast($FROM_EMAIL,$FROM_NAME,$SUBJECT,$MESSAGE,$LOCATION,$MessageData,$TYPE,$UniqueId,$voucherLists));//->cc([$CC_EMAIL])
            
            /*$GroupEmailBlast = Group_email_blast::find($blastClient->id);
            $GroupEmailBlast->is_sent              = 1;
            $GroupEmailBlast->updated_at           = date("Y-m-d H:i:s");
            $GroupEmailBlast->save();*/
            
            EmailLog::create([
                'user_id'   => $AdminId,
                'unique_id' => $UniqueId,
                'campaign_id'=> 0,
                'group_email_blast_id' => 0,
                'from_email' => $FROM_EMAIL,
                'to_email'  => $TO_EMAIL,
                'module_type' => 2,
                'module_type_text' => 'Blast Email',
                'created_at' => date('Y-m-d H:i:s')
            ]);
            /*$updateCampaign = Smart_campaign::find('id',$postData['id']);
            $updateCampaign->email_send_counter = $smartCampaign->email_send_counter + 1;
            $updateCampaign->save();*/
            $data["status"] = true;
            $data["message"] = "Email has been sent successfully.";
            return JsonReturn::success($data);
        // }
    }
    
    public function saveAsDraftEmailBlast(Request $request)
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
        
        $lastId = 0;
        $postData = $request->all();
        
        $setting = Setting::first();
        $fromNumber = $setting->telnyx_number;
        
        $status = 0;
        if($postData['edit_id'] == 0 || $postData['edit_id'] == "")
        {
            if($postData['message-type'] == 1)
            {
                $addEmailStatus = EmailMessage::create([
                    'user_id'              => $AdminId, 
                    'message_name'         => $postData['message_name'],
                    'email_subject'        => $postData['email_subject'],
                    'reply_email'          => $postData['email_reply'],
                    'title'                => $postData['title'],
                    'message'              => nl2br($postData['desc']),
                    'is_button'            => $postData['isbutton'],
                    'button_text'          => ($postData['isbutton'] == 2) ? $postData['btn-text'] : "",
                    'button_link'          => ($postData['isbutton'] == 2) ? $postData['btn_url'] : "",
                    'social_media_enable'  => isset($postData['isSocial'])?1:0,
                    'facebook_link'        => isset($postData['isSocial']) ? $postData['facebook'] : "",
                    'instagram_link'       => isset($postData['isSocial']) ? $postData['instagram'] : "",
                    'website'              => isset($postData['isSocial']) ? $postData['yoursite'] : "",
                    'message_price'        => $postData['message_price'],
                    'total_payable_price'  => $postData['total_payable_price'],
                    'is_sended'            => 0,
                    'message_type_int'     => 1,
                    'message_type_text'    => 'Quick update',
                    'is_image'             => isset($postData['enable_image']) ? 1: 0,
                    'image_path'           => isset($postData['enable_image']) ? $postData['image_path'] : "",
                    'client_type_int'      => $postData['audience'],
                    'client_type'          => ($postData['audience'] == 2) ? $postData['isclient'] : 'All Clients',
                    'created_by'           => $UserId,
                    'created_at'           => date("Y-m-d H:i:s"),
                    'updated_at'           => date("Y-m-d H:i:s"),
                ]);
                $lastId = $addEmailStatus->id;
                if($lastId)
                {
                    foreach ($postData['clients'] as $key => $value) 
                    {
                        $addGroupEmailBlast = Group_email_blast::create([
                            'user_id'       => $AdminId,  
                            'blast_email_id'=> $lastId,
                            'client_id'     => $value,
                            'created_at'    => date("Y-m-d H:i:s"),
                        ]);
                    }
                    $status = 1;
                }
            }
            else if($postData['message-type'] == 2)
            {
                $addEmailStatus = EmailMessage::create([
                    'user_id'              => $AdminId,     
                    'message_name'         => $postData['message_name'],
                    'email_subject'        => $postData['email_subject'],
                    'reply_email'          => $postData['email_reply'],
                    'title'                => $postData['title'],
                    'message'              => nl2br($postData['desc']),
                    'is_button'            => $postData['isbutton'],
                    'button_text'          => ($postData['isbutton'] == 2) ? $postData['btn-text'] : "",
                    'button_link'          => ($postData['isbutton'] == 2) ? $postData['btn_url'] : "",
                    'social_media_enable'  => isset($postData['isSocial'])?1:0,
                    'facebook_link'        => isset($postData['isSocial']) ? $postData['facebook'] : "",
                    'instagram_link'       => isset($postData['isSocial']) ? $postData['instagram'] : "",
                    'website'              => isset($postData['isSocial']) ? $postData['yoursite'] : "",
                    'discount_value'       => $postData['discount_value'],
                    'discount_type'        => $postData['discount_type'],
                    'message_price'        => $postData['message_price'],
                    'total_payable_price'  => $postData['total_payable_price'],
                    'appointment_limit'     => $postData['appoinmentsLimits'],
                    'valid_for'             => $postData['validFor'],
                    'is_sended'            => 0,
                    'message_type_int'     => 2,
                    'message_type_text'    => 'Special offer',
                    'services'             => json_encode($postData['service']),
                    'is_image'             => isset($postData['enable_image']) ? 1: 0,
                    'image_path'           => isset($postData['enable_image']) ? $postData['image_path'] : "",
                    'client_type_int'      => $postData['audience'],
                    'client_type'          => ($postData['audience'] == 2) ? $postData['isclient'] : 'All Clients',
                    'created_by'           => $UserId,
                    'created_at'           => date("Y-m-d H:i:s"),
                    'updated_at'           => date("Y-m-d H:i:s"),
                ]);
                $lastId = $addEmailStatus->id;
                if($lastId)
                {
                    foreach ($postData['clients'] as $key => $value) 
                    {
                        $addGroupEmailBlast = Group_email_blast::create([
                            'user_id'       => $AdminId,  
                            'blast_email_id'=> $lastId,
                            'client_id'     => $value,
                            'created_at'    => date("Y-m-d H:i:s"),
                        ]);
                    }
                    $status = 1;
                }
            } 
            else if($postData['message-type'] == 3)
            {
                $addEmailStatus = EmailMessage::create([
                    'user_id'              => $AdminId,  
                    'voucher_id'           => $postData['voucher_id'],      
                    'message_name'         => $postData['message_name'],
                    'email_subject'        => $postData['email_subject'],
                    'reply_email'          => $postData['email_reply'],
                    'title'                => $postData['title'],
                    'message'              => nl2br($postData['desc']),
                    'is_button'            => $postData['isbutton'],
                    'button_text'          => ($postData['isbutton'] == 2) ? $postData['btn-text'] : "",
                    'button_link'          => ($postData['isbutton'] == 2) ? $postData['btn_url'] : "",
                    'social_media_enable'  => isset($postData['isSocial'])?1:0,
                    'facebook_link'        => isset($postData['isSocial']) ? $postData['facebook'] : "",
                    'instagram_link'       => isset($postData['isSocial']) ? $postData['instagram'] : "",
                    'website'              => isset($postData['isSocial']) ? $postData['yoursite'] : "",
                    'message_price'        => $postData['message_price'],
                    'total_payable_price'  => $postData['total_payable_price'],
                    'is_sended'            => 0,
                    'message_type_int'     => 3,
                    'message_type_text'    => 'Buy Voucher',
                    'is_image'             => isset($postData['enable_image']) ? 1: 0,
                    'image_path'           => isset($postData['enable_image']) ? $postData['image_path'] : "",
                    'client_type_int'      => $postData['audience'],
                    'client_type'          => ($postData['audience'] == 2) ? $postData['isclient'] : 'All Clients',
                    'created_by'           => $UserId,
                    'created_at'           => date("Y-m-d H:i:s"),
                    'updated_at'           => date("Y-m-d H:i:s"),
                ]);
                $lastId = $addEmailStatus->id;
                if($lastId)
                {
                    foreach ($postData['clients'] as $key => $value) 
                    {
                        $addGroupEmailBlast = Group_email_blast::create([
                            'user_id'       => $AdminId,  
                            'blast_email_id'=> $lastId,
                            'client_id'     => $value,
                            'created_at'    => date("Y-m-d H:i:s"),
                        ]);
                    }
                    $status = 1;
                }
            }
        }
        else
        {
            if($postData['message-type'] == 1)
            {
                $addEmailStatus = EmailMessage::find($postData['edit_id']);
                $addEmailStatus->message_name       =$postData['message_name'];
                $addEmailStatus->email_subject      =$postData['email_subject'];
                $addEmailStatus->reply_email        =$postData['email_reply'];
                $addEmailStatus->title              =$postData['title'];
                $addEmailStatus->message            =$postData['desc'];
                $addEmailStatus->is_button          =$postData['isbutton'];
                $addEmailStatus->button_text        =($postData['isbutton'] == 2) ? $postData['btn-text'] : "";
                $addEmailStatus->button_link        =($postData['isbutton'] == 2) ? $postData['btn_url'] : "";
                $addEmailStatus->social_media_enable=isset($postData['isSocial'])?1:0;
                $addEmailStatus->facebook_link      =isset($postData['isSocial']) ? $postData['facebook'] : "";
                $addEmailStatus->instagram_link     =isset($postData['isSocial']) ? $postData['instagram'] : "";
                $addEmailStatus->website            =isset($postData['isSocial']) ? $postData['yoursite'] : "";
                $addEmailStatus->message_price      =$postData['message_price'];
                $addEmailStatus->total_payable_price=$postData['total_payable_price'];
                $addEmailStatus->is_sended          =0;
                $addEmailStatus->message_type_int   =1;
                $addEmailStatus->message_type_text  ='Special offer';
                $addEmailStatus->is_image           =isset($postData['enable_image']) ? 1: 0;
                $addEmailStatus->image_path         =isset($postData['enable_image']) ? $postData['image_path'] : "";
                $addEmailStatus->created_by         =$UserId;
                $addEmailStatus->updated_at         =date("Y-m-d H:i:s");
                Group_email_blast::where('blast_email_id',$postData['edit_id'])->delete();
                $lastId = $postData['edit_id'];
                if($addEmailStatus->save())
                {
                    foreach ($postData['clients'] as $key => $value) 
                    {
                        $addGroupEmailBlast = Group_email_blast::create([
                            'user_id'       => $AdminId,  
                            'blast_email_id'=> $lastId,
                            'client_id'     => $value,
                            'created_at'    => date("Y-m-d H:i:s"),
                        ]);
                    }
                    $status = 1;
                }
            }
            else if($postData['message-type'] == 2)
            {
                $addEmailStatus = EmailMessage::find($postData['edit_id']); 
                $addEmailStatus->message_name       =$postData['message_name'];
                $addEmailStatus->email_subject      =$postData['email_subject'];
                $addEmailStatus->reply_email        =$postData['email_reply'];
                $addEmailStatus->title              =$postData['title'];
                $addEmailStatus->message            =$postData['desc'];
                $addEmailStatus->is_button          =$postData['isbutton'];
                $addEmailStatus->button_text        =($postData['isbutton'] == 2) ? $postData['btn-text'] : "";
                $addEmailStatus->button_link        =($postData['isbutton'] == 2) ? $postData['btn_url'] : "";
                $addEmailStatus->social_media_enable=isset($postData['isSocial'])?1:0;
                $addEmailStatus->facebook_link      =isset($postData['isSocial']) ? $postData['facebook'] : "";
                $addEmailStatus->instagram_link     =isset($postData['isSocial']) ? $postData['instagram'] : "";
                $addEmailStatus->website            =isset($postData['isSocial']) ? $postData['yoursite'] : "";
                $addEmailStatus->discount_value     =$postData['discount_value'];
                $addEmailStatus->discount_type      =$postData['discount_type'];
                $addEmailStatus->message_price      =$postData['message_price'];
                $addEmailStatus->total_payable_price=$postData['total_payable_price'];
                $addEmailStatus->appointment_limit  =$postData['appoinmentsLimits'];
                $addEmailStatus->valid_for          =$postData['validFor'];
                $addEmailStatus->is_sended          =0;
                $addEmailStatus->message_type_int   =2;
                $addEmailStatus->message_type_text  ='Special offer';
                $addEmailStatus->services           =json_encode($postData['service']);
                $addEmailStatus->is_image           =isset($postData['enable_image']) ? 1: 0;
                $addEmailStatus->image_path         =isset($postData['enable_image']) ? $postData['image_path'] : "";
                $addEmailStatus->created_by         =$UserId;
                $addEmailStatus->updated_at         =date("Y-m-d H:i:s");
                Group_email_blast::where('blast_email_id',$postData['edit_id'])->delete();
                $lastId = $postData['edit_id'];
                if($addEmailStatus->save())
                {
                    foreach ($postData['clients'] as $key => $value) 
                    {
                        $addGroupEmailBlast = Group_email_blast::create([
                            'user_id'       => $AdminId,  
                            'blast_email_id'=> $lastId,
                            'client_id'     => $value,
                            'created_at'    => date("Y-m-d H:i:s"),
                        ]);
                    }
                    $status = 1;
                }
            } 
            else if($postData['message-type'] == 3)
            {
                $addEmailStatus = EmailMessage::find($postData['edit_id']);
                $addEmailStatus->voucher_id         =$postData['voucher_id'];
                $addEmailStatus->message_name       =$postData['message_name'];
                $addEmailStatus->email_subject      =$postData['email_subject'];
                $addEmailStatus->reply_email        =$postData['email_reply'];
                $addEmailStatus->title              =$postData['title'];
                $addEmailStatus->message            =$postData['desc'];
                $addEmailStatus->is_button          =$postData['isbutton'];
                $addEmailStatus->button_text        =($postData['isbutton'] == 2) ? $postData['btn-text'] : "";
                $addEmailStatus->button_link        =($postData['isbutton'] == 2) ? $postData['btn_url'] : "";
                $addEmailStatus->social_media_enable=isset($postData['isSocial'])?1:0;
                $addEmailStatus->facebook_link      =isset($postData['isSocial']) ? $postData['facebook'] : "";
                $addEmailStatus->instagram_link     =isset($postData['isSocial']) ? $postData['instagram'] : "";
                $addEmailStatus->website            =isset($postData['isSocial']) ? $postData['yoursite'] : "";
                $addEmailStatus->message_price      =$postData['message_price'];
                $addEmailStatus->total_payable_price=$postData['total_payable_price'];
                $addEmailStatus->is_sended          =0;
                $addEmailStatus->message_type_int   =3;
                $addEmailStatus->message_type_text  ='Buy Voucher';
                $addEmailStatus->is_image           =isset($postData['enable_image']) ? 1: 0;
                $addEmailStatus->image_path         =isset($postData['enable_image']) ? $postData['image_path'] : "";
                $addEmailStatus->created_by         =$UserId;
                $addEmailStatus->updated_at         =date("Y-m-d H:i:s");
                Group_email_blast::where('blast_email_id',$postData['edit_id'])->delete();
                $lastId = $postData['edit_id'];
                if($addEmailStatus->save())
                {
                    foreach ($postData['clients'] as $key => $value) 
                    {
                        $addGroupEmailBlast = Group_email_blast::create([
                            'user_id'       => $AdminId,  
                            'blast_email_id'=> $lastId,
                            'client_id'     => $value,
                            'created_at'    => date("Y-m-d H:i:s"),
                        ]);
                    }
                    $status = 1;
                }
            }
        }
        
        if($status == 1){
            $data["status"]   = true;
            $data["message"]  = "Email blast saved as draft succesfully.";
            $data["redirect"] = route('marketing_blast_messages');
            return JsonReturn::success($data);
        } else {
            $data["status"]   = false;
            $data["message"]  = "Something went wrong!";
            $data["redirect"] = route('marketing_blast_messages');
            return JsonReturn::success($data);
        }
    }
    
    public function add_sms_text_message()
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
        
        $getUserDetails = User::getUserbyID($AdminId);  
        
        $userData = User::where('id',Auth::id())->first();
        $allClients = Clients::where('user_id', $AdminId)->get();
        $serviceCategory = ServiceCategory::where(['user_id' => $AdminId,'is_deleted'=>0])->get()->toArray();
        $setting = Setting::first();
        foreach ($serviceCategory as $key => $serviceCat) 
        {
            $services = Services::where(['service_category'=>$serviceCat['id'],'is_deleted'=>0])->get()->toArray();
            foreach ($services as $serviceKey => $service)
            {
                $servicesPrice = ServicesPrice::where(['service_id'=>$service['id']])->get()->toArray();
                $services[$serviceKey]['service_price'] = $servicesPrice;
            }
            $serviceCategory[$key]['service'] = $services;
        }
        
        $cardList = array();
        if($getUserDetails->stripe_customer_code != ''){
            $cardListResponse = $this->getStripeCardList($getUserDetails->stripe_customer_code);

            if(!empty($cardListResponse) && $cardListResponse['status'] == true){
                $cardList = $cardListResponse['card_list'];
            }
        }
        
        $voucherLists = Voucher::select('id','name','services_ids','value','retailprice','validfor','enable_sale_limit','numberofsales')->where('user_id', $AdminId)->where('is_online',1)->orderBy('id', 'desc')->get()->toArray();
        
        $locationData = Location::where('user_id',$AdminId)->get()->toArray();
        
        return view('marketing.add_sms_text_message', compact('allClients','serviceCategory','setting','cardList','userData','voucherLists','locationData'));
    }
    
    public function edit_sms_message($id)
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
        
        $getUserDetails = User::getUserbyID($AdminId);  
        
        $setting = Setting::first();
        $allClients = Clients::where('user_id', $AdminId)->get();
        $serviceCategory = ServiceCategory::where(['user_id' => $AdminId,'is_deleted'=>0])->get()->toArray();
        $smsData = Sms_message::where('id',$id)->first();
        foreach ($serviceCategory as $key => $serviceCat) 
        {
            $services = Services::where(['service_category'=>$serviceCat['id'],'is_deleted'=>0])->get()->toArray();
            foreach ($services as $serviceKey => $service)
            {
                $servicesPrice = ServicesPrice::where(['service_id'=>$service['id']])->get()->toArray();
                $services[$serviceKey]['service_price'] = $servicesPrice;
            }
            $serviceCategory[$key]['service'] = $services;
        }
        if(empty($smsData))
        {
            return redirect(route('marketing_blast_messages'));
        }
        
        $cardList = array();
        if($getUserDetails->stripe_customer_code != ''){
            $cardListResponse = $this->getStripeCardList($getUserDetails->stripe_customer_code);

            if(!empty($cardListResponse) && $cardListResponse['status'] == true){
                $cardList = $cardListResponse['card_list'];
            }
        }
        
        return view('marketing.edit_sms', compact('allClients','serviceCategory','smsData','setting','cardList'));
    }
    public function edit_email_message($id)
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
        
        $getUserDetails = User::getUserbyID($AdminId);  
        
        $allClients = Clients::where('user_id', $AdminId)->get();
        $serviceCategory = ServiceCategory::where(['user_id'=> $AdminId,'is_deleted'=>0])->get()->toArray();
        $locationData = Location::where('user_id', $AdminId)->get()->toArray();
        $emailMessageData = EmailMessage::where('id',$id)->first();
        $clientData = Group_email_blast::where('blast_email_id',$id)->get()->toArray();
        $setting = Setting::first();
        foreach ($serviceCategory as $key => $serviceCat) 
        {
            $services = Services::where(['service_category'=>$serviceCat['id'],'is_deleted'=>0])->get()->toArray();
            foreach ($services as $serviceKey => $service)
            {
                $servicesPrice = ServicesPrice::where(['service_id'=>$service['id']])->get()->toArray();
                $services[$serviceKey]['service_price'] = $servicesPrice;
            }
            $serviceCategory[$key]['service'] = $services;
        }
        
        $cardList = array();
        if($getUserDetails->stripe_customer_code != ''){
            $cardListResponse = $this->getStripeCardList($getUserDetails->stripe_customer_code);

            if(!empty($cardListResponse) && $cardListResponse['status'] == true){
                $cardList = $cardListResponse['card_list'];
            }
        }
        
        $voucherLists = array();
        if($emailMessageData->voucher_id != '' && $emailMessageData->voucher_id > 0){
            $voucherLists = Voucher::select('id','name','services_ids','value','retailprice','validfor','enable_sale_limit','numberofsales')->where('user_id', $AdminId)->where('is_online',1)->where('id',$emailMessageData->voucher_id)->orderBy('id', 'desc')->get()->first()->toArray();
        }
        
        return view('marketing.edit_email_message', compact('allClients','serviceCategory','locationData','emailMessageData','clientData','setting','cardList','voucherLists'));
    }
    
    public function saveSMSBlast(Request $request)
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
        
        $lastId = 0;
        $postData = $request->all();
        
        $setting = Setting::first();
        $userData = User::where('id',Auth::id())->first();
        $fromNumber = $setting->telnyx_number;
        
        if($postData['edit_id'] == 0 || $postData['edit_id'] == "")
        {
            $status = 0;
            if($postData['message_type'] == 1)
            {
                $addSmsStatus = Sms_message::create([
                    'user_id'            => $AdminId,    
                    'message_name'       => $postData['message_name'],
                    'message_description'=> $postData['desc'],
                    'is_link'            => $postData['isLink'],
                    'btn_url'            => $postData['btn-url'],
                    'sms_type_int'       => $postData['message_type'],
                    'client_type'        => $postData['audience'],
                    'group_type'         => $postData['group_type'],
                    'sms_type_text'      => 'Quick update',
                    'payment_status'     => '0',
                    'created_by'         => $UserId,
                    'created_at'         => date("Y-m-d H:i:s"),
                    'updated_at'         => date("Y-m-d H:i:s"),
                ]);
                $lastId = $addSmsStatus->id;
                if($addSmsStatus->id)
                {
                    foreach ($postData['clients'] as $key => $value) 
                    {
                        $addGroupSmsBlast = Group_sms_blast::create([
                            'user_id'       => $AdminId,  
                            'blast_sms_id'=> $addSmsStatus->id,
                            'client_id'     => $value,
                            'created_at'    => date("Y-m-d H:i:s"),
                        ]);
                    }
                    $status = 1;
                }
            }
            else if($postData['message_type'] == 2)
            {
                $addSmsStatus = Sms_message::create([
                    'user_id'            => $AdminId,    
                    'message_name'       => $postData['message_name'],
                    'message_description'=> $postData['desc'],
                    'is_link'            => $postData['isLink'],
                    'btn_url'            => $postData['btn-url'],
                    'discount_value'     => $postData['discount_value'],
                    'discount_type'      => $postData['discount-type'],
                    'appointment_limit'  => $postData['appoinmentsLimits'],
                    'valid_for'          => $postData['validFor'],
                    'sms_type_int'       => $postData['message_type'],
                    'client_type'        => $postData['audience'],
                    'group_type'         => $postData['group_type'],
                    'sms_type_text'      => 'Special offer',
                    'services'           => json_encode($postData['service']),
                    'created_by'         => $UserId,
                    'created_at'         => date("Y-m-d H:i:s"),
                    'updated_at'         => date("Y-m-d H:i:s"),
                ]);
                
                $lastId = $addSmsStatus->id;
                
                if($addSmsStatus->id)
                {
                    foreach ($postData['clients'] as $key => $value) 
                    {
                        $addGroupSmsBlast = Group_sms_blast::create([
                            'user_id'       => $AdminId,  
                            'blast_sms_id'  => $addSmsStatus->id,
                            'client_id'     => $value,
                            'created_at'    => date("Y-m-d H:i:s"),
                        ]);
                    }
                    $status = 1;
                }
            }
            else if($postData['message_type'] == 3)
            {
                $addSmsStatus = Sms_message::create([
                    'user_id'            => $AdminId,    
                    'voucher_id'         => $postData['voucher_id'],    
                    'message_name'       => $postData['message_name'],
                    'message_description'=> $postData['desc'],
                    'is_link'            => $postData['isLink'],
                    'btn_url'            => $postData['btn-url'],
                    'sms_type_int'       => $postData['message_type'],
                    'client_type'        => $postData['audience'],
                    'group_type'         => $postData['group_type'],
                    'sms_type_text'      => 'Buy Voucher',
                    'payment_status'     => '0',
                    'created_by'         => $UserId,
                    'created_at'         => date("Y-m-d H:i:s"),
                    'updated_at'         => date("Y-m-d H:i:s"),
                ]);
                $lastId = $addSmsStatus->id;
                if($addSmsStatus->id)
                {
                    foreach ($postData['clients'] as $key => $value) 
                    {
                        $addGroupSmsBlast = Group_sms_blast::create([
                            'user_id'       => $AdminId,  
                            'blast_sms_id'  => $addSmsStatus->id,
                            'client_id'     => $value,
                            'created_at'    => date("Y-m-d H:i:s"),
                        ]);
                    }
                    $status = 1;
                }
            }
        }
        else
        {
            $lastId = $postData['edit_id'];
            if($postData['message_type'] == 1)
            {
                $saveSMSBlast = Sms_message::find($postData['edit_id']);
                $saveSMSBlast->message_name       = $postData['message_name'];
                $saveSMSBlast->message_description= $postData['desc'];
                $saveSMSBlast->is_link            = $postData['isLink'];
                $saveSMSBlast->btn_url            = $postData['btn-url'];
                $saveSMSBlast->sms_type_int       = $postData['message_type'];
                $saveSMSBlast->client_type        = $postData['audience'];
                $saveSMSBlast->group_type         = $postData['group_type'];
                $saveSMSBlast->sms_type_text      = 'Quick update';
                $saveSMSBlast->created_by         = $UserId;
                $saveSMSBlast->created_at         = date("Y-m-d H:i:s");
                $saveSMSBlast->updated_at         = date("Y-m-d H:i:s");
                if($saveSMSBlast->save())
                {
                    Group_sms_blast::where('blast_sms_id',$postData['edit_id'])->delete();
                    foreach ($postData['clients'] as $key => $value) 
                    {
                        $addGroupSmsBlast = Group_sms_blast::create([
                            'user_id'       => $AdminId,
                            'blast_sms_id'  => $postData['edit_id'],
                            'client_id'     => $value,
                            'created_at'    => date("Y-m-d H:i:s"),
                        ]);
                    }
                    $status = 1;
                }
                else
                {
                    $status = 0;
                }
            }
            else if($postData['message_type'] == 2)
            {
                $saveSms = Sms_message::find($postData['edit_id']);
                $saveSms->message_name       = $postData['message_name'];
                $saveSms->message_description= $postData['desc'];
                $saveSms->is_link            = $postData['isLink'];
                $saveSms->btn_url            = $postData['btn-url'];
                $saveSms->discount_value     = $postData['discount_value'];
                $saveSms->discount_type      = $postData['discount-type'];
                $saveSms->appointment_limit  = $postData['appoinmentsLimits'];
                $saveSms->valid_for          = $postData['validFor'];
                $saveSms->sms_type_int       = $postData['message_type'];
                $saveSms->client_type        = $postData['audience'];
                $saveSms->group_type         = $postData['group_type'];
                $saveSms->sms_type_text      = 'Quick update';
                $saveSms->services           = json_encode($postData['service']);
                $saveSms->created_by         = $UserId;
                $saveSms->created_at         = date("Y-m-d H:i:s");
                $saveSms->updated_at         = date("Y-m-d H:i:s");
                if($saveSms->save())
                {
                    Group_email_blast::where('blast_email_id',$postData['edit_id'])->delete();
                    foreach ($postData['clients'] as $key => $value) 
                    {
                        $addGroupEmailBlast = Group_email_blast::create([
                            'user_id'       => $AdminId,  
                            'blast_email_id'=> $postData['edit_id'],
                            'client_id'     => $value,
                            'created_at'    => date("Y-m-d H:i:s"),
                        ]);
                    }
                    $status = 1;
                }
                else
                {
                    $status = 0;    
                }
            }
            else if($postData['message_type'] == 3)
            {
                $saveSMSBlast = Sms_message::find($postData['edit_id']);
                $saveSMSBlast->voucher_id         = $postData['voucher_id'];
                $saveSMSBlast->message_name       = $postData['message_name'];
                $saveSMSBlast->message_description= $postData['desc'];
                $saveSMSBlast->is_link            = $postData['isLink'];
                $saveSMSBlast->btn_url            = $postData['btn-url'];
                $saveSMSBlast->sms_type_int       = $postData['message_type'];
                $saveSMSBlast->client_type        = $postData['audience'];
                $saveSMSBlast->group_type         = $postData['group_type'];
                $saveSMSBlast->sms_type_text      = 'Buy Voucher';
                $saveSMSBlast->created_by         = $UserId;
                $saveSMSBlast->created_at         = date("Y-m-d H:i:s");
                $saveSMSBlast->updated_at         = date("Y-m-d H:i:s");
                if($saveSMSBlast->save())
                {
                    Group_sms_blast::where('blast_sms_id',$postData['edit_id'])->delete();
                    foreach ($postData['clients'] as $key => $value) 
                    {
                        $addGroupSmsBlast = Group_sms_blast::create([
                            'user_id'       => $AdminId,
                            'blast_sms_id'  => $postData['edit_id'],
                            'client_id'     => $value,
                            'created_at'    => date("Y-m-d H:i:s"),
                        ]);
                    }
                    $status = 1;
                }
                else
                {
                    $status = 0;
                }
            }
        }
        
        if($status)
        {
            $getUserDetails = User::getUserbyID($AdminId);  
            
            $chargeData = array(
                'amount'      => round($postData['amount'] * 100),
                'description' => 'SMS Blast Message',
                'metadata'    => array('email' => $getUserDetails->email,'user_id' => $getUserDetails->id,'campaign_id' => $lastId),
                'currency'    => $postData['currency'],
                'customer'    => $getUserDetails->stripe_customer_code,
                'card'        => $postData['paymentCardId']
            );
                
            $chargeResponse = $this->chargeStripeCustomer($chargeData);
                
            if(!empty($chargeResponse) && $chargeResponse['status'] == true)
            {
                if($chargeResponse['charge_id'] != '' && $chargeResponse['transaction_id'] != '')
                {
                    $payment_response = Payment_response::create([
                        'user_id'           => $AdminId,
                        'type'              => 'SMS',
                        'email_sms_id'      => $lastId,
                        'charge_id'         => $chargeResponse['charge_id'],
                        'currency'          => 'CAD',
                        'amount'            => $postData['amount'],
                        'transaction_id'    => $chargeResponse['transaction_id'],
                        'card_brand'        => $chargeResponse['full_response']->source->brand,
                        'card_last4'        => $chargeResponse['full_response']->source->last4,
                        'card_id'           => $chargeResponse['full_response']->payment_method,
                        'module_type'       => '1',
                        'module_text'       => 'SMS Blast Payment',
                        'status'            => $chargeResponse['full_response']->paid,
                        'status_text'       => ($chargeResponse['full_response']->paid == 1) ? 'PAID' : 'UNPAID',
                        'payment_response'  => json_encode($chargeResponse['full_response']),
                        'created_at'        => date("Y-m-d H:i:s")
                    ]);
                    $blastSMSClients = Group_sms_blast::where('blast_sms_id',$lastId)->get();
                    
                    $sms_body = "";
                    $is_sent = false;
                    foreach ($blastSMSClients as $blastClient) 
                    {
                        $clientData = Clients::where('id',$blastClient->client_id)->first();
                        if(!empty($clientData))
                        {   
                            $GroupSMSBlastId = $blastClient->id;
                    
                            $toNumber = $clientData->mo_country_code.$clientData->mobileno;
                            
                            $customerMobileNo = str_replace(" ","",$toNumber);
                            $customerMobileNo = str_replace("-","",$customerMobileNo);
                            $customerMobileNo = str_replace("+","",$customerMobileNo);
                            
                            if($postData['message_type'] == 3){
                                $messge_link = config('app.url').'/myVouchers';
                                $shortCode = Shortener::urlToShortCode($messge_link);
                                $shortUrl  = config('app.SHORT_URL').$shortCode;
                            } else {
                                if($postData['isLink'] == 2){
                                    $messge_link = $postData['btn-url'];    
                                    $shortCode = Shortener::urlToShortCode($messge_link);
                                    $shortUrl  = config('app.SHORT_URL').$shortCode;
                                } else if($postData['isLink'] == 1) {
                                    $messge_link = config('app.url');
                                    $shortCode = Shortener::urlToShortCode($messge_link);
                                    $shortUrl  = config('app.SHORT_URL').$shortCode;
                                } else {
                                    $messge_link = '';
                                    $shortUrl = '';
                                    $shortCode = '';
                                }   
                            }
                            
                            if($shortUrl != ''){
                                $sms_body = $postData['desc'].' '.$shortUrl;
                            } else {
                                $sms_body = $postData['desc'];  
                            }
                            
                            $smsResponse = TelnyxSms::sendTelnyxSms($customerMobileNo,$fromNumber,$sms_body);
                            if(isset($smsResponse->errors))
                            {
                                $sms_status = 'failed'; 
                                $smsid = "";
                                $error_message = $smsResponse->errors[0]->detail;
                            } 
                            else
                            {
                                $is_sent = true;
                                $error_message = "";
                                
                                $updateSmsMsg = Sms_message::find($lastId);
                                $updateSmsMsg->is_sms_sended = 1;
                                $updateSmsMsg->payment_status = $chargeResponse['full_response']->paid;
                                $updateSmsMsg->save();
                                $sms_status = 'send';
                                
                                $smsid = $smsResponse->data->id;
                                
                                $Group_sms_blast = Group_sms_blast::find($blastClient->id);
                                $Group_sms_blast->is_sent    = 1;
                                $Group_sms_blast->long_url   = $messge_link;
                                $Group_sms_blast->short_url  = $shortUrl;
                                $Group_sms_blast->short_code = $shortCode;
                                $Group_sms_blast->updated_at = date("Y-m-d H:i:s");
                                $Group_sms_blast->save();
                            }
                            
                            $sms_log = Sms_log::create([
                                'user_id'            => $AdminId,
                                'client_id'          => $clientData->id,
                                'sms_id'             => $smsid,
                                'send_from'          => $fromNumber,
                                'send_to'            => $customerMobileNo,
                                'group_sms_blast_id' => $GroupSMSBlastId,
                                'client_name'        => $clientData->firstname.' '.$clientData->lastname,
                                'message'            => $sms_body,
                                'direction'          => 'outbox',
                                'sms_status'         => $sms_status,
                                'error_message'      => $error_message,
                                'module_type'        => '1',
                                'module_type_text'   => 'SMS Blast Messages',
                                'sms_response'       => json_encode($smsResponse),
                                'created_at'         => date("Y-m-d H:i:s")
                            ]);
                        }
                    }
                    
                    if($is_sent) {
                        $data["status"]   = true;
                        $data["message"]  = 'SMS has been send successfully.';
                        $data["redirect"] = route('marketing_blast_messages');
                        return JsonReturn::success($data);
                    } else {
                        $data["status"]   = false;
                        $data["message"]  = 'SMS not sent. Saved as draft.';
                        $data["redirect"] = route('marketing_blast_messages');
                        return JsonReturn::success($data);
                    }
                }
                else 
                {
                    $data["status"]   = false;
                    $data["message"]  = $chargeResponse['message'];
                    $data["redirect"] = route('marketing_blast_messages');
                    return JsonReturn::success($data);
                }
            }
            else 
            {
                $data["status"]   = false;
                $data["message"]  = $chargeResponse['message'];
                $data["redirect"] = route('marketing_blast_messages');
                return JsonReturn::success($data);
            }
        } 
        else
        {
            $data["status"]   = false;
            $data["message"]  = "Something went wrong.";
            $data["redirect"] = route('marketing_blast_messages');
            return JsonReturn::success($data);
        }
    }
    
    public function saveAsDraftSMSBlast(Request $request)
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
        
        $lastId = 0;
        $postData = $request->all();
        
        $setting = Setting::first();
        $userData = User::where('id',Auth::id())->first();
        $fromNumber = $setting->telnyx_number;
        
        if($postData['edit_id'] == 0 || $postData['edit_id'] == "")
        {
            $status = 0;
            if($postData['message_type'] == 1)
            {
                $addSmsStatus = Sms_message::create([
                    'user_id'            => $AdminId,    
                    'message_name'       => $postData['message_name'],
                    'message_description'=> $postData['desc'],
                    'is_link'            => $postData['isLink'],
                    'btn_url'            => $postData['btn-url'],
                    'sms_type_int'       => $postData['message_type'],
                    'client_type'        => $postData['audience'],
                    'group_type'         => $postData['group_type'],
                    'sms_type_text'      => 'Quick update',
                    'payment_status'     => '0',
                    'created_by'         => $UserId,
                    'created_at'         => date("Y-m-d H:i:s"),
                    'updated_at'         => date("Y-m-d H:i:s"),
                ]);
                $lastId = $addSmsStatus->id;
                if($addSmsStatus->id)
                {
                    foreach ($postData['clients'] as $key => $value) 
                    {
                        $addGroupSmsBlast = Group_sms_blast::create([
                            'user_id'       => $AdminId,  
                            'blast_sms_id'  => $addSmsStatus->id,
                            'client_id'     => $value,
                            'created_at'    => date("Y-m-d H:i:s"),
                        ]);
                    }
                    $status = 1;
                }
            }
            else if($postData['message_type'] == 2)
            {
                $addSmsStatus = Sms_message::create([
                    'user_id'            => $AdminId,    
                    'message_name'       => $postData['message_name'],
                    'message_description'=> $postData['desc'],
                    'is_link'            => $postData['isLink'],
                    'btn_url'            => $postData['btn-url'],
                    'discount_value'     => $postData['discount_value'],
                    'discount_type'      => $postData['discount-type'],
                    'appointment_limit'  => $postData['appoinmentsLimits'],
                    'valid_for'          => $postData['validFor'],
                    'sms_type_int'       => $postData['message_type'],
                    'client_type'        => $postData['audience'],
                    'group_type'         => $postData['group_type'],
                    'sms_type_text'      => 'Special offer',
                    'services'           => json_encode($postData['service']),
                    'created_by'         => $UserId,
                    'created_at'         => date("Y-m-d H:i:s"),
                    'updated_at'         => date("Y-m-d H:i:s"),
                ]);
                
                $lastId = $addSmsStatus->id;
                
                if($addSmsStatus->id)
                {
                    foreach ($postData['clients'] as $key => $value) 
                    {
                        $addGroupSmsBlast = Group_sms_blast::create([
                            'user_id'       => $AdminId,  
                            'blast_sms_id'  => $addSmsStatus->id,
                            'client_id'     => $value,
                            'created_at'    => date("Y-m-d H:i:s"),
                        ]);
                    }
                    $status = 1;
                }
            }
            else if($postData['message_type'] == 3)
            {
                $addSmsStatus = Sms_message::create([
                    'user_id'            => $AdminId,    
                    'voucher_id'         => $postData['voucher_id'],    
                    'message_name'       => $postData['message_name'],
                    'message_description'=> $postData['desc'],
                    'is_link'            => $postData['isLink'],
                    'btn_url'            => $postData['btn-url'],
                    'sms_type_int'       => $postData['message_type'],
                    'client_type'        => $postData['audience'],
                    'group_type'         => $postData['group_type'],
                    'sms_type_text'      => 'Buy Voucher',
                    'payment_status'     => '0',
                    'created_by'         => $UserId,
                    'created_at'         => date("Y-m-d H:i:s"),
                    'updated_at'         => date("Y-m-d H:i:s"),
                ]);
                $lastId = $addSmsStatus->id;
                if($addSmsStatus->id)
                {
                    foreach ($postData['clients'] as $key => $value) 
                    {
                        $addGroupSmsBlast = Group_sms_blast::create([
                            'user_id'       => $AdminId,  
                            'blast_sms_id'  => $addSmsStatus->id,
                            'client_id'     => $value,
                            'created_at'    => date("Y-m-d H:i:s"),
                        ]);
                    }
                    $status = 1;
                }
            }
        }
        else
        {
            $lastId = $postData['edit_id'];
            if($postData['message_type'] == 1)
            {
                $saveSMSBlast = Sms_message::find($postData['edit_id']);
                $saveSMSBlast->message_name       = $postData['message_name'];
                $saveSMSBlast->message_description= $postData['desc'];
                $saveSMSBlast->is_link            = $postData['isLink'];
                $saveSMSBlast->btn_url            = $postData['btn-url'];
                $saveSMSBlast->sms_type_int       = $postData['message_type'];
                $saveSMSBlast->client_type        = $postData['audience'];
                $saveSMSBlast->group_type         = $postData['group_type'];
                $saveSMSBlast->sms_type_text      = 'Quick update';
                $saveSMSBlast->created_by         = $UserId;
                $saveSMSBlast->created_at         = date("Y-m-d H:i:s");
                $saveSMSBlast->updated_at         = date("Y-m-d H:i:s");
                if($saveSMSBlast->save())
                {
                    Group_sms_blast::where('blast_sms_id',$postData['edit_id'])->delete();
                    foreach ($postData['clients'] as $key => $value) 
                    {
                        $addGroupSmsBlast = Group_sms_blast::create([
                            'user_id'       => $AdminId,
                            'blast_sms_id'  => $postData['edit_id'],
                            'client_id'     => $value,
                            'created_at'    => date("Y-m-d H:i:s"),
                        ]);
                    }
                    $status = 1;
                }
                else
                {
                    $status = 0;
                }
            }
            else if($postData['message_type'] == 2)
            {
                $saveSms = Sms_message::find($postData['edit_id']);
                $saveSms->message_name       = $postData['message_name'];
                $saveSms->message_description= $postData['desc'];
                $saveSms->is_link            = $postData['isLink'];
                $saveSms->btn_url            = $postData['btn-url'];
                $saveSms->discount_value     = $postData['discount_value'];
                $saveSms->discount_type      = $postData['discount-type'];
                $saveSms->appointment_limit  = $postData['appoinmentsLimits'];
                $saveSms->valid_for          = $postData['validFor'];
                $saveSms->sms_type_int       = $postData['message_type'];
                $saveSms->client_type        = $postData['audience'];
                $saveSms->group_type         = $postData['group_type'];
                $saveSms->sms_type_text      = 'Quick update';
                $saveSms->services           = json_encode($postData['service']);
                $saveSms->created_by         = $UserId;
                $saveSms->created_at         = date("Y-m-d H:i:s");
                $saveSms->updated_at         = date("Y-m-d H:i:s");
                if($saveSms->save())
                {
                    Group_email_blast::where('blast_email_id',$postData['edit_id'])->delete();
                    foreach ($postData['clients'] as $key => $value) 
                    {
                        $addGroupEmailBlast = Group_email_blast::create([
                            'user_id'       => $AdminId,  
                            'blast_email_id'=> $postData['edit_id'],
                            'client_id'     => $value,
                            'created_at'    => date("Y-m-d H:i:s"),
                        ]);
                    }
                    $status = 1;
                }
                else
                {
                    $status = 0;    
                }
            }
            else if($postData['message_type'] == 3)
            {
                $saveSMSBlast = Sms_message::find($postData['edit_id']);
                $saveSMSBlast->voucher_id         = $postData['voucher_id'];
                $saveSMSBlast->message_name       = $postData['message_name'];
                $saveSMSBlast->message_description= $postData['desc'];
                $saveSMSBlast->is_link            = $postData['isLink'];
                $saveSMSBlast->btn_url            = $postData['btn-url'];
                $saveSMSBlast->sms_type_int       = $postData['message_type'];
                $saveSMSBlast->client_type        = $postData['audience'];
                $saveSMSBlast->group_type         = $postData['group_type'];
                $saveSMSBlast->sms_type_text      = 'Buy Voucher';
                $saveSMSBlast->created_by         = $UserId;
                $saveSMSBlast->created_at         = date("Y-m-d H:i:s");
                $saveSMSBlast->updated_at         = date("Y-m-d H:i:s");
                if($saveSMSBlast->save())
                {
                    Group_sms_blast::where('blast_sms_id',$postData['edit_id'])->delete();
                    foreach ($postData['clients'] as $key => $value) 
                    {
                        $addGroupSmsBlast = Group_sms_blast::create([
                            'user_id'       => $AdminId,
                            'blast_sms_id'  => $postData['edit_id'],
                            'client_id'     => $value,
                            'created_at'    => date("Y-m-d H:i:s"),
                        ]);
                    }
                    $status = 1;
                }
                else
                {
                    $status = 0;
                }
            }
        }
        
        if($status)
        {
            $data["status"]   = true;
            $data["message"]  = 'SMS blast saved as draft succesfully.';
            $data["redirect"] = route('marketing_blast_messages');
            return JsonReturn::success($data);
        }
        else
        {
            $data["status"]   = false;
            $data["message"]  = 'Something went wrong!';
            $data["redirect"] = route('marketing_blast_messages');
            return JsonReturn::success($data);
        }
    }

    public function sendTestSMSBlast(Request $request)
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

        $postData = $request->all();

        $setting = Setting::first();
        $fromNumber = $setting->telnyx_number;

        /*$clientData = Clients::where('id',$blastClient->client_id)->first();
        if(!empty($clientData))
        {   */
            //$GroupSMSBlastId = $blastClient->id;
        
            $toNumber = $postData['mo_country_code'].$postData['mobileno'];
            
            $customerMobileNo = str_replace(" ","",$toNumber);
            $customerMobileNo = str_replace("-","",$customerMobileNo);
            $customerMobileNo = str_replace("+","",$customerMobileNo);
            
            if($postData['message_type'] == 3){
                $messge_link = config('app.url').'/myVouchers';
                $shortCode = Shortener::urlToShortCode($messge_link);
                $shortUrl  = config('app.SHORT_URL').$shortCode;
            } else {
                if($postData['isLink'] == 2){
                    $messge_link = $postData['btn-url'];    
                    $shortCode = Shortener::urlToShortCode($messge_link);
                    $shortUrl  = config('app.SHORT_URL').$shortCode;
                } else if($postData['isLink'] == 1) {
                    $messge_link = config('app.url');
                    $shortCode = Shortener::urlToShortCode($messge_link);
                    $shortUrl  = config('app.SHORT_URL').$shortCode;
                } else {
                    $messge_link = '';
                    $shortUrl = '';
                    $shortCode = '';
                }   
            }
            
            if($shortUrl != ''){
                $sms_body = $postData['desc'].' '.$shortUrl;
            } else {
                $sms_body = $postData['desc'];  
            }
            
            $smsResponse = TelnyxSms::sendTelnyxSms($customerMobileNo,$fromNumber,$sms_body);
            if(isset($smsResponse->errors))
            {
                $sms_status = 'failed'; 
                $smsid = "";
                $error_message = $smsResponse->errors[0]->detail;
            } 
            else
            {
                $error_message = "";
                
                // $updateSmsMsg = Sms_message::find($lastId);
                // $updateSmsMsg->is_sms_sended = 1;
                // $updateSmsMsg->payment_status = $chargeResponse['full_response']->paid;
                // $updateSmsMsg->save();
                $sms_status = 'send';
                
                $smsid = $smsResponse->data->id;
                
                /*$Group_sms_blast = Group_sms_blast::find($blastClient->id);
                $Group_sms_blast->is_sent    = 1;
                $Group_sms_blast->long_url   = $messge_link;
                $Group_sms_blast->short_url  = $shortUrl;
                $Group_sms_blast->short_code = $shortCode;
                $Group_sms_blast->updated_at = date("Y-m-d H:i:s");
                $Group_sms_blast->save();*/
            }
            
            $sms_log = Sms_log::create([
                'user_id'            => $AdminId,
                'client_id'          => NULL,
                'sms_id'             => $smsid,
                'send_from'          => $fromNumber,
                'send_to'            => $customerMobileNo,
                'group_sms_blast_id' => 0,
                'client_name'        => 'Test Message',
                'message'            => $sms_body,
                'direction'          => 'outbox',
                'sms_status'         => $sms_status,
                'error_message'      => $error_message,
                'module_type'        => '1',
                'module_type_text'   => 'SMS Blast Messages',
                'sms_response'       => json_encode($smsResponse),
                'created_at'         => date("Y-m-d H:i:s")
            ]);
        // }

        $data["status"] = true;
        $data["message"] = "SMS has been sent successfully.";
        return JsonReturn::success($data);
    }
    
    public function deleteBlastMessages(Request $request)
    {
        $postData = $request->all();
        if(!empty($postData['id']) && $postData['type'] == 'SMS')
        {
            $blastSMS = Group_sms_blast::where('blast_sms_id',$postData['id'])->delete();
            if($blastSMS)
            {
                $smsDelete = Sms_message::where('id',$postData['id'])->delete();
                if($smsDelete)
                {
                    $data["status"] = true;
                    $data["message"] = "Data has been deleted.";
                }
                else
                {
                    $data["status"] = false;
                    $data["message"] = "Something went wrong.";
                }
            }
        }
        else if(!empty($postData['id']) && $postData['type'] == 'EMAIL')
        {
            $blastEmail = Group_email_blast::where('blast_email_id',$postData['id'])->delete();
            if($blastEmail)
            {
                $emailDelete = EmailMessage::where('id',$postData['id'])->delete();
                if($emailDelete)
                {
                    $data["status"] = true;
                    $data["message"] = "Data has been deleted.";
                }
                else
                {
                    $data["status"] = false;
                    $data["message"] = "Something went wrong.";
                }
            }
        }
        return JsonReturn::success($data);
    }
    public function sendSMS()
    {
        $api_key = 'KEY0170163F410B3D73F3E24ACA4ED6A1AF_Vv9GsxS3SC7HWxzwhsQo1z';
        $response = TelnyxSms::sendTelnyxSms('911111111111','12897973720','How are you my friend ?');        
        /*Smslog::create([
            'sms_id' => $response->data->id,
        ]);*/
        // $error_message = $smsResponse->ErrorMessage;
        echo "<pre>";
        print_r($response);
        exit();
        $sms_log = Sms_log::create([
            'user_id'          => Auth::id(),
            'client_id'        => '55',
            'sms_id'           => "test",
            'send_from'        => '12897973720',
            'send_to'          => '917487855889',
            'client_name'      => 'Test',
            'message'          => 'How are you my friend ?',
            'direction'        => 'outbox',
            'sms_status'       => 'Send',
            'error_message'    => "",
            'module_type'      => '1',
            'module_type_text' => 'SMS Blast Messages',
            'sms_response'     => json_encode($response),
            'created_at'       => date("Y-m-d H:i:s")
        ]);
        echo "<pre>";
        print_r($response);
        exit();
    }
    
    public function SendEmail()
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
        
        $locationData = Location::where('user_id', $AdminId)->get()->toArray();
        $emailMessageData = EmailMessage::where('id',3)->first();
        $FROM_EMAIL     = env("MAIL_FROM_ADDRESS", "info@ikotel.ca");
        $FROM_NAME      = 'Special Offer';
        $TO_EMAIL       = "tjcloudtest@gmail.com";
        $CC_EMAIL       = '';//info@ikotel.ca
        $SUBJECT        = 'Test Subject';
        $MESSAGE        = 'Hi  This is test email!';
        $LOCATION       = $locationData;
        $MessageData    = $emailMessageData;
        $TYPE           = 'special_offer';
        $SendMail = Mail::to($TO_EMAIL)->send(new EmailBlast($FROM_EMAIL,$FROM_NAME,$SUBJECT,$MESSAGE,$LOCATION,$MessageData,$TYPE));//->cc([$CC_EMAIL])
        echo "<pre>";
        print_r($SendMail);
        // print_r($getEmailBlastData);
        print_r($FROM_EMAIL);
        echo "<br>";
        print_r($FROM_NAME);
        echo "<br>";
        print_r($TO_EMAIL);
        echo "<br>";
        print_r($CC_EMAIL);
        echo "<br>";
        print_r($SUBJECT);
        echo "<br>";
        print_r($MESSAGE);
        echo "<br>";
        echo "<br>";
        exit();
    }
    public function getFilteredClients(Request $request)
    {
        $post_data = $request->all();
        /*echo "<pre>";
        print_r($post_data);
        exit;*/
        
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
        
        $timeValue = $post_data['value'];
        $minSalesCount = !empty($post_data['min_sales_count']) ? $post_data['min_sales_count'] : 1;
        $curDate = date('Y-m-d');
        $html = "";
        $date = "";
        if($post_data['time'] == 'Days')
        {
            $date = \Carbon\Carbon::today()->subDays($timeValue);
        }
        else if($post_data['time'] == 'Weeks')
        {
            $days = $timeValue * 7;
            $date = \Carbon\Carbon::today()->subDays($days);
        }
        else if($post_data['time'] == "Months")
        {
            $date = \Carbon\Carbon::today()->subMonth($timeValue)->format('Y-m-d');
        }
        if($post_data['type'] == 'last_client')
        {
            $allClients = Clients::where('user_id', $AdminId)->whereDate('created_at','>=', $date)->whereDate('created_at','<=', $curDate)->get();
            if(!empty($allClients))
            {
                foreach ($allClients as $key => $value) 
                {
                    $html .= '<label class="checkbox">';
                    $html .= '<input type="checkbox" checked="checked" name="clients[]" class="clients" value="'.$value->id.'">';
                    $html .= '<span></span><h3><b> <p data-letters="'.substr($value->firstname,0,1).'">'.$value->firstname.' '.$value->lastname.'</p></b></h3> &nbsp; <p style="margin-bottom: 1rem;">'.$value->mobileno.'</p></label>';
                }
            }
        }
        else if($post_data['type'] == 'first_sale')
        {
            $allClients = DB::table('clients')
                    ->join('invoice','invoice.client_id','=','clients.id')
                    ->select('clients.*','invoice.id as invoice_id','invoice.client_id')
                    ->whereDate('invoice.payment_date','>=', $date)
                    ->whereDate('invoice.payment_date','<=', $curDate)
                    ->where(['clients.user_id'=> $AdminId,'invoice_status'=>1])
                    ->groupBy('clients.id')
                    ->get();
            if(!empty($allClients))
            {
                foreach ($allClients as $key => $value) 
                {
                    $checkDateBeforeSale = Invoice::where('client_id',$value->id)->whereDate('payment_date','<=', $date)->get()->toArray();
                    if(empty($checkDateBeforeSale))
                    {
                        $html .= '<label class="checkbox">';
                        $html .= '<input type="checkbox" checked="checked" name="clients[]" class="clients" value="'.$value->id.'">';
                        $html .= '<span></span><h3><b> <p data-letters="'.substr($value->firstname,0,1).'">'.$value->firstname.' '.$value->lastname.'</p></b></h3> &nbsp; <p style="margin-bottom: 1rem;">'.$value->mobileno.'</p></label>';
                    }
                }
            }
        }
        else if($post_data['type'] == 'loyal_client')
        {
            /*$totalSale = array();

            $allClients = Clients::get();
            foreach ($allClients as $aClientKey => $aClient) {
                $getCountOfInvoice = Invoice::select('id','client_id')->where('client_id',$aClient->id)->get();
                if(!$getCountOfInvoice->isEmpty()){
                    $totalSale[$aClientKey]['client_id'] = $aClient->id;
                    $totalSale[$aClientKey]['total_sale'] = $getCountOfInvoice->count();
                }
            }
            echo "<pre>";
            print_r($totalSale);
            exit;*/

            if($post_data['who_spent_type'] == 2) {
                $atLeastSpent  = !empty($post_data['at_least_spent']) ? $post_data['at_least_spent'] : 0;
            } else {
                $atLeastSpent = 0;
            }

            $countCustomerInvoices = DB::select('select COUNT(id) as total_sales, client_id from invoice where user_id = '.$AdminId.' AND user_id != 0 AND '.'payment_date >= "'.$date.'" AND payment_date <= "'.$curDate.'" AND client_id != 0 AND invoice_status = 1  GROUP BY client_id HAVING SUM(inovice_final_total) >= '.$atLeastSpent);

            if(!empty($countCustomerInvoices))
            {
                foreach ($countCustomerInvoices as $key => $value) 
                {
                    if(!empty($countCustomerInvoices))
                    {
                        if($value->total_sales >= 3)
                        {
                            $client = Clients::where('id', $value->client_id)->first();

                            $html .= '<label class="checkbox">';
                            $html .= '<input type="checkbox" checked="checked" name="clients[]" class="clients" value="'.$client->id.'">';
                            $html .= '<span></span><h3><b> <p data-letters="'.substr($client->firstname,0,1).'">'.$client->firstname.' '.$client->lastname.'</p></b></h3> &nbsp; <p style="margin-bottom: 1rem;">'.$client->mobileno.'</p></label>';
                        }
                    }
                }
            }
        }
        else if($post_data['type'] == 'at_least_3_sale')
        {
            $lastTwoMonth = \Carbon\Carbon::today()->subMonth(2)->format('Y-m-d');
            $coutClientSales = array();
            $clientsArr = array();
            $allInvoice = Invoice::where('user_id',$AdminId)->whereDate('payment_date','>=', $date)->whereDate('payment_date','<=', $curDate)->where('invoice_status', 1)->get();

            // 2021-06-02 >= 2021-02-10 and 

            $sinceLastSale = !empty($post_data['since_last_sale']) ? \Carbon\Carbon::today()->subMonth($post_data['since_last_sale'])->format('Y-m-d') : '';
            foreach ($allInvoice as $key => $value) 
            {
                if(!in_array($value->client_id, $clientsArr))
                {
                    array_push($clientsArr, $value->client_id);
                    $coutClientSales[$value->client_id] = 1;
                }
                else
                {
                    $coutClientSales[$value->client_id] = $coutClientSales[$value->client_id] + 1;
                }
            }
            if(!empty($coutClientSales))
            {
                foreach ($coutClientSales as $key => $value) 
                {
                    $sinceLastSaleCount = Invoice::where('user_id',$AdminId)->where('client_id', $key)->whereDate('payment_date','>', $sinceLastSale)->where('invoice_status', 1)->count();

                    if(($value >= $minSalesCount) && ($sinceLastSaleCount == 0))
                    {
                        $checkDateBeforeSale = Clients::where('id',$key)->get();
                        if(!empty($checkDateBeforeSale))
                        {
                            foreach ($checkDateBeforeSale as $key => $value) 
                            {
                                $html .= '<label class="checkbox">';
                                $html .= '<input type="checkbox" checked="checked" name="clients[]" class="clients" value="'.$value->id.'">';
                                $html .= '<span></span><h3><b> <p data-letters="'.substr($value->firstname,0,1).'">'.$value->firstname.' '.$value->lastname.'</p></b></h3> &nbsp; <p style="margin-bottom: 1rem;">'.$value->mobileno.'</p></label>'; 
                            }
                        }
                    }
                }
            }
        }
        // exit();
        $data['status'] = true;
        $data['html'] = $html;
        return JsonReturn::success($data);
    }

    public function getAllClients()
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

        $allClients = Clients::where('user_id', $AdminId)->get();

        $html = '';
        if($allClients->isNotEmpty()) {
            foreach($allClients as $key => $value) {

                $html .= '<label class="checkbox">';
                $html .= '<input type="checkbox" checked="checked" name="clients[]" class="clients" value="'.$value->id.'">';
                $html .= '<span></span><h3><b> <p data-letters="'.substr($value->firstname,0,1).'">'.$value->firstname.' '.$value->lastname.'</p></b></h3> &nbsp; <p style="margin-bottom: 1rem;">'.$value->mobileno.'</p></label>';                
            }
        }

        $data['status'] = true;
        $data['html'] = $html;
        return JsonReturn::success($data);
    }
    
    public function campaign_overview($id)
    {
        if(!empty($id))
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
            
            $id = Crypt::decryptString($id);
            $smartCampaign = Smart_campaign::where('id',$id)->first();
            if(!empty($smartCampaign))
            {   
                $totalSentEmail = EmailLog::whereRaw("(`campaign_id` = ".$id." and `user_id` = ".$AdminId." and `module_type` = 1) and (`email_status` = 'sent' or `email_status` = 'opened' or `email_status` = 'clicked')")->count();
                $totalEmail = EmailLog::where(['campaign_id'=>$id,'user_id' => $AdminId,'module_type'=>1])->count();
                $totalFaliedEmail = EmailLog::where(['campaign_id'=>$id,'user_id' => $AdminId,'module_type'=>1])->where('email_status','dropped')->orWhere('email_status','bounced')->orWhere('email_status','invalid')->count();
                $lastActiveDate = History::select('created_at')->where(['module_type'=>1,'action'=>'Active'])->orderBy('created_at','desc')->first();
                $createdBy = User::select(DB::raw("CONCAT(first_name,' ',last_name) AS created_by"))->where('id',Auth::id())->first();
                $serviceCategory = ServiceCategory::where(['user_id' => $AdminId,'is_deleted'=>0])->pluck('id')->toArray();
                $allServices = Services::where(['is_deleted'=>0])->whereIn('service_category',$serviceCategory)->get()->toArray();
                $emailCounter['sent_email'] = $totalSentEmail;
                $emailCounter['total_email'] = $totalEmail;
                $emailCounter['total_failed'] = $totalFaliedEmail;
                $emailCounter['percentage'] = ($totalSentEmail != 0 && $totalEmail != 0) ? ($totalSentEmail * 100 / $totalEmail) : 0;

                return view('marketing.campaign_overview',compact('smartCampaign','emailCounter','lastActiveDate','createdBy','allServices'));
            }
            else
            {
                Session::flash('error', 'Data not found.');
                return redirect(route('smart_campaigns'));       
            }
        }
        else
        {
            return redirect(route('smart_campaigns'));
        }
    }
    
    function message_overview($type,$id)
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
        
        $locationData = Location::where('user_id',$AdminId)->get()->toArray();
        
        if($type == 'SMS'){
            $id = Crypt::decryptString($id);
            
            $getOverview = Sms_message::where('id',$id)->with('getGroupSmsBlast')->with('getCreator')->first();
            
            $paymentResponse = Payment_response::where('email_sms_id',$id)->where('type','SMS')->get()->first()->toArray();
            
            $TotalBlast = 0;
            if(!empty($getOverview)){
                $TotalBlast     = count($getOverview['get_group_sms_blast']);
                $TotalDelivered = 0;
                $TotalClicked   = 0;
                
                foreach($getOverview['get_group_sms_blast'] as $GroupSmsBlastData){
                    if($GroupSmsBlastData['is_delivered'] == 1){
                        $TotalDelivered++;
                    }
                    if($GroupSmsBlastData['is_clicked'] == 1){
                        $TotalClicked++;
                    }
                }
                
                $TotalDeliveredPer = 0;
                if($TotalBlast != 0 && $TotalDelivered != 0){
                    $TotalDeliveredPer = round(($TotalDelivered * 100) / $TotalBlast);
                }
                
                $TotalClickedPer = 0;
                if($TotalBlast != 0 && $TotalClicked != 0){
                    $TotalClickedPer = round(($TotalClicked * 100) / $TotalBlast);
                }
            }
            
            return view('marketing.blastCampaignsSMSDetailsPage',compact('getOverview','TotalBlast','TotalDelivered','TotalClicked','TotalDeliveredPer','TotalClickedPer','paymentResponse','locationData'));
        }else{
            $id = Crypt::decryptString($id);
            
            $getOverview     = EmailMessage::where('id',$id)->with('getGroupEmailBlast','getCreator')->first()->toArray();
            $paymentResponse = Payment_response::where('email_sms_id',$id)->where('type','Email')->get()->first()->toArray();
            
            $TotalBlast = 0;
            if(!empty($getOverview)){
                $TotalBlast     = count($getOverview['get_group_email_blast']);
                $TotalDelivered = 0;
                $TotalOpened    = 0;
                $TotalClicked   = 0;
                
                foreach($getOverview['get_group_email_blast'] as $GroupEmailBlastData){
                    if($GroupEmailBlastData['is_delivered'] == 1){
                        $TotalDelivered++;
                    }
                    if($GroupEmailBlastData['is_opened'] == 1){
                        $TotalOpened++;
                    }
                    if($GroupEmailBlastData['is_clicked'] == 1){
                        $TotalClicked++;
                    }
                }
                
                $TotalDeliveredPer = 0;
                if($TotalBlast != 0 && $TotalDelivered != 0){
                    $TotalDeliveredPer = round(($TotalDelivered * 100) / $TotalBlast);
                }
                
                $TotalOpenedPer = 0;
                if($TotalBlast != 0 && $TotalOpened != 0){
                    $TotalOpenedPer = round(($TotalOpened * 100) / $TotalBlast);
                }
                
                $TotalClickedPer = 0;
                if($TotalBlast != 0 && $TotalClicked != 0){
                    $TotalClickedPer = round(($TotalClicked * 100) / $TotalBlast);
                }
            }
            
            return view('marketing.blastCampaignsDetailsPage',compact('getOverview','TotalBlast','TotalDelivered','TotalOpened','TotalClicked','TotalDeliveredPer','TotalOpenedPer','TotalClickedPer','paymentResponse','locationData'));
        }
    }
    
    // created by dj
    public function addCustomerPaymentCard(Request $request){
        if ($request->ajax()) 
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
            
            $paymentCardType  = ($request->paymentCardType) ? $request->paymentCardType : 'newCard';
            $card_holder_name = ($request->card_holder_name) ? $request->card_holder_name : '';
            $card_number      = ($request->card_number) ? $request->card_number : '';
            $expiry_date      = ($request->expiry_date) ? $request->expiry_date : '';
            $cvv              = ($request->cvv) ? $request->cvv : '';
            
            $getUserDetails = User::getUserbyID($AdminId);  
            
            $StripeCustomerCode = $getUserDetails->stripe_customer_code;
            
            if($StripeCustomerCode == '')   {
                $clientData['name']  = $getUserDetails->first_name.' '.$getUserDetails->last_name;
                $clientData['email'] = $getUserDetails->email;
                $createCustomerResponse = $this->addCustomerToStripe($clientData);
                
                if(!empty($createCustomerResponse) && $createCustomerResponse['status'] == true){
                    
                    $updateUserObj = User::find($AdminId);
                    $updateUserObj->stripe_customer_code = $createCustomerResponse['customer_code'];
                    $updateUserObj->updated_at           = date("Y-m-d H:i:s");
                    $updateUserObj->save();
                    
                    $StripeCustomerCode = $createCustomerResponse['customer_code'];
                } else {
                    $data["status"]  = false;
                    $data["message"] = $createCustomerResponse['message'];
                    return JsonReturn::success($data);
                }
            }   
            
            $fetchUserDetails = User::getUserbyID($AdminId);    
            
            if($paymentCardType == 'newCard') {
                
                if($card_holder_name == '' && $card_number == '' && $expiry_date == '' && $cvv == ''){
                    $data["status"]  = false;
                    $data["message"] = "Credit card details are missing!";
                    return JsonReturn::success($data);
                }   
                
                $Expiry = explode("/",$expiry_date);
                
                $cardData['number']    = $card_number;
                $cardData['exp_month'] = $Expiry[0];
                $cardData['exp_year']  = $Expiry[1];
                $cardData['cvc']       = $cvv;
                $cardData['name']      = $card_holder_name;
                
                $createTokenResponse = $this->createStripeCardToken($cardData);
                
                if(!empty($createTokenResponse) && $createTokenResponse['status'] == true) {
                    $card_token = $createTokenResponse['card_token'];
                    
                    if($StripeCustomerCode != '') {
                        // add token to customer
                        $createCardResponse = $this->createStripeCard($StripeCustomerCode,$card_token); 
                        
                        if(!empty($createCardResponse) && $createCardResponse['status'] == true){
                            
                            $card_id = $createCardResponse['card_id'];
                            
                            $customerData['customer_id'] = $StripeCustomerCode;
                            $customerData['card_id']     = $card_id;
                            
                            $retriveCardDetails = $this->getStripeCardDetails($customerData);
                            
                            $response = array();
                            if(!empty($retriveCardDetails) && $retriveCardDetails['status'] == true){
                                $successResponse = $retriveCardDetails['full_response'];
                                
                                $response['id']    = $successResponse->id;
                                $response['brand'] = $successResponse->brand;
                                $response['last4'] = $successResponse->last4;
                                $response['name']  = $successResponse->name;
                                
                                $data["status"]  = true;
                                $data["message"] = "Card details set succesfully.";
                                $data["data"]    = $response;
                            } else {
                                $data["status"]  = false;
                                $data["message"] = $retriveCardDetails['message'];
                                $data["data"]    = $response;
                            }
                            
                            return JsonReturn::success($data);
                            
                        } else {
                            $data["status"]  = false;
                            $data["message"] = $createCardResponse['message'];
                            return JsonReturn::success($data);
                        }
                    } else {
                        $data["status"]  = false;
                        $data["message"] = 'Customer code is missing!';
                        return JsonReturn::success($data);
                    }
                } else {
                    $data["status"]  = false;
                    $data["message"] = $createTokenResponse['message'];
                    return JsonReturn::success($data);
                }
            } else if(!empty($fetchUserDetails) && $fetchUserDetails->stripe_customer_code != '') {
                
                $customerData['customer_id'] = $fetchUserDetails->stripe_customer_code;
                $customerData['card_id']     = $paymentCardType;
                
                $retriveCardDetails = $this->getStripeCardDetails($customerData);
                
                $response = array();
                if(!empty($retriveCardDetails) && $retriveCardDetails['status'] == true){
                    $successResponse = $retriveCardDetails['full_response'];
                    
                    $response['id']    = $successResponse->id;
                    $response['brand'] = $successResponse->brand;
                    $response['last4'] = $successResponse->last4;
                    $response['name']  = $successResponse->name;
                    
                    $data["status"]  = true;
                    $data["message"] = "Card details set succesfully.";
                    $data["data"]    = $response;
                } else {
                    $data["status"]  = false;
                    $data["message"] = $retriveCardDetails['message'];
                    $data["data"]    = $response;
                }
                
                return JsonReturn::success($data);
            }
        }
    }
    
    public function cloneEmailBlastMessage(Request $request)
    {
        if ($request->ajax()) 
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
            
            $EmailMessageId  = ($request->id) ? $request->id : '';
            $getEmailMessage = EmailMessage::where('id',$EmailMessageId)->with('getGroupEmailBlast')->get()->first()->toArray();
            
            if(!empty($getEmailMessage)){
                $addEmailStatus = EmailMessage::create([
                    'user_id'              => $AdminId,    
                    'message_name'         => $getEmailMessage['message_name'],
                    'email_subject'        => $getEmailMessage['email_subject'],
                    'reply_email'          => $getEmailMessage['reply_email'],
                    'title'                => $getEmailMessage['title'],
                    'message'              => $getEmailMessage['message'],
                    'is_button'            => $getEmailMessage['is_button'],
                    'button_text'          => $getEmailMessage['button_text'],
                    'button_link'          => $getEmailMessage['button_link'],
                    'social_media_enable'  => $getEmailMessage['social_media_enable'],
                    'facebook_link'        => $getEmailMessage['facebook_link'],
                    'instagram_link'       => $getEmailMessage['instagram_link'],
                    'website'              => $getEmailMessage['website'],
                    'message_price'        => $getEmailMessage['message_price'],
                    'total_payable_price'  => $getEmailMessage['total_payable_price'],
                    'is_sended'            => 0,
                    'message_type_int'     => $getEmailMessage['message_type_int'],
                    'message_type_text'    => $getEmailMessage['message_type_text'],
                    'is_image'             => $getEmailMessage['is_image'],
                    'image_path'           => $getEmailMessage['image_path'],
                    'client_type_int'      => $getEmailMessage['client_type_int'],
                    'client_type'          => $getEmailMessage['client_type'],
                    'created_by'           => $UserId,
                    'created_at'           => date("Y-m-d H:i:s"),
                    'updated_at'           => date("Y-m-d H:i:s"),
                ]);
                
                $lastId = $addEmailStatus->id;
                
                if(!empty($getEmailMessage['get_group_email_blast'])){
                    foreach($getEmailMessage['get_group_email_blast'] as $get_group_email_blast){
                        $addGroupEmailBlast = Group_email_blast::create([
                            'user_id'       => $AdminId,  
                            'blast_email_id'=> $lastId,
                            'client_id'     => $get_group_email_blast['client_id'],
                            'created_at'    => date("Y-m-d H:i:s"),
                        ]);
                    }
                }
                
                if($lastId){
                    $data["status"]   = true;
                    $data["message"]  = 'Group email message blast cloned succesfully';
                    $data["redirect"] = route('edit_email_message',['id' => $lastId]);
                    return JsonReturn::success($data);
                } else {
                    $data["status"]  = false;
                    $data["message"] = $createCustomerResponse['message'];
                    return JsonReturn::success($data);
                }
            }
        }
    }
    
    public function cloneSmsBlastMessage(Request $request)
    {
        if ($request->ajax()) 
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
            
            $SmsMessageId  = ($request->id) ? $request->id : '';
            $getSmsMessage = Sms_message::where('id',$SmsMessageId)->with('getGroupSmsBlast')->get()->first()->toArray();
            
            if(!empty($getSmsMessage)) {
                
                $addSmsStatus = Sms_message::create([
                    'user_id'             => $AdminId,    
                    'message_name'        => $getSmsMessage['message_name'],
                    'message_description' => $getSmsMessage['message_description'],
                    'is_link'             => $getSmsMessage['is_link'],
                    'btn_url'             => $getSmsMessage['btn_url'],
                    'title'               => $getSmsMessage['title'],
                    'discount_type'       => $getSmsMessage['discount_type'],
                    'discount_value'      => $getSmsMessage['discount_value'],
                    'appointment_limit'   => $getSmsMessage['appointment_limit'],
                    'valid_for'           => $getSmsMessage['valid_for'],
                    'sms_type_text'       => $getSmsMessage['sms_type_text'],
                    'sms_type_int'        => $getSmsMessage['sms_type_int'],
                    'services'            => $getSmsMessage['services'],
                    'payment_status'      => 0,
                    'is_sms_sended'       => 0,
                    'client_type'         => $getSmsMessage['client_type'],
                    'group_type'          => $getSmsMessage['group_type'],
                    'created_by'          => $UserId,
                    'created_at'          => date("Y-m-d H:i:s"),
                    'updated_at'          => date("Y-m-d H:i:s"),
                ]);
                
                $lastId = $addSmsStatus->id;
                
                if(!empty($getSmsMessage['get_group_sms_blast'])){
                    foreach($getSmsMessage['get_group_sms_blast'] as $get_group_sms_blast){
                        $Group_sms_blast = Group_sms_blast::create([
                            'user_id'       => $AdminId,  
                            'blast_sms_id'  => $lastId,
                            'client_id'     => $get_group_sms_blast['client_id'],
                            'created_at'    => date("Y-m-d H:i:s"),
                        ]);
                    }
                }
                
                if($lastId){
                    $data["status"]   = true;
                    $data["message"]  = 'Group sms message blast cloned succesfully';
                    $data["redirect"] = route('edit_sms_message',['id' => $lastId]);
                    return JsonReturn::success($data);
                } else {
                    $data["status"]  = false;
                    $data["message"] = $createCustomerResponse['message'];
                    return JsonReturn::success($data);
                }
            }
        }
    }
    
    public function addCustomerToStripe($clientData = null) 
    {   
        $returnResponse = array();
        $stripeResponse = array();
        $errorResponse  = '';
        
        $setting = Setting::first();
        Stripe\Stripe::setApiKey($setting->stripe_api_key);
        
        try {
            $stripeResponse = Stripe\Customer::create($clientData);
        } catch(Stripe\Exception\CardException $e) {
            $errorResponse = $e->getError()->message;
        } catch (Stripe\Exception\RateLimitException $e) {
            $errorResponse = $e->getError()->message;
        } catch (Stripe\Exception\InvalidRequestException $e) {
            $errorResponse = $e->getError()->message;
        } catch (Stripe\Exception\AuthenticationException $e) {                    
            $errorResponse = $e->getError()->message;
        } catch (Stripe\Exception\ApiConnectionException $e) {
            $errorResponse = $e->getError()->message;
        } catch (Stripe\Exception\ApiErrorException $e) {
            $errorResponse = $e->getError()->message;    
        } catch (Exception $e) {
            $errorResponse = $e->getError()->message;
        }
        
        if(!empty($stripeResponse) && $stripeResponse->id != ''){
            $returnResponse['status']        = true;
            $returnResponse['customer_code'] = $stripeResponse->id;
            $returnResponse['full_response'] = $stripeResponse;
            $returnResponse['message']       = 'success';
        } else if($errorResponse != ''){
            $returnResponse['status']        = false;
            $returnResponse['customer_code'] = null;
            $returnResponse['full_response'] = $stripeResponse;
            $returnResponse['message']       = $errorResponse;
        }
        return $returnResponse;
    } 
    
    public function createStripeCardToken($cardData = null) 
    {   
        $returnResponse = array();
        $stripeResponse = array();
        $errorResponse  = '';
        
        $setting = Setting::first();
        Stripe\Stripe::setApiKey($setting->stripe_api_key);
        
        $stripeCard = array(
            'card' => $cardData
        );
        
        try {
            $stripeResponse = Stripe\Token::create($stripeCard);
        } catch(Stripe\Exception\CardException $e) {
            $errorResponse = $e->getError()->message;
        } catch (Stripe\Exception\RateLimitException $e) {
            $errorResponse = $e->getError()->message;
        } catch (Stripe\Exception\InvalidRequestException $e) {
            $errorResponse = $e->getError()->message;
        } catch (Stripe\Exception\AuthenticationException $e) {                    
            $errorResponse = $e->getError()->message;
        } catch (Stripe\Exception\ApiConnectionException $e) {
            $errorResponse = $e->getError()->message;
        } catch (Stripe\Exception\ApiErrorException $e) {
            $errorResponse = $e->getError()->message;    
        } catch (Exception $e) {
            $errorResponse = $e->getError()->message;
        }
        
        if(!empty($stripeResponse) && $stripeResponse->id != ''){
            $returnResponse['status']        = true;
            $returnResponse['card_token']    = $stripeResponse->id;
            $returnResponse['full_response'] = $stripeResponse;
            $returnResponse['message']       = 'success';
        } else if($errorResponse != ''){
            $returnResponse['status']        = false;
            $returnResponse['card_token']    = null;
            $returnResponse['full_response'] = $stripeResponse;
            $returnResponse['message']       = $errorResponse;
        }
        return $returnResponse;
    } 
    
    public function createStripeCard($customer_code = null,$card_token = null) 
    {   
        $returnResponse = array();
        $stripeResponse = array();
        $errorResponse  = '';
        
        $setting = Setting::first();
        Stripe\Stripe::setApiKey($setting->stripe_api_key);
        
        $createCardData = array(
            'customer_id' => $customer_code,
            'source'      => $card_token
        );
        
        try {
            $cu = Stripe\Customer::retrieve($createCardData['customer_id']);
            if($cu->deleted == 1){
                $stripeResponse = array();
            } else{ 
                unset($createCardData['customer_id']);
                $stripeResponse = $cu->sources->create($createCardData);    
            }
            
        } catch(Stripe\Exception\CardException $e) {
            $errorResponse = $e->getError()->message;
        } catch (Stripe\Exception\RateLimitException $e) {
            $errorResponse = $e->getError()->message;
        } catch (Stripe\Exception\InvalidRequestException $e) {
            $errorResponse = $e->getError()->message;
        } catch (Stripe\Exception\AuthenticationException $e) {                    
            $errorResponse = $e->getError()->message;
        } catch (Stripe\Exception\ApiConnectionException $e) {
            $errorResponse = $e->getError()->message;
        } catch (Stripe\Exception\ApiErrorException $e) {
            $errorResponse = $e->getError()->message;    
        } catch (Exception $e) {
            $errorResponse = $e->getError()->message;
        }
        
        if(!empty($stripeResponse) && $stripeResponse->id != ''){
            $returnResponse['status']        = true;
            $returnResponse['card_id']       = $stripeResponse->id;
            $returnResponse['full_response'] = $stripeResponse;
            $returnResponse['message']       = 'success';
        } else if($errorResponse != ''){
            $returnResponse['status']        = false;
            $returnResponse['card_id'] = null;
            $returnResponse['full_response'] = $stripeResponse;
            $returnResponse['message']       = $errorResponse;
        }
        return $returnResponse;
    } 
    
    public function getStripeCardList($customer_code = null) 
    {
        $returnResponse = array();
        $stripeResponse = array();
        $errorResponse  = '';
        
        $setting = Setting::first();
        Stripe\Stripe::setApiKey($setting->stripe_api_key);
        
        $customerData = array(
            'customer_id' => $customer_code,
            'options'     => array('object' => 'card')
        );
        
        try {
            $cu = Stripe\Customer::retrieve($customerData['customer_id']);
            $cardData = $cu->sources->all($customerData['options']);
            
            if(!empty($cardData) && isset($cardData->data)){
                foreach($cardData->data as $cardData){
                    $tempResponse['id']        = $cardData->id;
                    $tempResponse['exp_month'] = $cardData->exp_month;
                    $tempResponse['exp_year']  = $cardData->exp_year;
                    $tempResponse['brand']     = $cardData->brand;
                    $tempResponse['last4']     = $cardData->last4;
                    $tempResponse['name']      = $cardData->name;
                    array_push($stripeResponse,$tempResponse);
                }
            }
            
        } catch(Stripe\Exception\CardException $e) {
            $errorResponse = $e->getError()->message;
        } catch (Stripe\Exception\RateLimitException $e) {
            $errorResponse = $e->getError()->message;
        } catch (Stripe\Exception\InvalidRequestException $e) {
            $errorResponse = $e->getError()->message;
        } catch (Stripe\Exception\AuthenticationException $e) {                    
            $errorResponse = $e->getError()->message;
        } catch (Stripe\Exception\ApiConnectionException $e) {
            $errorResponse = $e->getError()->message;
        } catch (Stripe\Exception\ApiErrorException $e) {
            $errorResponse = $e->getError()->message;    
        } catch (Exception $e) {
            $errorResponse = $e->getError()->message;
        }
        
        if(!empty($stripeResponse)){
            $returnResponse['status']        = true;
            $returnResponse['card_list']     = $stripeResponse;
            $returnResponse['full_response'] = $stripeResponse;
            $returnResponse['message']       = 'success';
        } else if($errorResponse != ''){
            $returnResponse['status']        = false;
            $returnResponse['card_list'] = null;
            $returnResponse['full_response'] = $stripeResponse;
            $returnResponse['message']       = $errorResponse;
        }
        return $returnResponse;
    }
    
    public function getStripeCardDetails($customerData = null) 
    {
        $returnResponse = array();
        $stripeResponse = array();
        $errorResponse  = '';
        
        $setting = Setting::first();
        Stripe\Stripe::setApiKey($setting->stripe_api_key);
        
        try {
            $cu = Stripe\Customer::retrieve($customerData['customer_id']);
                    
            if($cu->deleted == 1){
                $stripeResponse = array();
            } else{ 
                $stripeResponse = $cu->sources->retrieve($customerData['card_id']);
            }
            
        } catch(Stripe\Exception\CardException $e) {
            $errorResponse = $e->getError()->message;
        } catch (Stripe\Exception\RateLimitException $e) {
            $errorResponse = $e->getError()->message;
        } catch (Stripe\Exception\InvalidRequestException $e) {
            $errorResponse = $e->getError()->message;
        } catch (Stripe\Exception\AuthenticationException $e) {                    
            $errorResponse = $e->getError()->message;
        } catch (Stripe\Exception\ApiConnectionException $e) {
            $errorResponse = $e->getError()->message;
        } catch (Stripe\Exception\ApiErrorException $e) {
            $errorResponse = $e->getError()->message;    
        } catch (Exception $e) {
            $errorResponse = $e->getError()->message;
        }
        
        if(!empty($stripeResponse) && $stripeResponse->id != ''){
            $returnResponse['status']        = true;
            $returnResponse['card_id']       = $stripeResponse->id;
            $returnResponse['full_response'] = $stripeResponse;
            $returnResponse['message']       = 'success';
        } else if($errorResponse != ''){
            $returnResponse['status']        = false;
            $returnResponse['card_id']       = null;
            $returnResponse['full_response'] = $stripeResponse;
            $returnResponse['message']       = $errorResponse;
        }
        return $returnResponse;
    }
    
    
    public function chargeStripeCustomer($chargeData = null){
        $returnResponse = array();
        $stripeResponse = array();
        $errorResponse  = '';
        
        $setting = Setting::first();
        Stripe\Stripe::setApiKey($setting->stripe_api_key);
        
        try {
            $stripeResponse = Stripe\Charge::create($chargeData);
        } catch(Stripe\Exception\CardException $e) {
            $errorResponse = $e->getError()->message;
        } catch (Stripe\Exception\RateLimitException $e) {
            $errorResponse = $e->getError()->message;
        } catch (Stripe\Exception\InvalidRequestException $e) {
            $errorResponse = $e->getError()->message;
        } catch (Stripe\Exception\AuthenticationException $e) {                    
            $errorResponse = $e->getError()->message;
        } catch (Stripe\Exception\ApiConnectionException $e) {
            $errorResponse = $e->getError()->message;
        } catch (Stripe\Exception\ApiErrorException $e) {
            $errorResponse = $e->getError()->message;    
        } catch (Exception $e) {
            $errorResponse = $e->getError()->message;
        }
        
        if(!empty($stripeResponse) && $stripeResponse->paid != '' && $stripeResponse->paid == 1){
            $returnResponse['status']         = true;
            $returnResponse['charge_id']      = $stripeResponse->id;
            $returnResponse['transaction_id'] = $stripeResponse->balance_transaction;
            $returnResponse['full_response']  = $stripeResponse;
            $returnResponse['message']        = 'success';
        } else if($errorResponse != ''){
            $returnResponse['status']         = false;
            $returnResponse['charge_id']      = null;
            $returnResponse['transaction_id'] = null;
            $returnResponse['full_response']  = $stripeResponse;
            $returnResponse['message']        = $errorResponse;
        }
        return $returnResponse;
    }
    
    function resetCampaign(Request $request){
        if($request->ajax()){
            $defaultCampaignData = Default_campaign::where('id',$request->default_campaign_id)->first();
            $resetCampaign = Smart_campaign::find($request->campaign_id);
            $resetCampaign->email_subject = $defaultCampaignData->email_subject;
            $resetCampaign->headline_text = $defaultCampaignData->headline_text;
            $resetCampaign->body_text = $defaultCampaignData->body_text;
            $resetCampaign->discount_value = $defaultCampaignData->discount_value;
            $resetCampaign->discount_type = $defaultCampaignData->discount_type;
            $resetCampaign->day_before_birthday = $defaultCampaignData->day_before_birthday;
            $resetCampaign->appoinment_limit = $defaultCampaignData->appoinment_limit;
            $resetCampaign->valid_for = $defaultCampaignData->valid_for;
            $resetCampaign->image_path = $defaultCampaignData->image_path;
            if($resetCampaign->save()){
                $data["status"]   = true;
                $data["message"]  = 'Campaign reset succesfully.';
            }else{
                $data["status"]   = false;
                $data["message"]  = 'Something went wrong.';
            }
        }
        return JsonReturn::success($data);
    }

    function unique_code($digits)
    {
        $this->autoRender = false;
        srand((double)microtime() * 10000000);
        $input = array("A", "B", "C", "D", "E", "F", "G", "H", "J", "K", "L", "M", "N", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", "1", "2", "3", "4", "5", "6", "7", "8", "9");
        //$input = array("0","1","2","3","4","5","6","7","8","9");
        $random_generator = "";// Initialize the string to store random numbers
        for ($i = 1; $i < $digits + 1; $i++) {
            // Loop the number of times of required digits

            if (rand(1, 2) == 1) {// to decide the digit should be numeric or alphabet
                $rand_index = array_rand($input);
                $random_generator .= $input[$rand_index]; // One char is added
            } else {
                $random_generator .= rand(1, 7); // one number is added
            }
        } // end of for loop
        return $random_generator;
    }
    public function QuickUpdate(){
        return view('emailTemplates.campaign_mail');
    }
}
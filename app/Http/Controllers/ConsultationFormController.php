<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\JsonReturn;
use App\Models\User;
use App\Models\Staff;
use App\Models\Clients;
use App\Models\ConsForm;
use App\Models\conFormClientDetails;
use App\Models\conFormCustomSection;
use App\Models\ClientConsultationForm;
use App\Models\ClientConsultationFormField;
use Carbon\Carbon;
use App\Models\Country;
use App\Models\Location;
use App\Models\EmailLog;
use App\Models\Services;
use App\Models\ServicesPrice;
use App\Models\ServiceCategory;
use App\Models\StaffLocations;
use App\Models\Appointments;
use App\Models\AppointmentServices;
use App\Models\PaidPlan;
use App\Models\SoldPaidPlan;
use App\Models\Voucher;
use App\Models\SoldVoucher;
use App\Models\Taxes;
use App\Models\InvoiceTaxes;
use App\Models\StaffTip;
use App\Mail\ConsultationFormReminder;
use DataTables;
use DB;
use Crypt;
use Session;
use Mail;

class ConsultationFormController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return redirect(route('showconForm'));
        return view('conForm.consultant-form');
    }
	
    public function showconForm()
    {
        return view('conForm.consultation_form');
    }

	public function getconform(Request $request){
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
			
            $conform = ConsForm::where('user_id',$AdminId)->where('is_delete','0')->get()->toArray();

            $data_arr = [];
            foreach($conform as $val)
            {
                $tempData = array(
                    'id' => $val['id'],
                    'user_id' => $val['user_id'],
                    'name' => $val['name'],
                    'send_request' => $val['send_request'],
                    'status' => $val['status'],
                    'service_id' => $val['service_id'],
                    
                );
                array_push($data_arr, $tempData);
            }
			// dd($data_arr);
			// dd(json_decode($data_arr[0]['service_id']));
         
            return Datatables::of($data_arr)
                ->editColumn('name', function ($row) {
                    $html = '<td class="font-weight-bolder"><a href="#">'.$row['name'].'</a></td>';
                    return $html;
                })
				->editColumn('service_id', function ($row) {
					$tempArray = json_decode($row['service_id']);

					if(!empty($tempArray)){
						$html = '<td>'.count($tempArray).' services'.'</td>';
						return $html;
					}else{
						return '<td>0 services </td>';
					}
                })
				->editColumn('send_request', function ($row) {
                    $html = '<td>'.$row['send_request']==0 ? 'Before appointment' : 'Manually'.'</td>';
                    return $html;
                })
				->editColumn('status', function ($row) {
                    $html = '';
                    $html = '<td><span class="badge badge-warning text-uppercase">';
                    if($row['status']==0) {$html.= 'INACTIVE'; } else { $html.= 'ACTIVE';}
                    $html.= '</span></td>';
                    return $html;
                })
                ->editColumn('id', function ($row) {
                    $edit=route('geteditconform',$row['id']);
                    $preview=route('preview',$row['id']);
                    $overview=route('overview',$row['id']);
                    $html = '';
                    $html .= '<tr class="cursor-pointer"><td>
                    <div class="dropdown dropdown-inline">
                        <a href="#" class="btn btn-clean text-dark btn-sm btn-icon"
                            data-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                            <i class="ki ki-bold-more-ver text-dark"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right text-center">
                            <ul class="navi navi-hover">
                                <li class="navi-item">
                                    <a href="'.$overview.'"
                                        class="navi-link">
                                        <span class="navi-text">Overview</span>
                                    </a>
                                </li>
                                <li class="navi-item">
                                    <a href="'.$edit.'"
                                        class="navi-link">
                                        <span class="navi-text">Edit</span>
                                    </a>
                                </li>
                                <li class="navi-item">
                                    <a href="'.$preview.'"
                                        class="navi-link">
                                        <span class="navi-text">Preview</span>
                                    </a>
                                </li>
                                <li class="navi-item">
                                    ';  
                                    if($row['status']==0) {$html .= '<a href="#" data-toggle="modal" data-target="#ActiveFormModal" data-id="'.$row['id'].'" data-name="'.$row['name'].'" class="navi-link" id="activate"><span class="navi-text">Activate</span></a>'; } else{ $html .= '<a data-toggle="modal" data-target="#DeactiveFormModal" data-id="'.$row['id'].'" data-name="'.$row['name'].'" class="navi-link" id="deactivate">
                                        <span class="navi-text">Deactivate</span>
                                    </a>';}
                                $html .= '</li>
                            </ul>
                        </div>
                    </div>
                </td></tr>';
                    return $html;
                })
                ->rawColumns(['name','service_id','send_request','status','id'])
                ->setRowAttr([
                    'data-id' => function($row) {
                        return $row['id'];
                    },
                    'class' => function($row) {
                        return "editloctaxes";
                    },
                ])
                ->make(true);
        }
	}

    public function showCreateform()
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
		
		$cat = ServiceCategory::where('is_deleted',0)->where('user_id', $AdminId)->with('service.servicePrice')->get()->toArray();

		$Country = Country::get()->toArray();

        return $this->addConsultationForm();//view('conForm.consultation_form_create',compact('cat','Country'));
    }

    public function activeform($id){
        
        $form = ConsForm::where('id',$id)->first();
        $form->update([
            'status' => 1,
        ]);
        $data["status"] = true;
        $data["message"] = 'Form Status update successfully!';
        return JsonReturn::success($data);
        
    }
      
    public function deactiveform($id){
        
        $form = ConsForm::where('id',$id)->first();
        $form->update([
            'status' => 0,
        ]);
        $data["status"] = true;
        $data["message"] = 'Form Status update successfully!';
        return JsonReturn::success($data);
        
    }

    public function conFormClientInfo(Request $request){

        if($request->ajax())
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
			
            parse_str($request->formdata, $formdataarray);
            parse_str($request->client, $clientdataarray);
            // return $clientdataarray;
            // save form
            $formdetails = ConsForm::create([
                'user_id'       => $AdminId,
                'name'          => $formdataarray['name'],
                'send_request'  => $formdataarray['send_request']=='before-appoinment' ? 0 : 1,
                'status' => isset($formdataarray['status'])=='on' ? 1 : 0,
                'complete' => $formdataarray['complete'],
                'signature' => ($formdataarray['signature']=='on') ? 1 : 0,
                'service_id' => json_encode($formdataarray['value_checkbox']),
            ]);
            
            if($clientdataarray['section_title'] != ''){
                // save form client info
                $addclientinfo[] = [
                    'user_id'           => $AdminId,
                    'form_id'           => $formdetails->id,
                    'section_id'           => $clientdataarray['clientse_id'],
                    'section_title'     => $clientdataarray['section_title'] ,
                    'section_des' => isset($clientdataarray['section_des']) ? $clientdataarray['section_des'] : null,
                    'first_name' => isset($clientdataarray['first_name'])=='on' ? 1 : 0,
                    'last_name' => isset($clientdataarray['last_name'])=='on' ? 1 : 0,
                    'email' => isset($clientdataarray['email'])=='on' ? 1 : 0,
                    'birthday' => isset($clientdataarray['birthday'])=='on' ? 1 : 0,
					'country_code' => (isset($clientdataarray['country_code'])) ? $clientdataarray['country_code'] : 1,
                    'mobile' => isset($clientdataarray['mobile'])=='on' ? 1 : 0,
                    'gender' => isset($clientdataarray['gender'])=='on' ? 1 : 0,
                    'address' => isset($clientdataarray['address'])=='on' ? 1 : 0,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
                $insert_role = DB::table('conForm_clientDetails')->insert($addclientinfo);
            }
            
            // save form qna info
            if(isset($request->filterdata) )
			{
                $max = count($request->filterdata)==1 ? 0 : count($request->filterdata)-1 ;

                for ($i=0; $i <= $max; $i++) 
				{
                    parse_str($request->filterdata[$i], $secdata);

                    $max1 = count($secdata['answer_type'])==1 ? 0 : count($secdata['answer_type'])-1 ;
                    // return $max1;
                    for ($j=0; $j <= $max1 ; $j++) 
					{
                        // return $secdata;

                        $data=[
                            'user_id'       => $AdminId,
                            'form_id'       => $formdetails->id,
                            'section_id'    => $secdata['sectionid'][0],
                            'title'         => $secdata['title'][0] ,
                            'des'           => isset($secdata['des']) ? $secdata['des'][0] : null,
                            'required'      => ($secdata['required'][$j]=='on') ? 1 : 0,
                            'answer_type'   => isset($secdata['answer_type'][$j]) ? $secdata['answer_type'][$j] : null,
                            'question'      => $secdata['answer_type'][$j]=='des' ? null : $secdata['question'][$j],
                            '1ans'          => isset($secdata['ans1']) ? $secdata['ans1'] : null,
                            '2ans'          => isset($secdata['ans2']) ? 'text' : null,
                            '3ans'   => isset($secdata['ans3']) ? json_encode($secdata['ans3']) : null,
                            '4ans'   => isset($secdata['ans4']) ? 'check' : null,
                            '5ans'   => isset($secdata['ans5']) ? json_encode($secdata['ans5']) : null,
                            '6ans'   => isset($secdata['ans6']) ? json_encode($secdata['ans6']) : null,
                            '7ans'   => isset($secdata['ans7']) ? json_encode($secdata['ans7']) : null,
                            '8ans'   => isset($secdata['ans8']) ? $secdata['ans8'] : null,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ];
                        // return $data;

                        $insert_role = DB::table('conForm_custom_section')->insert($data);
                    }
                }
            }
            
            $data["status"] = true;
            $data["url"] = route('showconForm');
            $data["message"] = 'Form create successfully!';
            return JsonReturn::success($data);
        }
    }

    public function geteditconform($id)
	{
		return $this->editConsultationForm($id);
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
		
		$cat = ServiceCategory::where('is_deleted',0)->where('user_id', $AdminId)->with('service.servicePrice')->get()->toArray();
        $conform = ConsForm::where('id',$id)->where('is_delete','0')->with('qna','client')->first();
	
		$Country = Country::get()->toArray();
		
		$TotalServices = 0;
		$TotalFormServices = ($conform->service_id) ? count(json_decode($conform->service_id)) : 0;
		
		foreach($cat as $catdata){
			foreach($catdata['service'] as $servicedata){
				foreach ($servicedata['service_price'] as $priceKey => $servicePrice){
					$TotalServices++;
				}
			}
		}
		
        return view('conForm.consultation_form_update',compact('conform','cat','Country','TotalFormServices','TotalServices'));

    }

    public function updateconform(Request $request){
        if($request->ajax())
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

            // return $request->filterdata;
            parse_str($request->formdata, $formdataarray);
            parse_str($request->client, $clientdataarray);
            // return $clientdataarray;
            // save form
            $form = ConsForm::where('id',$formdataarray['formid'])->first();
            $form->update([
                'user_id'       => $AdminId,
                'name'          => $formdataarray['name'],
                'send_request'  => $formdataarray['send_request']=='before-appoinment' ? 0 : 1,
                'status' => isset($formdataarray['status'])=='on' ? 1 : 0,
                'complete' => $formdataarray['complete'],
                'signature' => ($formdataarray['signature']=='on') ? 1 : 0,
                'service_id' => json_encode($formdataarray['value_checkbox']),
            ]);
            
            if($clientdataarray['section_title'] != ''){
                // save form client info
                $client = conFormClientDetails::where('id',$clientdataarray['client_id'])->first();
                if($client){
                    $client->update([
                        'user_id'           => $AdminId,
                        'form_id'           => $form->id,
                        'section_id'           => $clientdataarray['secton_id'],
                        'section_title'     => $clientdataarray['section_title'] ,
                        'section_des' => isset($clientdataarray['section_des']) ? $clientdataarray['section_des'] : null,
                        'first_name' => isset($clientdataarray['first_name'])=='on' ? 1 : 0,
                        'last_name' => isset($clientdataarray['last_name'])=='on' ? 1 : 0,
                        'email' => isset($clientdataarray['email'])=='on' ? 1 : 0,
                        'birthday' => isset($clientdataarray['birthday'])=='on' ? 1 : 0,
						'country_code' => (isset($clientdataarray['country_code'])) ? $clientdataarray['country_code'] : 1,
                        'mobile' => isset($clientdataarray['mobile'])=='on' ? 1 : 0,
                        'gender' => isset($clientdataarray['gender'])=='on' ? 1 : 0,
                        'address' => isset($clientdataarray['address'])=='on' ? 1 : 0,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
                } else {
                    conFormClientDetails::create([
                        'user_id'           => $AdminId,
                        'form_id'           => $form->id,
                        'section_id'           => $clientdataarray['secton_id'],
                        'section_title'     => $clientdataarray['section_title'] ,
                        'section_des' => isset($clientdataarray['section_des']) ? $clientdataarray['section_des'] : null,
                        'first_name' => isset($clientdataarray['first_name'])=='on' ? 1 : 0,
                        'last_name' => isset($clientdataarray['last_name'])=='on' ? 1 : 0,
                        'email' => isset($clientdataarray['email'])=='on' ? 1 : 0,
                        'birthday' => isset($clientdataarray['birthday'])=='on' ? 1 : 0,
                        'mobile' => isset($clientdataarray['mobile'])=='on' ? 1 : 0,
                        'gender' => isset($clientdataarray['gender'])=='on' ? 1 : 0,
                        'address' => isset($clientdataarray['address'])=='on' ? 1 : 0,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
                }
            }
            
            //remove
                $remove = conFormCustomSection::where('form_id',$form->id)->get();
                // $remove->delete();
                foreach($remove as $qna)
                {
                    $qna->delete();
                }
            //end
            // save form qna info
            if(isset($request->filterdata) ){
                $max = count($request->filterdata)==1 ? 0 : count($request->filterdata)-1 ;

                for ($i=0; $i <= $max; $i++) {
                    parse_str($request->filterdata[$i], $secdata);

                    $max1 = count($secdata['answer_type'])==1 ? 0 : count($secdata['answer_type'])-1 ;
                    // return $max1;
                    for ($j=0; $j <= $max1 ; $j++) {

                        $data=[
                            'user_id'       => $AdminId,
                            'form_id'       => $form->id,
                            'section_id'    => $secdata['sectionid'][0],
                            'title'         => $secdata['title'][0] ,
                            'des'           => isset($secdata['des']) ? $secdata['des'][0] : null,
                            'required'      => ($secdata['required'][$j]=='on') ? 1 : 0,
                            'answer_type'   => isset($secdata['answer_type'][$j]) ? $secdata['answer_type'][$j] : null,
                            'question'      => $secdata['answer_type'][$j]=='des' ? null : $secdata['question'][$j],
                            '1ans'          => isset($secdata['ans1']) ? $secdata['ans1'] : null,
                            '2ans'          => isset($secdata['ans2']) ? 'text' : null,
                            '3ans'   => isset($secdata['ans3']) ? json_encode($secdata['ans3']) : null,
                            '4ans'   => isset($secdata['ans4']) ? 'check' : null,
                            '5ans'   => isset($secdata['ans5']) ? json_encode($secdata['ans5']) : null,
                            '6ans'   => isset($secdata['ans6']) ? json_encode($secdata['ans6']) : null,
                            '7ans'   => isset($secdata['ans7']) ? json_encode($secdata['ans7']) : null,
                            '8ans'   => isset($secdata['ans8']) ? $secdata['ans8'] : null,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ];
                        // return $data;

                        $insert_role = DB::table('conForm_custom_section')->insert($data);
                    }
                }
            }
            
            $data["status"] = true;
            $data["url"] = route('showconForm');
            $data["message"] = 'Form Details Update successfully!';
            return JsonReturn::success($data);
            
        }
    }

    public function preview($id)
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
		
        $cat = ServiceCategory::where('is_deleted',0)->where('user_id', $AdminId)->with('service.servicePrice')->get();
        $conform = ConsForm::where('id',$id)->where('is_delete','0')->with('qna','client')->first();

        return view('conForm.consultation_form_preview',compact('cat','conform'));
    }

    public function overview($id) 
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
		
        $conform = ConsForm::where('id',$id)->where('is_delete','0')->with('qna','client')->first();
        $cat = ServiceCategory::where('is_deleted',0)->where('user_id', $AdminId)->with('service.servicePrice')->get();

		// get generated consultation forms
		$ClientConsultationFormGet = ClientConsultationForm::select('client_consultation_form.id','client_consultation_form.complete_before','client_consultation_form.completed_at','client_consultation_form.status','clients.firstname','clients.lastname','clients.email')->leftJoin('clients','clients.id','client_consultation_form.client_id')->where('client_consultation_form.consultation_from_id',$id)->get()->toArray();	
		
		// total sent consultation form
		$totalSentConsultationForm = ClientConsultationForm::select('client_consultation_form.id')->where('client_consultation_form.consultation_from_id',$id)->get()->count();
		
		// total completed consultation form
		$totalCompletedConsultationForm = ClientConsultationForm::select('client_consultation_form.id')->where('client_consultation_form.consultation_from_id',$id)->where('client_consultation_form.status',1)->get()->count();
		
		// total completed consultation form
		$CurrentDate = date("Y-m-d");
		
		$totalToBeConsultationForm = ClientConsultationForm::select('client_consultation_form.id')->where('client_consultation_form.consultation_from_id',$id)->whereDate('client_consultation_form.complete_before','>=',$CurrentDate)->where('client_consultation_form.status',0)->get()->count();
		
		$totalNotCompletedConsultationForm = ClientConsultationForm::select('client_consultation_form.id')->where('client_consultation_form.consultation_from_id',$id)->whereDate('client_consultation_form.complete_before','<',$CurrentDate)->where('client_consultation_form.status',0)->get()->count();
		
		$TotalSentPercentage  = 100;
		$TotalCompletedPer    = 0;
		$TotalTobeCompletePer = 0;
		$TotalNotCompletePer  = 0;
		
		if($totalSentConsultationForm != 0 && $totalCompletedConsultationForm != 0){
			$TotalCompletedPer = round(($totalCompletedConsultationForm * 100) / $totalSentConsultationForm);
		}
		
		if($totalSentConsultationForm != 0 && $totalToBeConsultationForm != 0){
			$TotalTobeCompletePer = round(($totalToBeConsultationForm * 100) / $totalSentConsultationForm);
		}
		
		if($totalSentConsultationForm != 0 && $totalNotCompletedConsultationForm != 0){
			$TotalNotCompletePer = round(($totalNotCompletedConsultationForm * 100) / $totalSentConsultationForm);
		}
		
        return view('conForm.consultation_form_detail',compact('conform','cat','ClientConsultationFormGet','totalSentConsultationForm','totalCompletedConsultationForm','totalToBeConsultationForm','totalNotCompletedConsultationForm','TotalSentPercentage','TotalCompletedPer','TotalTobeCompletePer','TotalNotCompletePer'));
    }
	
	public function getConsultationForms(Request $request)
	{
		if ($request->ajax()) 
		{
			$FormId = ($request->consultationFormId) ? $request->consultationFormId : 0;
			
            // get generated consultation forms
			$ClientConsultationFormGet = ClientConsultationForm::select('client_consultation_form.id','client_consultation_form.complete_before','client_consultation_form.completed_at','client_consultation_form.status','clients.firstname','clients.lastname','clients.email')->leftJoin('clients','clients.id','client_consultation_form.client_id')->where('client_consultation_form.consultation_from_id',$FormId)->get()->toArray();	
			
			$data_arr = array();
			foreach($ClientConsultationFormGet as $val)
			{
				$tempData = array(
					'client_consultation_form_id' => $val['id'],
					'client_firstname'            => substr($val['firstname'],0,1),
					'client_name'                 => $val['firstname'].' '.$val['lastname'],
					'client_email'                => $val['email'],
					'complete_before'             => $val['complete_before'],
					'requested_on'                => ($val['complete_before']) ? date("d M ,Y",strtotime($val['complete_before'])) : '-',
					'completed_on'                => ($val['completed_at']) ? date("d M ,Y",strtotime($val['completed_at'])) : '-',
					'form_status'                 => $val['status']
				);
				array_push($data_arr, $tempData);
			}
			
            return Datatables::of($data_arr)
                ->addIndexColumn()
				->editColumn('client_firstname', function ($row) {
					$html = '<td><span class="namespan">'.$row['client_firstname'].'</span></td>';
					return $html;
				})
				->editColumn('client_name', function ($row) {
					$html2 = '<td>'.$row['client_name'].' <br> '.$row['client_email'].'</td>';
					return $html2;
				})
				->editColumn('form_status', function ($row) {
					
					$currentDate     = date("Y-m-d");
					$complete_before = date("Y-m-d",strtotime($row['complete_before']));
					
					if($currentDate > $complete_before && $row['form_status'] == 0){
						$html3 = '<td><span class="badge badge-pill badge-danger">NOT COMPLETED</span></td>';	
					} else {
						if($row['form_status'] == '0'){
							$html3 = '<td><span class="badge badge-pill badge-warning">TO BE COMPLETED</span></td>';	
						} else {
							$html3 = '<td><span class="badge badge-pill badge-success">COMPLETED</span></td>';
						}	
					}
					
					return $html3;
				})
				->editColumn('action', function ($row) {
					$html4 = '<td>
								<div class="dropdown dropdown-inline">
									<a href="#" class="btn btn-clean text-dark btn-sm btn-icon"
										data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										<i class="ki ki-bold-more-hor text-dark"></i>
									</a>
									<div class="dropdown-menu dropdown-menu-md dropdown-menu-right text-center">
										<ul class="navi navi-hover">';
											
											if($row['form_status'] != 1) {
												$html4 .=	
												'<li class="navi-item">
													<a href="javascript:;" class="navi-link sendEmailReminder" data-consultationFormId="'.$row['client_consultation_form_id'].'">
														<span class="navi-text">Send Email Reminder</span>
													</a>
												</li>';
												if (Auth::user()->can('complete_consultation_forms'))	{
													$html4 .=
													'<li class="navi-item">
														<a href="'.route('completeConsultationForm',['consultationId' => Crypt::encryptString($row['client_consultation_form_id'])]).'" class="navi-link">
															<span class="navi-text">Complete the form</span>
														</a>
													</li>';
												} else {
													$html4 .=
													'<li class="navi-item">
														<a href="javascript:;" class="no_access navi-link">
															<span class="navi-text">Complete the form</span>
														</a>
													</li>';
												}		
											} else {
												if (Auth::user()->can('view_client_responses'))	{
													$html4 .=
													'<li class="navi-item">
														<a href="'.route('viewClientConsultationForm',['consultationId' => Crypt::encryptString($row['client_consultation_form_id'])]).'" class="navi-link">
															<span class="navi-text">View client response</span>
														</a>
													</li>';
												} else {
													$html4 .=
													'<li class="navi-item">
														<a href="javascript:;" class="no_access navi-link">
															<span class="navi-text">View client response</span>
														</a>
													</li>';	
												}			
											}
										$html4 .=
										'</ul>
									</div>
								</div>
							</td>';
					return $html4;
				})
                ->rawColumns(['client_firstname','client_name','form_status','action'])
                ->make(true);
        }
	}
	
	public function completeConsultationForm($consultationId = null) {
		if($consultationId != '')
		{
			$consultationId = Crypt::decryptString($consultationId);
			
			$ClientConsultationFormGet = ClientConsultationForm::select('*')->with('clientConsultationFields')->where('id',$consultationId)->get()->first()->toArray();
			
			$ClientConsultationFormField = ClientConsultationFormField::select('section_id','section_title','section_description')->where('client_consultation_form_id',$consultationId)->distinct()->get('section_id')->toArray();
			
			$TotalSteps = count($ClientConsultationFormField);
			
			//main step counter plus
			$TotalSteps = $TotalSteps + 1;
			
			if($ClientConsultationFormGet['is_signature']){
				$TotalSteps = $TotalSteps + 1;
			}
			
			$Country = Country::get()->toArray();
			
			return view('conForm.submitConsultationForm',compact('ClientConsultationFormGet','ClientConsultationFormField','TotalSteps','Country'));
		}	
	}
	
	public function viewClientConsultationForm($consultationId = null){
		if($consultationId != '')
		{
			$consultationId = Crypt::decryptString($consultationId);
			
			$ClientConsultationFormGet = ClientConsultationForm::select('*')->with('clientConsultationFields')->where('id',$consultationId)->get()->first()->toArray();
			
			$LocationInfo = array();
			if(!empty($ClientConsultationFormGet)) {
				$LocationInfo = Location::select('location_name')->where(['id'=> $ClientConsultationFormGet['location_id']])->get()->first()->toArray();
			}
			
			$ClientConsultationFormField = ClientConsultationFormField::select('section_id','section_title','section_description')->where('client_consultation_form_id',$consultationId)->distinct()->get('section_id')->toArray();
			
			return view('conForm.viewConsultationForm',compact('ClientConsultationFormGet','ClientConsultationFormField','LocationInfo'));
		}	
	}
	
	public function printClientConsultationForm(Request $request)
	{
		if ($request->ajax()){
			$consultationId = Crypt::decryptString($request->consultationFormId);
			
			$ClientConsultationFormGet = ClientConsultationForm::select('*')->with('clientConsultationFields')->where('id',$consultationId)->get()->first()->toArray();
			
			$LocationInfo = array();
			if(!empty($ClientConsultationFormGet)) {
				$LocationInfo = Location::select('location_name')->where(['id'=> $ClientConsultationFormGet['location_id']])->get()->first()->toArray();
			}
			
			$ClientConsultationFormField = ClientConsultationFormField::select('section_id','section_title','section_description')->where('client_consultation_form_id',$consultationId)->distinct()->get('section_id')->toArray();
			
			return view('conForm.printConsultationForm',compact('ClientConsultationFormGet','ClientConsultationFormField','LocationInfo'))->render();
		}
	}
	
	public function completeSaveConsultationForm(Request $request)
	{
		if ($request->isMethod('post')) {
			$client_consultation_form_id       = ($request->client_consultation_form_id) ? $request->client_consultation_form_id : '';
			$client_first_name                 = ($request->client_first_name) ? $request->client_first_name : null;
			$client_last_name                  = ($request->client_last_name) ? $request->client_last_name : null;
			$client_email                      = ($request->client_email) ? $request->client_email : null;
			$client_birthday                   = ($request->client_birthday) ? date("Y-m-d",strtotime($request->client_birthday)) : null;
			$country_code                      = ($request->country_code) ? $request->country_code : null;
			$client_mobile                     = ($request->client_mobile) ? $request->client_mobile : null;
			$client_gender                     = ($request->client_gender) ? $request->client_gender : null;
			$client_address                    = ($request->client_address) ? $request->client_address : null;
			$client_consultation_form_field_id = ($request->client_consultation_form_field_id) ? $request->client_consultation_form_field_id : array();
			$client_answer                     = ($request->client_answer) ? $request->client_answer : array();
			$signature_name                    = ($request->signature_name) ? $request->signature_name : null;
			
			$ClientConsultationFormGet = ClientConsultationForm::select('*')->with('clientConsultationFields')->where('id',$client_consultation_form_id)->get()->first()->toArray();
			
			if(!empty($ClientConsultationFormGet)){
				$ClientConsultationFormUpdate = ClientConsultationForm::find($client_consultation_form_id);
				
				$ClientConsultationFormUpdate->client_first_name = $client_first_name; 
				$ClientConsultationFormUpdate->client_last_name  = $client_last_name; 
				$ClientConsultationFormUpdate->client_email      = $client_email; 
				$ClientConsultationFormUpdate->client_birthday   = $client_birthday; 
				$ClientConsultationFormUpdate->country_code      = $country_code; 
				$ClientConsultationFormUpdate->client_mobile     = $client_mobile; 
				$ClientConsultationFormUpdate->client_gender     = $client_gender; 
				$ClientConsultationFormUpdate->client_address    = $client_address; 
				$ClientConsultationFormUpdate->signature_name    = $signature_name; 
				$ClientConsultationFormUpdate->status            = 1; 
				$ClientConsultationFormUpdate->completed_at      = date("Y-m-d H:i:s"); 
				$ClientConsultationFormUpdate->updated_at        = date("Y-m-d H:i:s"); 
				if($ClientConsultationFormUpdate->save()) 
				{
					if(!empty($client_consultation_form_field_id)) 
					{
						foreach($client_consultation_form_field_id as $CCFFI){
							$ClientConsultationFormField = ClientConsultationFormField::find($CCFFI);
							
							if(!empty($ClientConsultationFormField)){
								if(isset($client_answer[$CCFFI][0]) && count($client_answer[$CCFFI]) > 0){
									
									$elemt = (count($client_answer[$CCFFI]) > 1) ? implode(",",$client_answer[$CCFFI]) : $client_answer[$CCFFI][0];
									
									$ClientConsultationFormField->client_answer = $elemt;		
									$ClientConsultationFormField->updated_at    = date("Y-m-d H:i:s"); 
									$ClientConsultationFormField->save();		
								}
							}		
						}
					}
					
					Session::flash('message', 'Consultation form updated succesfully.');
					return redirect()->route('overview',['id' => $ClientConsultationFormGet['consultation_from_id']]);
				} else {
					Session::flash('error', 'Something went wrong.');
					return redirect()->route('overview',['id' => $ClientConsultationFormGet['consultation_from_id']]);
				}
			} else {
				Session::flash('error', 'Something went wrong.');
				return redirect()->route('overview',['id' => $ClientConsultationFormGet['consultation_from_id']]);
			}
		} else {
			Session::flash('error', 'Something went wrong.');
			return redirect()->route('overview',['id' => $ClientConsultationFormGet['consultation_from_id']]);
		}
	}
	
	public function consultationFormEmailReminder(Request $request){
		if ($request->ajax()) 
        {
            $rules = [
                'CSID' => 'required'
            ];

            $input = $request->only(
                'CSID'
            );

            $validator = Validator::make($input, $rules);
			
            if ($validator->fails()) {
                return JsonReturn::error($validator->messages());
            }
			
			$CSID = ($request->CSID) ? $request->CSID : 0;
			
			// get generated consultation forms
			$ClientConsultationFormGet = ClientConsultationForm::select('client_consultation_form.id','client_consultation_form.client_id','client_consultation_form.user_id','client_consultation_form.user_id','client_consultation_form.location_id','client_consultation_form.appointment_id','client_consultation_form.complete_before','clients.firstname','clients.lastname','clients.email','locations.location_name','locations.location_address','locations.location_latitude','locations.location_longitude','locations.location_image')->leftJoin('clients','clients.id','client_consultation_form.client_id')->leftJoin('locations','locations.id','client_consultation_form.location_id')->where('client_consultation_form.id',$CSID)->get()->first()->toArray();	
			
			// get appointment details
			$Appointment = Appointments::select('*')->where('id',$ClientConsultationFormGet['appointment_id'])->orderBy('id', 'desc')->get()->first()->toArray();
			
			// get appointment services
			$AppointmentServices = AppointmentServices::select('*')->where('appointment_id',$ClientConsultationFormGet['appointment_id'])->orderBy('id', 'desc')->get()->toArray();
			
			$ClientServices = array();
			
			if(!empty($AppointmentServices))
			{
				foreach($AppointmentServices as $AppointmentServiceData)
				{
					$service_price_id = $AppointmentServiceData['service_price_id'];
					
					$Services = ServicesPrice::select('services_price.id as service_price_id','services_price.duration','services_price.price_type','services_price.price','services_price.special_price','services_price.pricing_name','services_price.lowest_price','services_price.staff_prices','services.id as service_id','services.service_name','services.extra_time','services.is_extra_time','services.extra_time_duration')->join('services','services.id','=','services_price.service_id')->where('services_price.id',$service_price_id)->orderBy('services_price.id', 'ASC')->get()->first()->toArray();
					
					$getUser = User::getUserbyID($AppointmentServiceData['staff_user_id']);
					
					$tempClientServices['appointment_service_id'] = $AppointmentServiceData['id'];
					$tempClientServices['duration']               = $this->hoursandmins($AppointmentServiceData['duration']);
					$tempClientServices['price']                  = ($Services['price']) ? $Services['price'] : 0;
					$tempClientServices['special_price']          = $AppointmentServiceData['special_price'];
					$tempClientServices['staff_user_id']          = $AppointmentServiceData['staff_user_id'];
					$tempClientServices['staff_name']             = $getUser->first_name.' '.$getUser->last_name;
					$tempClientServices['service_name']           = ($Services['service_name']) ? $Services['service_name'] : '';
					$tempClientServices['service_pricing_name']   = ($Services['pricing_name']) ? $Services['pricing_name'] : '';
					$tempClientServices['start_time']             = ($AppointmentServiceData['start_time']) ? date("h:ia",strtotime($AppointmentServiceData['start_time'])) : '';
					
					array_push($ClientServices,$tempClientServices);
				}
			}
			
			$FROM_EMAIL     = env("MAIL_FROM_ADDRESS", "info@ikotel.ca");
			$FROM_NAME      = ($ClientConsultationFormGet) ? $ClientConsultationFormGet['location_name'] : 'Location';
			$TO_EMAIL       = ($ClientConsultationFormGet) ? $ClientConsultationFormGet['email'] : '';
			$SUBJECT        = 'We need some details before your appointment '.date("l, M d",strtotime($ClientConsultationFormGet['complete_before'])).' at '.date("h:i a",strtotime($ClientConsultationFormGet['complete_before']));
			$MESSAGE        = 'Appointment';
			$UniqueId       = $this->unique_code(30).time();
			
			$SendMail = Mail::to($TO_EMAIL)->send(new ConsultationFormReminder($FROM_EMAIL,$FROM_NAME,$SUBJECT,$MESSAGE,$UniqueId,$ClientConsultationFormGet,$Appointment,$ClientServices));	
			
			EmailLog::create([
				'user_id'          => ($ClientConsultationFormGet) ? $ClientConsultationFormGet['user_id'] : '',
				'client_id'        => ($ClientConsultationFormGet) ? $ClientConsultationFormGet['client_id'] : '',
				'appointment_id'   => ($ClientConsultationFormGet) ? $ClientConsultationFormGet['appointment_id'] : '',	
				'from_email'       => $FROM_EMAIL,	
				'to_email'         => $TO_EMAIL,	
				'module_type'      => 3,	
				'module_type_text' => 'Consultation Form Reminder',
				'unique_id'        => $UniqueId,
				'created_at'       => date("Y-m-d H:i:s")
			]);
			
			$data["status"] = true;
			$data["message"] = "Mail has been sent succesfully.";	
			return JsonReturn::success($data);   
        }
        else
        {
            $data["status"] = false;
            $data["message"] = array("Sorry somethig went wrong.");
            $data["message_code"] = array("Out of ajax condition.");
            return JsonReturn::success($data);     
        }
	}
	
	public function searchForServiceCategory(Request $request)
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
		
		$searchKeyWord = $request->searchKeyWord;	
		
		if($searchKeyWord != ''){
			$serviceCategory = ServiceCategory::select('service_category.*')->leftJoin('services','services.service_category','=','service_category.id')->leftJoin('services_price','services_price.service_id','=','services.id')->where('service_category.is_deleted',0)->where('service_category.user_id', $AdminId)->where(function ($query) use ($searchKeyWord){
			$query->where('service_category.category_title','LIKE','%'.$searchKeyWord.'%')
				  ->orWhere('services.service_name','LIKE','%'.$searchKeyWord.'%')
				  ->orWhere('services.treatment_type','LIKE','%'.$searchKeyWord.'%')
				  ->orWhere('services.service_description','LIKE','%'.$searchKeyWord.'%');
			})->with('service.servicePrice')->groupBy('service_category.id')->get()->toArray();	
		} else {
			$serviceCategory = ServiceCategory::select('service_category.*')->leftJoin('services','services.service_category','=','service_category.id')->leftJoin('services_price','services_price.service_id','=','services.id')->where('service_category.is_deleted',0)->where('service_category.user_id', $AdminId)->with('service.servicePrice')->groupBy('service_category.id')->get()->toArray();	
		}
		
		$html = '';
		if(!empty($serviceCategory)) {
			
			$html  .= 
			'<ul id="treeview">
				<li>
					<label for="all" class="checkbox allService">
						<input type="checkbox" name="all" id="all">
						<span></span>
						All Services
					</label>
					<ul>';
			
				foreach($serviceCategory as $serviceKey => $serviceValue) {
					
				$html  .= 	
					'<li>
						<label for="all-'.$serviceValue['category_title'].'" class="checkbox">
							<input type="checkbox" id="all-'.$serviceValue['category_title'].'">
							<span></span>
							'.$serviceValue['category_title'].'
						</label>
						<ul>';
						
							foreach ($serviceValue['service'] as $serviceData){
								foreach ($serviceData['service_price'] as $priceKey => $servicePrice){
									
									$html  .= 	
									'<li>
										<label for="all-'.$serviceValue['category_title'].'-'.$serviceData['id'].'-'.$priceKey.'" class="checkbox">
											<input type="checkbox" name="value_checkbox[]" id="all-'.$serviceValue['category_title'].'-'.$serviceData['id'].'-'.$priceKey.'" value="'.$serviceData['id'].'">
											<span></span>
											<div class="d-flex align-items-center w-100">
												<span class="m-0">';
													
													$totalMinutes = $servicePrice['duration'];
													$hours = intval($totalMinutes/60);
													$minutes = $totalMinutes - ($hours * 60);
													$duration = $hours."h ".$minutes."min";
													
													$html  .= $serviceData['service_name'];
													
													$html  .= 
													'<p class="m-0 text-muted">p'.($priceKey + 1).','.$duration.'</p>
												</span>
												<span class="ml-auto">
													CA $'.$servicePrice['price'].'
												</span>
											</div>
										</label>
									</li>';
								}
							}
						
						$html  .= '
						</ul>
					</li>';
				}
				
			$html  .= '
					</ul>
				</li>	
			</ul>';
		} else {
			$html = '<ul id="treeview"><li><label>No data found.</label></li></ul>';
		}

		$data["status"] = true;
		$data["html"]   = $html;	
		return JsonReturn::success($data);
	}
	
	function addConsultationForm()
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
		
		$Services = ServiceCategory::with('service.servicePrice')->where('is_deleted',0)->where('user_id',$AdminId)->get()->toArray();
	
		return view('conForm.createConsultationForm',compact('Services'));
	}
	
	function saveConsultationFormDetails(Request $request)
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
			
			$TOTAL_SECTION        = ($request->total_section) ? $request->total_section : 0;
			
			// custom section related data
			$SECTION_ID           = ($request->section_id) ? $request->section_id : array();
			$FORM_TYPE            = ($request->form_type) ? $request->form_type : array();
			$CUSTOM_SECTION_TITLE = ($request->customSectionTitle) ? $request->customSectionTitle : array();
			$CUSTOM_SECTION_DESCR = ($request->customSectionDescription) ? $request->customSectionDescription : array();
			$QUESTION_TYPE        = ($request->answer_type) ? $request->answer_type : array();
			$QUESTION             = ($request->question) ? $request->question : array();
			$IS_REQUIRED          = ($request->is_required) ? $request->is_required : array();
			$IS_REQUIRED_VAL      = ($request->is_required_value) ? $request->is_required_value : array();
			$answerField          = ($request->answerField) ? $request->answerField : array();
			$subUniqueID          = ($request->subUniqueID) ? $request->subUniqueID : array();

			// client form related data
			$clientFormTitle      = ($request->clientFromSectionTitle) ? $request->clientFromSectionTitle : '';
			$clientFormDescr      = ($request->clientFromSectionDesc) ? $request->clientFromSectionDesc : '';
			$isFirstName          = ($request->first_name) ? $request->first_name : 0;
			$isLastName           = ($request->last_name) ? $request->last_name : 0;
			$isEmail              = ($request->email) ? $request->email : 0;
			$isBirthday           = ($request->birthday) ? $request->birthday : 0;
			$CountryCode          = ($request->country_code) ? $request->country_code : 1;
			$isMobile             = ($request->mobile) ? $request->mobile : 0;
			$isGender             = ($request->gender) ? $request->gender : 0;
			$isAddress            = ($request->address) ? $request->address : 0;
			
			// consultation form realted data
			$CONSULTATION_FORM_NAME      = ($request->consultation_form_name) ? $request->consultation_form_name : ''; 
			$CONSULTATION_FORM_TYPE      = ($request->consultation_form_type) ? $request->consultation_form_type : 0; 
			$CONSULTATION_FORM_WHEN      = ($request->consultation_form_when) ? $request->consultation_form_when : 0; 
			$CONSULTATION_FORM_SIGNATURE = ($request->consultation_form_is_signature) ? 1 : 0; 
			$CONSULTATION_FORM_SERVICES  = ($request->selectedServices) ? json_encode(explode(",",$request->selectedServices)) : ''; 
			
			$createConsultationForm = ConsForm::create([
				"user_id"      => $AdminId,
				"name"         => $CONSULTATION_FORM_NAME,
				"send_request" => $CONSULTATION_FORM_TYPE,
				"complete"     => $CONSULTATION_FORM_WHEN,
				"status"       => 0,
				"signature"    => $CONSULTATION_FORM_SIGNATURE,
				"service_id"   => $CONSULTATION_FORM_SERVICES,
				"created_at"   => date("Y-m-d H:i:s")
			]);
			
			$FORMID = $createConsultationForm->id;
			$STATUS = 0;
			
			if(!empty($SECTION_ID)){
				foreach($SECTION_ID as $keyField => $SECTIONIDS){
					
					if($FORM_TYPE[$keyField] != '' && $FORM_TYPE[$keyField] == 'client_form') 
					{
						$createConsultationFormClientDetails = conFormClientDetails::create([
							"user_id"       => $AdminId,
							"form_id"       => $FORMID,
							"section_id"    => $SECTIONIDS,
							"section_title" => $clientFormTitle,
							"section_des"   => $clientFormDescr,
							"first_name"    => $isFirstName,
							"last_name"     => $isLastName,
							"email"         => $isEmail,
							"birthday"      => $isBirthday,
							"country_code"  => $CountryCode,
							"mobile"        => $isMobile,
							"gender"        => $isGender,
							"address"       => $isAddress,
							"created_at"    => date("Y-m-d H:i:s")
						]);
						
						$CLIENT_FORMID = $createConsultationFormClientDetails->id;
						
						if($CLIENT_FORMID){
							$STATUS = 1;
						}
					} 
					else if($FORM_TYPE[$keyField] != '' && $FORM_TYPE[$keyField] == 'custom_form')
					{	
						if(!empty($QUESTION_TYPE[$SECTIONIDS])) 
						{
							foreach($QUESTION_TYPE[$SECTIONIDS] as $QuestionKey => $QuestionType) 
							{
								$UNIQUE   = $subUniqueID[$SECTIONIDS][$QuestionKey];
								$REQUIRED = $IS_REQUIRED_VAL[$SECTIONIDS][$QuestionKey];
								
								$ANS1 = '';
								$ANS2 = '';
								$ANS3 = '';
								if($QuestionType == 'singleAnswer'){
									$ANS3 = (!empty($answerField[$SECTIONIDS][$UNIQUE])) ? json_encode($answerField[$SECTIONIDS][$UNIQUE]) : '';
								}
								$ANS4 = '';
								$ANS5 = '';
								if($QuestionType == 'multipleCheckbox'){
									$ANS5 = (!empty($answerField[$SECTIONIDS][$UNIQUE])) ? json_encode($answerField[$SECTIONIDS][$UNIQUE]) : '';
								}
								$ANS6 = '';
								if($QuestionType == 'dropdown'){
									$ANS6 = (!empty($answerField[$SECTIONIDS][$UNIQUE])) ? json_encode($answerField[$SECTIONIDS][$UNIQUE]) : '';
								}
								$ANS7 = '';
								$ANS8 = '';
								if($QuestionType == 'des'){
									$ANS8 = (!empty($answerField[$SECTIONIDS][$UNIQUE])) ? $answerField[$SECTIONIDS][$UNIQUE][0] : '';
								}
								
								$conFormCustomSection = conFormCustomSection::create([
									"user_id"     => $AdminId,
									"form_id"     => $FORMID,
									"section_id"  => $SECTIONIDS,
									"title"       => $CUSTOM_SECTION_TITLE[$SECTIONIDS],
									"des"         => $CUSTOM_SECTION_DESCR[$SECTIONIDS],
									"question"    => $QUESTION[$SECTIONIDS][$QuestionKey],
									"required"    => $REQUIRED,
									"answer_type" => $QuestionType,
									"1ans"        => $ANS1,
									"2ans"        => $ANS2,
									"3ans"        => $ANS3,
									"4ans"        => $ANS4,
									"5ans"        => $ANS5,
									"6ans"        => $ANS6,
									"7ans"        => $ANS7,
									"8ans"        => $ANS8,
									"created_at"  => date("Y-m-d H:i:s")
								]);	
								
								$CLIENT_FORM_SECTIONID = $conFormCustomSection->id;
						
								if($CLIENT_FORM_SECTIONID){
									$STATUS = 1;
								}
							}
						}
					}
				}
			}
			
			if($STATUS == 1){
				Session::flash('message', 'Consultation form has been created succesfully.');
				$data["status"]   = true;
				$data["message"]  = "Consultation form has been created succesfully.";	
				$data["redirect"] = route('showconForm');	
			} else {
				Session::flash('error', 'Something went wrong!');
				$data["status"] = false;
				$data["message"] = "Something went wrong!";	
			}
			return JsonReturn::success($data);
		}
	}
	
	function updateConsultationFormDetails(Request $request)
	{
		if ($request->ajax()) 
        {
			$CurrentUser = auth::user();
			$is_admin = $CurrentUser->is_admin;
			$updateformId = $request->updateform_id;

			if($is_admin == 1){
				$CurrentStaff = Staff::select('user_id')->where('staff_user_id',$CurrentUser->id)->first();
				$AdminId = $CurrentStaff->user_id;
				$UserId  = Auth::id();
			} else {
				$AdminId = Auth::id();
				$UserId  = Auth::id();
			}
			
			$TOTAL_SECTION        = ($request->total_section) ? $request->total_section : 0;
			
			// custom section related data
			$SECTION_ID           = ($request->section_id) ? $request->section_id : array();
			$FORM_TYPE            = ($request->form_type) ? $request->form_type : array();
			$CUSTOM_SECTION_TITLE = ($request->customSectionTitle) ? $request->customSectionTitle : array();
			$CUSTOM_SECTION_DESCR = ($request->customSectionDescription) ? $request->customSectionDescription : array();
			$QUESTION_TYPE        = ($request->answer_type) ? $request->answer_type : array();
			$QUESTION             = ($request->question) ? $request->question : array();
			$IS_REQUIRED          = ($request->is_required) ? $request->is_required : array();
			$IS_REQUIRED_VAL      = ($request->is_required_value) ? $request->is_required_value : array();
			$answerField          = ($request->answerField) ? $request->answerField : array();
			$subUniqueID          = ($request->subUniqueID) ? $request->subUniqueID : array();
			// return $request->answerField;
			
			// client form related data
			$clientFormTitle      = ($request->clientFromSectionTitle) ? $request->clientFromSectionTitle : '';
			$clientFormDescr      = ($request->clientFromSectionDesc) ? $request->clientFromSectionDesc : '';
			$isFirstName          = ($request->first_name) ? $request->first_name : 0;
			$isLastName           = ($request->last_name) ? $request->last_name : 0;
			$isEmail              = ($request->email) ? $request->email : 0;
			$isBirthday           = ($request->birthday) ? $request->birthday : 0;
			$CountryCode          = ($request->country_code) ? $request->country_code : 1;
			$isMobile             = ($request->mobile) ? $request->mobile : 0;
			$isGender             = ($request->gender) ? $request->gender : 0;
			$isAddress            = ($request->address) ? $request->address : 0;
			
			// consultation form realted data
			$CONSULTATION_FORM_NAME      = ($request->consultation_form_name) ? $request->consultation_form_name : ''; 
			$CONSULTATION_FORM_TYPE      = ($request->consultation_form_type) ? $request->consultation_form_type : 0; 
			$CONSULTATION_FORM_WHEN      = ($request->consultation_form_when) ? $request->consultation_form_when : 0; 
			$CONSULTATION_FORM_SIGNATURE = ($request->consultation_form_is_signature) ? 1 : 0; 
			$CONSULTATION_FORM_SERVICES  = ($request->selectedServices) ? json_encode(explode(",",$request->selectedServices)) : ''; 
			            
			$form = ConsForm::where('id',$updateformId)->first();
			$form->update([
				"user_id"      => $AdminId,
				"name"         => $CONSULTATION_FORM_NAME,
				"send_request" => $CONSULTATION_FORM_TYPE,
				"complete"     => $CONSULTATION_FORM_WHEN,
				"status"       => 0,
				"signature"    => $CONSULTATION_FORM_SIGNATURE,
				"service_id"   => $CONSULTATION_FORM_SERVICES,
				"created_at"   => date("Y-m-d H:i:s")
			]);
			
			$FORMID = $updateformId;
			$STATUS = 0;
			
			//remove
			$remove = conFormCustomSection::where('form_id',$updateformId)->get();

			// $remove->delete();
			foreach($remove as $qna)
			{
				$qna->delete();
			}
			//end
			if(!empty($SECTION_ID)){
				foreach($SECTION_ID as $keyField => $SECTIONIDS){
					
					if($FORM_TYPE[$keyField] != '' && $FORM_TYPE[$keyField] == 'client_form') 
					{                
						$client = conFormClientDetails::where('form_id',$updateformId)->first();
						if($client){
							$client->update([
								"user_id"       => $AdminId,
								"form_id"       => $FORMID,
								"section_id"    => $SECTIONIDS,
								"section_title" => $clientFormTitle,
								"section_des"   => $clientFormDescr,
								"first_name"    => $isFirstName,
								"last_name"     => $isLastName,
								"email"         => $isEmail,
								"birthday"      => $isBirthday,
								"country_code"  => $CountryCode,
								"mobile"        => $isMobile,
								"gender"        => $isGender,
								"address"       => $isAddress,
								"created_at"    => date("Y-m-d H:i:s")
							]);
						} else {
							$client = conFormClientDetails::create([
								"user_id"       => $AdminId,
								"form_id"       => $FORMID,
								"section_id"    => $SECTIONIDS,
								"section_title" => $clientFormTitle,
								"section_des"   => $clientFormDescr,
								"first_name"    => $isFirstName,
								"last_name"     => $isLastName,
								"email"         => $isEmail,
								"birthday"      => $isBirthday,
								"country_code"  => $CountryCode,
								"mobile"        => $isMobile,
								"gender"        => $isGender,
								"address"       => $isAddress,
								"created_at"    => date("Y-m-d H:i:s")
							]);
						}
						
						$CLIENT_FORMID = $client->id;
						
						if($CLIENT_FORMID){
							$STATUS = 1;
						}
					} 
					
					else if($FORM_TYPE[$keyField] != '' && $FORM_TYPE[$keyField] == 'custom_form')
					{	
						if(!empty($QUESTION_TYPE[$SECTIONIDS])) 
						{
							
							foreach($QUESTION_TYPE[$SECTIONIDS] as $QuestionKey => $QuestionType) 
							{
								$UNIQUE   = $subUniqueID[$SECTIONIDS][$QuestionKey];
								$REQUIRED = $IS_REQUIRED_VAL[$SECTIONIDS][$QuestionKey];
								
								$ANS1 = '';
								$ANS2 = '';
								$ANS3 = '';
								if($QuestionType == 'singleAnswer'){
									$ANS3 = (!empty($answerField[$SECTIONIDS][$UNIQUE])) ? json_encode($answerField[$SECTIONIDS][$UNIQUE]) : '';
								}
								$ANS4 = '';
								$ANS5 = '';
								if($QuestionType == 'multipleCheckbox'){
									$ANS5 = (!empty($answerField[$SECTIONIDS][$UNIQUE])) ? json_encode($answerField[$SECTIONIDS][$UNIQUE]) : '';
								}
								$ANS6 = '';
								if($QuestionType == 'dropdown'){
									$ANS6 = (!empty($answerField[$SECTIONIDS][$UNIQUE])) ? json_encode($answerField[$SECTIONIDS][$UNIQUE]) : '';
								}
								$ANS7 = '';
								$ANS8 = '';
								if($QuestionType == 'des'){
									// return $answerField[$SECTIONIDS][$UNIQUE][0];
									$ANS8 = (!empty($answerField[$SECTIONIDS][$UNIQUE])) ? $answerField[$SECTIONIDS][$UNIQUE][0] : '';
								}
								
								$conFormCustomSection = conFormCustomSection::create([
									"user_id"     => $AdminId,
									"form_id"     => $FORMID,
									"section_id"  => $SECTIONIDS,
									"title"       => $CUSTOM_SECTION_TITLE[$SECTIONIDS],
									"des"         => $CUSTOM_SECTION_DESCR[$SECTIONIDS],
									"question"    => $QUESTION[$SECTIONIDS][$QuestionKey],
									"required"    => $REQUIRED,
									"answer_type" => $QuestionType,
									"1ans"        => $ANS1,
									"2ans"        => $ANS2,
									"3ans"        => $ANS3,
									"4ans"        => $ANS4,
									"5ans"        => $ANS5,
									"6ans"        => $ANS6,
									"7ans"        => $ANS7,
									"8ans"        => $ANS8,
									"created_at"  => date("Y-m-d H:i:s")
								]);	
								
								$CLIENT_FORM_SECTIONID = $conFormCustomSection->id;
						
								if($CLIENT_FORM_SECTIONID){
									$STATUS = 1;
								}
							}
						}
					}
				}
			}
			
			if($STATUS == 1){
				Session::flash('message', 'Consultation form has been update succesfully.');
				$data["status"]   = true;
				$data["message"]  = "Consultation form has been update succesfully.";	
				$data["redirect"] = route('showconForm');	
			} else {
				Session::flash('error', 'Something went wrong!');
				$data["status"] = false;
				$data["message"] = "Something went wrong!";	
			}
			return JsonReturn::success($data);
		}
	}

	function editConsultationForm($id = null)
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
		
		$Services = ServiceCategory::with('service.servicePrice')->where('is_deleted',0)->where('user_id',$AdminId)->get()->toArray();
		
		$ConsForm             = ConsForm::select('*')->where('id',$id)->first();
		$conFormClientDetails = conFormClientDetails::select('*')->where('form_id',$id)->first();
		$conFormCustomSection = conFormCustomSection::select('*')->where('form_id',$id)->get()->toArray();
		
		$TotalSections = conFormCustomSection::select('section_id')->where('form_id',$id)->distinct()->get('section_id')->toArray();
		
		$ClientConsultationFormGet = ClientConsultationForm::select('*')->with('clientConsultationFields')->where('id',$id)->get()->first()->toArray();
		
		$COUNTSECTION = count($TotalSections);
		
		if(!empty($conFormClientDetails)){
			$COUNTSECTION = $COUNTSECTION + 1;	
		}
		
		$FORM_SEQUANCE = array();
		
		if($COUNTSECTION > 0)
		{
			for($i = 1; $i <= $COUNTSECTION; $i++) 
			{	
				$conFormCustomSection = conFormCustomSection::select('*')->where('form_id',$id)->get()->toArray();
				$conFormClientDetails = conFormClientDetails::select('*')->where('form_id',$id)->first();
				
				if(!empty($conFormCustomSection))
				{	
					$UNIQUE_ID = $this->unique_code(30);
					
					$tempSectionData['uniqueid']                 = $UNIQUE_ID;
					$tempSectionData['section_id']               = $conFormCustomSection[0]['section_id'];
					$tempSectionData['form_type']                = 'custom_form';
					$tempSectionData['customSectionTitle']       = $conFormCustomSection[0]['title'];
					$tempSectionData['customSectionDescription'] = $conFormCustomSection[0]['des'];
					
					$custom_fields = array();
					
					foreach($conFormCustomSection as $conFormCustomSectionData) 
					{	
						$SUB_UNIQUE_ID = $this->unique_code(30);
						$tempCustomFields['subuniqueid'] = $SUB_UNIQUE_ID;
						$tempCustomFields['answer_type'] = $conFormCustomSectionData['answer_type'];
						$tempCustomFields['question']    = $conFormCustomSectionData['question'];
						$tempCustomFields['is_required'] = $conFormCustomSectionData['required'];
						$tempCustomFields['1ans']        = $conFormCustomSectionData['1ans'];
						$tempCustomFields['2ans']        = $conFormCustomSectionData['2ans'];
						$tempCustomFields['3ans']        = $conFormCustomSectionData['3ans'];
						$tempCustomFields['4ans']        = $conFormCustomSectionData['4ans'];
						$tempCustomFields['5ans']        = $conFormCustomSectionData['5ans'];
						$tempCustomFields['6ans']        = $conFormCustomSectionData['6ans'];
						$tempCustomFields['7ans']        = $conFormCustomSectionData['7ans'];
						$tempCustomFields['8ans']        = $conFormCustomSectionData['8ans'];
						array_push($custom_fields,$tempCustomFields);
					}
					
					$tempSectionData['custom_fields'] = $custom_fields;
					array_push($FORM_SEQUANCE,$tempSectionData);
					
				} else if(!empty($conFormClientDetails)) {
					
				}
			}
		}
		$formid = $id;
		return view('conForm.editConsultationForm',compact('formid','ConsForm','conFormClientDetails','conFormCustomSection','Services'));
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
	
	function hoursandmins($time, $format = '%02d:%02d')
	{
		if ($time < 1) {
			return;
		}
		$hours = floor($time / 60);
		$minutes = ($time % 60);
		
		$returnText = '';
		if($hours == 0){
			$returnText = $minutes.'min';
		} else {
			$returnText = $hours.'h '.$minutes.'min';
		}
		
		return $returnText;
	}
}

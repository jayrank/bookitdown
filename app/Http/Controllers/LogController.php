<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Sms_log;
use App\Models\Smslog;
use App\Models\EmailLog;
use App\Models\smartCampaign;
use App\Models\Group_email_blast;
use App\Models\Group_sms_blast;
use App\Mail\TestOrder;

use Mail;

class LogController extends Controller
{
	public function Telnyx_log()
	{
		header('Content-Type: application/json');
		$request = file_get_contents('php://input');
		$response = json_decode($request);
		
		$SMS_ID     = ($response->data->payload->id) ? $response->data->payload->id : '';
		$sms_status = ($response->data->payload->to[0]->status) ? $response->data->payload->to[0]->status : '';
		
		$getSMSLog = Sms_log::where('sms_id',$SMS_ID)->get()->first()->toArray();
		
		if(!empty($getSMSLog)) {
			
			// update group sms blast record
			$group_sms_blast_id = ($getSMSLog['group_sms_blast_id']) ? $getSMSLog['group_sms_blast_id'] : 0;
			if($group_sms_blast_id != '' && $group_sms_blast_id != 0){
				
				$Group_sms_blast = Group_sms_blast::where('id',$group_sms_blast_id)->get()->first()->toArray();				
				
				if(!empty($Group_sms_blast)) {
					if($sms_status == 'delivered' || $sms_status == 'sent'){
						$updateGroupSMSBlast['is_delivered'] = 1;
					}	
					$updateGroupSMSBlast['updated_at'] = date("Y-m-d H:i:s");
					Group_sms_blast::where('id', $group_sms_blast_id)->update($updateGroupSMSBlast);			
				}
			}
			
			// update sms response 
			$updateLog['sms_status'] = $sms_status;
			$updateLog['sms_response'] = $request;
			Sms_log::where('sms_id', $SMS_ID)->update($updateLog);	
		}
	}
	
	public function sendTestEmail(){
		
		$FROM_EMAIL     = env("MAIL_FROM_ADDRESS", "info@ikotel.ca");
		$FROM_NAME      = 'Test Email';
		$TO_EMAIL       = 'tjcloudtest@gmail.com';
		$CC_EMAIL       = 'tjcloudtest2@gmail.com';
		$SUBJECT        = 'Test Email';
		$MESSAGE        = 'Hi  Please see attached purchase order Have a great day! ';
		$OrderId        = 0;
		
		$UniqueId       = $this->unique_code(30).time();
		
		$SendMail = Mail::to($TO_EMAIL)->cc([$CC_EMAIL])->send(new TestOrder($FROM_EMAIL,$FROM_NAME,$SUBJECT,$MESSAGE,$OrderId,$UniqueId));	
		
		EmailLog::create([
			'unique_id' => $UniqueId,
		]);
	}
	
	public function emailResponseLog()
	{
		header('Content-Type: application/json');
		$request = file_get_contents('php://input');
		$response = json_decode($request,true);
		
		if(!empty($response))
		{
			$email_id = $response[0]['X-APIHEADER'];
			$Event    = $response[0]['EVENT'];
			$TRANSID    = $response[0]['TRANSID'];
		
			if($Event == 'sent' || $Event == 'opened'){
				$updateLog['is_sent']      = 1;
			}
			
			if($email_id != ''){
				$getEmailLogData = EmailLog::where('unique_id',$email_id)->get()->first()->toArray();
				
				if(!empty($getEmailLogData)){
					$group_email_blast_id = $getEmailLogData['group_email_blast_id'];
					
					if($group_email_blast_id != '' && $group_email_blast_id != 0){
						$Group_email_blast = Group_email_blast::where('id',$group_email_blast_id)->get()->first()->toArray();
						
						if(!empty($Group_email_blast)){
							if($Group_email_blast['is_sent'] == 0 && $Event == 'sent'){
								$updateGroupEmailBlast['is_sent'] = 1;
							}
							if($Group_email_blast['is_delivered'] == 0 && ($Event == 'delivered' || $Event == 'sent')){
								$updateGroupEmailBlast['is_delivered'] = 1;
							}
							if($Group_email_blast['is_opened'] == 0 && ($Event == 'opened' || $Event == 'open')){
								$updateGroupEmailBlast['is_opened'] = 1;
							}
							if($Group_email_blast['is_clicked'] == 0 && ($Event == 'clicked' || $Event == 'click')){
								$updateGroupEmailBlast['is_clicked'] = 1;
							}
							$updateGroupEmailBlast['updated_at'] = date("Y-m-d H:i:s");	
							Group_email_blast::where('id',$group_email_blast_id)->update($updateGroupEmailBlast);
						}
					}
				}
				
				$updateLog['trans_id']     = $TRANSID;
				$updateLog['email_status'] = $Event;
				$updateLog['response']     = $request;
				$updateLog['updated_at']   = date('Y-m-d H:i:s');
				
				EmailLog::where('unique_id', $email_id)
						->update($updateLog);
			} else {
				EmailLog::create([
					'unique_id'    => $email_id,
					'email_status' => $Event,
					'response'     => $request,
				]);	
			}
		}
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
}
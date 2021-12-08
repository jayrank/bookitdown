<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\ReminderNotification;
use App\Models\AppointmentServices;
use App\Models\Clients;
use App\Models\ServicesPrice;
use App\Models\Services;
use App\Models\AccountSetting;
use App\Models\Sms_log;
use App\Notification\TelnyxSms;
use Carbon\Carbon;
use date;
use Mail;
use App\Mail\ReminderNoti;
use App\Models\Appointments;
use App\Models\EmailLog;


class ReminderNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminder:notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Reminder Appointment Notification.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $users = User::where('is_admin',0)->get();

        foreach ($users as $user) {
            $as = AccountSetting::where('user_id',$user->id)->first();
            
            if($as){
                $timezone = $as->timezone;
            } else {
                $timezone = 'Asia/Kolkata';
            }
            $dt = Carbon::now();
            $dt->setTimezone($timezone);
            $time = $dt->toTimeString();

            $remidernoti = ReminderNotification::where('user_id',$user->id)->first();
            $adnotifi = $remidernoti->advance_notice;

            $appoint = AppointmentServices::where('user_id',$user->id)->get();
            foreach($appoint as $aptime){
                $apptime = $aptime->start_time;
                $appdate = $aptime->appointment_date;
                $appdatetime = date('Y-m-d H:i:s', strtotime("$appdate $apptime"));
                $apptimeget = $appdatetime->toDateTimeString();
                $thistime = $apptimeget->subHours($adnotifi);

                if ($dt==$thistime) {
                    $clientid = $aptime->client_id;

                    $client = Clients::where('id',$clientid)->first();
                    $serPrice = ServicesPrice::where('id',$aptime->service_price_id)->first();
                    $service = Services::where('id',$serPrice->service_id)->first();
                    $getappoint = Appointments::where('id',$aptime->appointment_id)->first();

                    if($client){
                        $email = $client->email;
                        $mobile = $client->tel_country_code.' '.$client->telephoneno;

                        if($email != ''){
                            $FROM_EMAIL     = env("MAIL_FROM_ADDRESS", "info@ikotel.ca");
                            $FROM_NAME      = 'Appointment Email';
                            $TO_EMAIL       = $email;
                            $CC_EMAIL       = 'tjcloudtest2@gmail.com';
                            $SUBJECT        = 'Appointment';
                            $Appointment    = $aptime;
                            
                            $UniqueId       = $this->unique_code(30).time();
                            
                            $SendMail = Mail::to($TO_EMAIL)->cc([$CC_EMAIL])->send(new ReminderNoti($FROM_EMAIL,$FROM_NAME,$SUBJECT,$Appointment,$getappoint,$UniqueId,$client,$service,$remidernoti));	
                            
                            EmailLog::create([
                                'user_id' => $user->id,
                                'client_id' => $clientid,
                                'appointment_id' => $getappoint->id,
                                'unique_id' => $UniqueId,
                                'from_email' => $FROM_EMAIL,
                                'to_email' => $TO_EMAIL,
                                'module_type_text' => 'Reminder Appointment Email',
                                'created_at'       => date("Y-m-d H:i:s")
                            ]);
                        }
                        if($mobile != ''){
                            //sms
                            $api_key = 'KEY0170163F410B3D73F3E24ACA4ED6A1AF_Vv9GsxS3SC7HWxzwhsQo1z';
                            $smsResponse = TelnyxSms::sendTelnyxSms($mobile,'12897973720','Gym area now accepts online bookings! Check out our service menu and book your next appointment now.');        
                            
                            if(isset($smsResponse->errors)){
    							$sms_status = 'failed';	
    							$smsid = "";
    							$error_message = $smsResponse->errors[0]->detail;
    						} 
    						else
    						{
    							$error_message = "";
    							$sms_status = 'send';
    							$smsid = $smsResponse->data->id;
    						}
                            $sms_log = Sms_log::create([
                                'user_id'          => $user->id,
                                'client_id'        => $client->id,
                                'appointment_id'   => $getappoint->id,
                                'sms_id'           => $smsid,
                                'send_from'        => '12897973720',
                                'send_to'          => $mobile,
                                'client_name'      => $client->firstname.' '.$client->lastname,
                                'message'          => 'Gym area now accepts online bookings! Check out our service menu and book your next appointment now.',
                                'direction'        => 'outbox',
                                'sms_status'       => $sms_status,
                                'error_message'    => $error_message,
                                'module_type'      => '1',
                                'module_type_text' => 'Reminder Appointment',
                                'sms_response'     => json_encode($smsResponse),
                                'created_at'       => date("Y-m-d H:i:s")
                            ]);
                        }
                    }
                }
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

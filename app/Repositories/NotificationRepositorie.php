<?php

namespace App\Repositories;

use Illuminate\Support\Carbon;
use App\Models\Notifications;
use App\Models\NotificationSettings;
use App\Models\NotificationSettingsLocations;
use App\Models\User;
use App\Models\Staff;
use Illuminate\Http\Request;

/**
* Class NotificationRepositorie
*/

class NotificationRepositorie
{

    public function __construct() {

    }

    /**
    * @return array
    *  store notification
    */
    public function storeNotification($notificationData = array()) {


        if(!empty($notificationData)) {

            # Current Timestamp
            $currentDateTime = Carbon::now();
            $currentDateTime = $currentDateTime->toDateTimeString();

            $notificationData['created_at'] = $currentDateTime;
            $notificationData['updated_at'] = $currentDateTime;
            $notificationData['is_active']  = 1;

            $notificationData = Notifications::insert($notificationData);
        }

    }


    /**
    * @return array
    *  get notification
    */
    public function getNotification(Request $request, $UserId = '') {

        $where = ['is_active' => 1];

        if(!empty($UserId)) {
            $where['user_id'] = $UserId;
        }
        $notificationData = Notifications::orderBy('id','DESC')
                            ->where($where)
                            ->paginate(15);

        $notificationTemplateContain = view('layouts.notificationQuickPanel', compact('notificationData'))->render();

        return $notificationTemplateContain;
    }

    public function sendNotification($userId, $clientId, $type, $typeId, $title, $description, $locationId, $notification_type, $send_to_current_user = true) {
        if(!empty($userId)) {
            $userRow = User::where(['id' => $userId])->first();

            if(!empty($userRow)) {
                $is_admin = $userRow->is_admin;

                # Current Timestamp
                $currentDateTime = Carbon::now();
                $currentDateTime = $currentDateTime->toDateTimeString();

                $staffUserId = [$userId];
                if($is_admin == 1){
                    $staffRow = Staff::select('user_id')->where('staff_user_id',$userRow->id)->first();
                    $AdminId = !empty($staffRow) ? $staffRow->user_id : NULL;

                    if(!empty($staffRow)) {
                        $staffUserId = Staff::where('user_id', $AdminId)->pluck('staff_user_id')->toArray();
                        $staffUserId[] = $AdminId;
                    }
                } else {
                    $AdminId = $userId;
                    $staffUserId = Staff::where('user_id', $AdminId)->pluck('staff_user_id')->toArray();
                    $staffUserId[] = $AdminId;
                }
                $staffUserId = array_unique($staffUserId);

                $insertArray = [];
                if(!empty($staffUserId)) {
                    foreach($staffUserId as $key => $value) {
                        $userNotificationSettings = NotificationSettings::where(['user_id' => $value, 'deleted_at' => NULL])->first();

                        if(!empty($userNotificationSettings)) {

                            if($notification_type == 'new_appointment' && $userNotificationSettings->new_appointments == 1) {
                                $sendNotification = true;
                            } elseif($notification_type == 'reschedules' && $userNotificationSettings->reschedules == 1) {
                                $sendNotification = true;
                            } elseif($notification_type == 'cancellations' && $userNotificationSettings->cancellations == 1) {
                                $sendNotification = true;
                            } elseif($notification_type == 'no_shows' && $userNotificationSettings->no_shows == 1) {
                                $sendNotification = true;
                            } elseif($notification_type == 'confirmations' && $userNotificationSettings->confirmations == 1) {
                                $sendNotification = true;
                            } elseif($notification_type == 'arrivals' && $userNotificationSettings->arrivals == 1) {
                                $sendNotification = true;
                            } elseif($notification_type == 'started' && $userNotificationSettings->started == 1) {
                                $sendNotification = true;
                            } elseif($notification_type == 'tips' && $userNotificationSettings->tips == 1) {
                                $sendNotification = true;
                            } else {
                                $sendNotification = false;
                            }

                            if($sendNotification) {
                                if($value == $userId) {
                                    if($send_to_current_user) {
                                        $insertArray[] = [
                                            'user_id'           => $value,
                                            'client_id'         => $clientId,
                                            'type'              => $type,
                                            'type_id'           => $typeId,
                                            'title'             => $title,
                                            'description'       => $description,
                                            'created_at'        => $currentDateTime,
                                            'updated_at'        => $currentDateTime,
                                            'is_active'         => 1
                                        ];
                                    }
                                } elseif($userNotificationSettings->notify_about == 'all_staff') {

                                    $notificationLocationIds = NotificationSettingsLocations::where('notification_settings_id', $userNotificationSettings->id)->pluck('location_id')->toArray();

                                    if(in_array($locationId, $notificationLocationIds)) {
                                        $insertArray[] = [
                                            'user_id'           => $value,
                                            'client_id'         => $clientId,
                                            'type'              => $type,
                                            'type_id'           => $typeId,
                                            'title'             => $title,
                                            'description'       => $description,
                                            'created_at'        => $currentDateTime,
                                            'updated_at'        => $currentDateTime,
                                            'is_active'         => 1
                                        ];
                                    }
                                }
                            }
                        }
                    }
                }

                $notificationData = Notifications::insert($insertArray);
            }
        }
    }

    public function markRead($userId)
    {
        Notifications::where(['user_id' => $userId, 'is_read' => 0])
                        ->update([
                            'is_read' => 1
                        ]);
    }

    public function countUnreadNotification($userId)
    {
        return Notifications::where(['user_id' => $userId, 'is_read' => 0])->count();
    }


}
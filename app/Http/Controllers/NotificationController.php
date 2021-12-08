<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Notifications;
use App\Models\NotificationSettings;
use App\Models\NotificationSettingsLocations;
use App\Models\User;
use App\Models\Location;
use App\JsonReturn;
use Carbon\Carbon;
use App\Repositories\NotificationRepositorie;

class NotificationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    /**
     * @var notificationRepositorie
     */
    private $notificationRepositorie;

    public function __construct(
        NotificationRepositorie $notificationRepositorie
    )
    {
        $this->middleware('auth');
        $this->notificationRepositorie = $notificationRepositorie;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getSidebarNotification(Request $request)
    {
        $UserId  = Auth::id();
        $data = [];
        $data['notificationData'] = $this->notificationRepositorie->getNotification($request, $UserId);
        $data['notificationCount'] = $this->notificationRepositorie->countUnreadNotification($UserId);
        return JsonReturn::success($data);
    }

    public function notificationMarkRead(Request $request)
    {
        $UserId  = Auth::id();
        $this->notificationRepositorie->markRead($UserId);
    }

    public function userNotificationSettings()
    {
        $UserId = Auth::id();
        $location = Location::where('deleted_at', NULL)->get();
        $userNotificationSettings = NotificationSettings::where(['user_id' => $UserId, 'deleted_at' => NULL])->first();

        if(!empty($userNotificationSettings)) {
            $notificationSettingsLocations = NotificationSettingsLocations::where(['notification_settings_id' => $userNotificationSettings->id])
                                            ->leftjoin('locations', 'locations.id', 'notification_settings_locations.location_id')
                                            ->select('notification_settings_locations.*', 'locations.location_name')
                                            ->get();
        } else {
            $notificationSettingsLocations = new \stdClass();
        }

        return view('notifications.notification_settings', compact('location', 'userNotificationSettings', 'notificationSettingsLocations'));
    }

    public function storeUserNotificationSettings(Request $request)
    {
        $UserId = Auth::id();

        $userNotificationSettings = NotificationSettings::where(['user_id' => $UserId, 'deleted_at' => NULL])->first();

        $notify_about = !empty($request->notify_about) ? $request->notify_about : 'only_me';

        $notification_settings_array = [
            'user_id' => $UserId,
            'notify_about' => $notify_about,
            'new_appointments' => isset($request->new_appointments) ? $request->new_appointments : 0,
            'reschedules' => isset($request->reschedules) ? $request->reschedules : 0,
            'cancellations' => isset($request->cancellations) ? $request->cancellations : 0,
            'no_shows' => isset($request->no_shows) ? $request->no_shows : 0,
            'confirmations' => isset($request->confirmations) ? $request->confirmations : 0,
            'arrivals' => isset($request->arrivals) ? $request->arrivals : 0,
            'started' => isset($request->started) ? $request->started : 0,
            'tips' => isset($request->tips) ? $request->tips : 0,
        ];

        if(!empty($userNotificationSettings)) {
            NotificationSettings::where('id', $userNotificationSettings->id)
                        ->update($notification_settings_array);

            NotificationSettingsLocations::where(['notification_settings_id' => $userNotificationSettings->id])->delete();

        } else {
            $notification_settings_array['created_at'] = date('Y-m-d H:i:s');
            $userNotificationSettings = NotificationSettings::insert($notification_settings_array);
        }

        if($notify_about == 'all_staff' && !empty($request->location) && is_array($request->location)) {
            $insert_array = [];

            foreach($request->location as $key => $value) {
                $insert_array[] = [
                    'notification_settings_id' => $userNotificationSettings->id,
                    'location_id' => $value,
                    'created_at' => date('Y-m-d H:i:s')
                ];
            }

            NotificationSettingsLocations::insert($insert_array);
        }

        $data["status"] = true;
        $data["msg"] = 'Notification settings updated successfully.';
        return JsonReturn::success($data);
    }
}

<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function readNotif(Request $request){
        // Log::info($request->id);
        $notif = Notification::find($request->id);
        // Log::info($notif->id);
        $notif->update(["hasRead"=>true]);   
    }

    public function getNotifs(Request $request){
        // Log::info($request->all());
        $notifs = Notification::where('recipient',$request->name)
                    ->orderBy("created_at","DESC");
        // Log::info($notifs->get());
        return response()->json($notifs->get());
    }
}

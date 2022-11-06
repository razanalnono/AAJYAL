<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Trainee;
use App\Models\Trainer;
use App\Notifications\NormalNotification;

use App\Models\GlobalNotification;
use App\Models\Group;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GlobalNotificationController extends Controller
{
    public function send(Request $request)
    { 
        $notification           = new GlobalNotification();
        $notification->title    = $request->title;
        $notification->message  = $request->message;
        $notification->admin_id = auth()->user()->id ?? 0;
        $notification->type      = $request->type;
        $notification->save();


        $message = [
            'title' => $request->title,
            "message" => $request->message,
        ];

        if ($request->type == 'trainee') {

            $trainees =  Trainee::all();
            foreach ($trainees as $trainee) {
                $trainee->notify(new NormalNotification($message));
            }
        }
        if ($request->type == 'trainer') {

            $trainers =  Trainer::all();
            foreach ($trainers as $trainer) {
                $trainer->notify(new NormalNotification($message));
            }
        }

        $group_id = $request->group_id;
        if ($request->type == 'group') {
            $test = Trainee::when($group_id, function ($query, $group_id) {
                $query->whereHas('groups', function ($query) use ($group_id) {
                    $query->where('id', $group_id);
                });
            })->get();
            foreach ($test as $trainee) {
                $trainee->notify(new NormalNotification($message));
            }
        }

        if ($request->type == 'other') {
            if ($request->trainer_id) {
                $trainer = Trainer::find($request->trainer_id);
                $trainer->notify(new NormalNotification($message));
            } else {
                $trainee = Trainee::find($request->trainee_id);
                $trainee->notify(new NormalNotification($message));
            }
        }


        return response()->json([
            'status' => 200,
            'message' => 'تم ارسال الاشعار بنجاح',
            'data' => $notification,
        ]);
    }
}
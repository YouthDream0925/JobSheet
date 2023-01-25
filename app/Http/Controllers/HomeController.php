<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Notification;
use App\Notifications\SendPushNotification;
use App\Models\JobCard;
use App\Models\Task;
use App\Models\SubTask;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        $wps = JobCard::all();
        $my_wps = [];
        foreach($wps as $key => $wp) {
            foreach($wp->assign() as $key => $assign) {
                if($assign == $user->email) {
                    array_push($my_wps, $wp);
                }
            }
        }
        array_unique($my_wps);

        $tasks = Task::all();
        $my_tasks = [];
        foreach($tasks as $key => $task) {
            foreach($task->assign() as $key => $assign) {
                if($assign == $user->email) {
                    array_push($my_tasks, $task);
                }
            }
        }
        array_unique($my_tasks);

        $subtasks = SubTask::all();
        $my_subtasks = [];
        foreach($subtasks as $key => $subtask) {
            foreach($subtask->assign() as $key => $assign) {
                if($assign == $user->email) {
                    array_push($my_subtasks, $task);
                }
            }
        }
        array_unique($my_subtasks);

        $total = count($my_wps) + count($my_tasks) + count($my_subtasks);
        return view('home', compact('user', 'my_wps', 'my_tasks', 'my_subtasks', 'total'));
    }

    public function updateToken(Request $request)
    {
        try{
            $request->user()->update(['fcm_token'=>$request->token]);
            return response()->json([
                'success'=>true
            ]);
        }catch(\Exception $e){
            report($e);
            return response()->json([
                'success'=>false
            ],500);
        }
    }

    public function notification(Request $request)
    {
        $request->validate([
            'title'=>'required',
            'message'=>'required'
        ]);
    
        try{
            $fcmTokens = User::whereNotNull('fcm_token')->pluck('fcm_token')->toArray();
    
            //Notification::send(null,new SendPushNotification($request->title,$request->message,$fcmTokens));
    
            /* or */
    
            //auth()->user()->notify(new SendPushNotification($title,$message,$fcmTokens));
    
            /* or */
    
            Larafirebase::withTitle($request->title)
                ->withBody($request->message)
                ->sendMessage($fcmTokens);
    
            return redirect()->back()->with('success','Notification Sent Successfully!!');
    
        }catch(\Exception $e){
            report($e);
            return redirect()->back()->with('error','Something goes wrong while sending notification.');
        }
    }
}

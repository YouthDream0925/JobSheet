<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Message;

class MessageController extends Controller
{
    public function index(Request $request, $id = null)
    {
        $string = 'The lazy fox jumped over the fence';

        // if (str_contains($string, 'lazy')) {
        //     echo "The string 'lazy' was found in the string\n";
        // }

        // if (str_contains($string, 'Lazy')) {
        //     echo 'The string "Lazy" was found in the string';
        // } else {
        //     echo '"Lazy" was not found because the case does not match';
        // }

        // die();
        
        $user = Auth::user();
        $emails = User::pluck('email','email')->all();

        $notifications = [];
        foreach($user->notifications as $key => $notification) {
            array_push($notifications, $notification);
        }

        if($request->input('search')) {
            foreach($notifications as $key => $notification) {
                if(str_contains(strtolower($notification->data['title']), strtolower($request->input('search'))) == false && str_contains(strtolower($notification->data['message']), strtolower($request->input('search'))) == false) {
                    unset($notification[$key]);
                    array_splice($notifications, $notification[$key]);
                }
            }
        }

        $selected_notification = null;
        foreach($notifications as $key => $notification)
        {
            if($notification->id == $id) {
                $selected_notification = $notification;
                $selected_notification->markAsRead();
            }
        }
        
        return view('messages.index', compact('user', 'notifications', 'selected_notification', 'emails'));
    }

    public function delete($id)
    {
        $user = Auth::user();
        $selected_notification = null;

        $notifications = $user->notifications;
        foreach($notifications as $key => $notification)
        {
            if($notification->id == $id) {
                $selected_notification = $notification;
                $selected_notification->delete();
            }
        }

        return redirect()->back();
    }

    public function send(Request $request)
    {
        $user = Auth::user();
        $emailTo = $request->email;
        $message = $request->new_message;

        Message::create([
            'from' => $user->email,
            'to' => $emailTo,
            'content' => $message
        ]);
        return redirect()->route('messages.index')->with('success', 'Message sended successfully');
    }
}

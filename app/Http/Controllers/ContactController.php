<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Mail;
use App\Mail\SendMail;
use Plank\Mediable\Facades\ImageManipulator;
use Plank\Mediable\HandlesMediaUploadExceptions;
use Plank\Mediable\Facades\MediaUploader;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('contact.index', compact('user'));
    }

    public function send(Request $request)
    {
        $user = Auth::user();

        $this->validate($request, [
            'problem' => ['required', 'string', 'max:255'],
            'problem_screen' => ['required', 'file'],
        ]);

        $file = '';
        try {
            if($request->file('problem_screen') != null) {
                if($user->hasMedia('screen')) {
                    $media = $user->getMedia('screen')[0];
                    $media->forceDelete();
                }

                $media = MediaUploader::fromSource($request->file('problem_screen'))
                    ->toDirectory('screen')
                    ->upload();
                
                $file = $media->filename . '.' . $media->extension;
                $user->attachMedia($media, 'screen');
            }
        } catch (MediaUploadException $e) {
            throw $this->transformMediaUploadException($e);
        }

        $files = [
            storage_path('app/screen/' . $file),
        ];

        $data["email"] = "big.dreamwork999@gmail.com";
        $data["title"] = "Please help me";
        $data["body"] = $request->problem;
 
        Mail::send('emails.contact', $data, function($message)use($data, $files) {
            $message->to($data["email"], $data["email"])
                    ->subject($data["title"]);
            
            foreach ($files as $file){
                $message->attach($file);
            }
            
        });

        return redirect()->route('contact')->with('success', trans('global.mailSendedSuccessfully'));
    }
}
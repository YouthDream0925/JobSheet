<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Plank\Mediable\Facades\ImageManipulator;
use Plank\Mediable\HandlesMediaUploadExceptions;
use Plank\Mediable\Facades\MediaUploader;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    use HandlesMediaUploadExceptions;
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user_id = $request->id;
        $user = User::find($user_id);

        if($user == null)
            $user = Auth::user();

        return view('profile.index', compact('user'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */    
    public function store(Request $request)
    {
        $this->validate($request, [
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
        ]);

        $input['firstname'] = $request->firstname;
        $input['lastname'] = $request->lastname;
        $input['email'] = $request->email;
        $input['address'] = $request->address;
        $input['phone'] = $request->phone;
        $input['about'] = $request->about;

        $user = Auth::user();
        try {
            if($request->file('user_avatar') != null) {
                if($user->hasMedia('avatar')) {
                    $media = $user->getMedia('avatar')[0];
                    $media->forceDelete();
                }

                $media = MediaUploader::fromSource($request->file('user_avatar'))
                    ->toDirectory('avatar')
                    ->upload();
                    
                $user->attachMedia($media, 'avatar');
            }
        } catch (MediaUploadException $e) {
            throw $this->transformMediaUploadException($e);
        }

        $user->update($input);
        return view('profile.index', compact('user'));
    }
}

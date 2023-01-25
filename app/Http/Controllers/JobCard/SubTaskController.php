<?php

namespace App\Http\Controllers\JobCard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Task;
use App\Models\SubTask;
use App\Models\Comment;
use App\Models\Description;
use Illuminate\Support\Facades\Auth;
use Mail;
use App\Mail\SendMail;
use Notification;
use App\Notifications\SendPushNotification;
use App\Exports\SubTasksExport;
use App\Imports\SubTasksImport;
use Maatwebsite\Excel\Facades\Excel;

class SubTaskController extends Controller
{
    public function index(Request $request)
    {
        //
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($task_id)
    {
        $job_id = Task::find($task_id)->job_id;
        $users = User::pluck('email','id')->all();
        return view('subtasks.create', compact('users', 'task_id', 'job_id'));
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'assign_users' => 'required',
        ]);

        $input = $request->all();
        $input['create_user'] = Auth::user()->id;
        $input['status'] = 0;
        $input['task_id'] = $request->task_id;

        $input['assign_users'] = '';
        if(count($request->assign_users) != 0) {
            for($i=0; $i<count($request->assign_users); $i++) {
                $input['assign_users'] .= ','.$request->assign_users[$i].',';
            }
        } else {
            $input['assign_users'] = null;
        }

        $sub_task = SubTask::create($input);
        return redirect()->route('subtasks.edit', $sub_task->id)
                        ->with('success', trans('global.subTaskCreateSuccess'));
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $sub_task = SubTask::find($id);
        $comments = Comment::where('type', 'subTask')->where('type_id', $id)->get();
        $users = User::pluck('email','id')->all();

        $assign_users = [];
        foreach(explode(',', $sub_task->assign_users) as $item) {
            foreach($users as $key => $user) {
                if($key == $item) {
                    $assign_users[$key] = $user;
                }                    
            }
        }
        return view('subtasks.show',compact('sub_task', 'assign_users', 'comments'));
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $sub_task = SubTask::find($id);
        $comments = Comment::where('type', 'subTask')->where('type_id', $id)->get();
        $users = User::pluck('email','id')->all();

        $assign_users = [];
        $i = 0;
        foreach(explode(',', $sub_task->assign_users) as $item) {
            $assign_users[$i] = $item;
            ++$i;
        }

        if(!empty(Auth::user()->getRoleNames()) && Auth::user()->hasExactRoles('SuperAdmin') || Auth::user()->hasExactRoles('Admin'))
            return view('subtasks.edit',compact('sub_task', 'users', 'assign_users', 'comments'));
        else
            return view('subtasks.edit-simple',compact('sub_task', 'users', 'assign_users', 'comments'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $sub_task = SubTask::find($id);
        if(!empty(Auth::user()->getRoleNames()) && Auth::user()->hasExactRoles('SuperAdmin') || Auth::user()->hasExactRoles('Admin')) {
            $this->validate($request, [
                'title' => 'required',
                'status' => 'required',
                'assign_users' => 'required',
            ]);
    
            $input = $request->all();
            
            $input['assign_users'] = '';
            if(count($request->assign_users) != 0) {
                for($i=0; $i<count($request->assign_users); $i++) {
                    $input['assign_users'] .= ','.$request->assign_users[$i].',';
                }
            } else {
                $input['assign_users'] = null;
            }
                
            $sub_task->update($input);
        } else {
            $this->validate($request, [
                'status' => 'required',
            ]);

            $sub_task->status = $request->status;
            $sub_task->save();

            $sender = Auth::user()->email;
            $name = Auth::user()->firstname. ' ' .Auth::user()->lastname;
            $subject = '';
            if($sub_task->status == 0) {
                $subject = $sub_task->title . trans('global.jobPending');
            } else if($sub_task->status == 1) {
                $subject = $sub_task->title . trans('global.jobActive');
            } else if($sub_task->status == 2) {
                $subject = $sub_task->title . trans('global.jobComplete');
            } else if($sub_task->status == 3) {
                $subject = $sub_task->title . trans('global.jobUnComplete');
            } else {
                $subject = $sub_task->title . trans('global.jobPending');
            }
            $title = $sub_task->title;
            $description = $sub_task->description;

            // send a mail
            if(config('setting.notification_allow') == 1) {
                $mailData = [
                    'sender' => $sender,
                    'name' => $name,
                    'subject' => $subject,
                    'title' => $title,
                    'description' => $description
                ];
    
                $creator = $sub_task->creator();
                Mail::to($creator)->send(new SendMail($mailData));
            }
        }    

        if(config('setting.notification_allow') == 1) {
            $recipient = User::where('id', $sub_task->create_user)->get();
            $fcmTokens = $recipient[0]->fcm_token;
            $sender = Auth::user()->id;
            $title = "Hello";
            $message = $sub_task->title . "(SubTask) is updated";
            if($fcmTokens != null)
                $recipient[0]->notify(new SendPushNotification($sender, $title,$message,$fcmTokens));
        }

        return redirect()->route('subtasks.edit', $id)
                        ->with('success', trans('global.subTaskUpdatedSuccess'));
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $task_id = SubTask::find($id)->task_id;
        SubTask::find($id)->delete();
        return redirect()->route('jobcards.index')
                        ->with('success', trans('global.subTaskDeletedSuccess'));
    }

    public function SaveHeader(Request $request, $id)
    {
        $sub_task = SubTask::find($id);
        $sub_task->coordinater = $request->coordinater;
        $sub_task->university = $request->university;

        $sub_task->save();
        return redirect()->route('subtasks.edit', $id)
                ->with('success', trans('global.wpCommentHeader'));
    }

    public function SaveComments(Request $request)
    {
        $result = [];
        $old_id = $request->old_id;
        if($old_id) {
            $comment = Comment::find($old_id);
            if($request->title != null)
                $comment->title = $request->title;
            $comment->type = $request->type;
            $comment->phase = $request->phase;
            $comment->type_id = $request->type_id;
            $comment->content = $request->content;
            $comment->save();

            $result = [
                "success" => true,
                "new" => null
            ];
        } else {
            $data = [];
            $data['create_user'] = Auth::user()->id;
            $data['title'] = $request->title;
            $data['type'] = $request->type;
            $data['phase'] = $request->phase;
            $data['type_id'] = $request->type_id;
            $data['content'] = $request->content;
            $comment = Comment::create($data);

            $result = [
                "success" => true,
                "new" => $comment->id
            ];
        }

        return $result;
    }

    public function duplicate($id)
    {
        try {
            $sub_task = SubTask::find($id);
            $data = [
                'task_id' => $sub_task->task_id,
                'title' => $sub_task->title,
                'create_user' => $sub_task->create_user,
                'assign_users' => $sub_task->assign_users,
                'status' => $sub_task->status,
                'coordinater' => $sub_task->coordinater,
                'university' => $sub_task->university
            ];
            $new_sub_task = SubTask::create($data);
    
            $comments = $sub_task->comments;
            $descriptions = $sub_task->descriptions;
    
            foreach($comments as $key => $comment) {
                $data = [
                    'create_user' => $new_sub_task->create_user,
                    'type_id' => $new_sub_task->id,
                    'type' => 'subTask',
                    'title' => $comment->title,
                    'phase' => $comment->phase,
                    'content' => $comment->content,
                ];
                $new_comment = Comment::create($data);
            }
    
            foreach($descriptions as $key => $description) {
                $data = [
                    'create_user' => $new_sub_task->create_user,
                    'type_id' => $new_sub_task->id,
                    'type' => 'subTask',
                    'content' => $description->content,
                ];
                $new_description = Description::create($data);
            }
            return redirect()->back()->with('success', 'This SubTask is duplicated successfully.');
        } catch(\Exception $e) {
            return redirect()->back()->with('error', 'Unexpected error occured.');
        }        
    }

    public function excel($task_id)
    {
        $subtasks = SubTask::where('task_id', $task_id)->paginate(config('pagination.per_page'));
        return view('excel.subtasks', compact('subtasks', 'task_id'));
    }

    public function export($task_id) 
    {
        return Excel::download(new SubTasksExport($task_id), 'subtasks.xlsx');
    }

    public function import($task_id) 
    {
        try {
            Excel::import(new SubTasksImport($task_id),request()->file('file'));
            $message = trans('global.dataEntrySuccess');
            return back()->with('success', $message);
        } catch(\Exception $e) {
            $message = trans('global.dataFormatError');
            return back()->with('error', $message);
        }
    }
}

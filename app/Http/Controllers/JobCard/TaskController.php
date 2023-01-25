<?php

namespace App\Http\Controllers\JobCard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubTask;
use App\Models\Task;
use App\Models\User;
use App\Models\Comment;
use App\Models\Description;
use Illuminate\Support\Facades\Auth;
use Mail;
use App\Mail\SendMail;
use Notification;
use App\Notifications\SendPushNotification;
use App\Exports\TasksExport;
use App\Imports\TasksImport;
use Maatwebsite\Excel\Facades\Excel;

class TaskController extends Controller
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
    public function create($job_id)
    {
        $users = User::pluck('email','id')->all();
        return view('tasks.create', compact('users', 'job_id'));
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
        $input['job_id'] = $request->job_id;

        $input['assign_users'] = '';
        if(count($request->assign_users) != 0) {
            for($i=0; $i<count($request->assign_users); $i++) {
                $input['assign_users'] .= ','.$request->assign_users[$i].',';
            }
        } else {
            $input['assign_users'] = null;
        }

        $task = Task::create($input);
        return redirect()->route('tasks.edit', $task->id)
                        ->with('success', trans('global.taskCreateSuccess'));
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $task = Task::find($id);
        $comments = Comment::where('type', 'task')->where('type_id', $id)->get();
        $sub_tasks = SubTask::where('task_id', $id)->get();

        $users = User::pluck('email','id')->all();
        $assign_users = [];
        foreach(explode(',', $task->assign_users) as $item) {
            foreach($users as $key => $user) {
                if($key == $item) {
                    $assign_users[$key] = $user;
                }                    
            }
        }
        return view('tasks.show',compact('task', 'assign_users', 'sub_tasks', 'comments'));
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $task = Task::find($id);
        $comments = Comment::where('type', 'task')->where('type_id', $id)->get();
        $users = User::pluck('email','id')->all();

        $assign_users = [];
        $i = 0;
        foreach(explode(',', $task->assign_users) as $item) {
            $assign_users[$i] = $item;
            ++$i;
        }

        if(!empty(Auth::user()->getRoleNames()) && Auth::user()->hasExactRoles('SuperAdmin') || Auth::user()->hasExactRoles('Admin')) {
            if($request->per_page != null)
                $sub_tasks = SubTask::where('task_id', $id)->paginate($request->per_page)->appends(['per_page' => $request->per_page]);
            else
                $sub_tasks = SubTask::where('task_id', $id)->paginate(config('pagination.per_page'))->appends(['per_page' => config('pagination.per_page')]);

            return view('tasks.edit',compact('task', 'sub_tasks', 'users', 'assign_users', 'comments'));
        }   
        else {
            $sub_tasks = SubTask::where('task_id', $id)->popular();
            return view('tasks.edit-simple',compact('task', 'sub_tasks', 'users', 'assign_users', 'comments'));
        }
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
        $task = Task::find($id);
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
            
            $task->update($input);
        } else {
            $this->validate($request, [
                'status' => 'required',
            ]);

            $task->status = $request->status;
            $task->save();

            $sender = Auth::user()->email;
            $name = Auth::user()->firstname. ' ' .Auth::user()->lastname;
            $subject = '';
            if($task->status == 0) {
                $subject = $task->title . trans('global.jobPending');
            } else if($task->status == 1) {
                $subject = $task->title . trans('global.jobActive');
            } else if($task->status == 2) {
                $subject = $task->title . trans('global.jobComplete');
            } else if($task->status == 3) {
                $subject = $task->title . trans('global.jobUnComplete');
            } else {
                $subject = $task->title . trans('global.jobPending');
            }
            $title = $task->title;
            $description = $task->description;

            // send a mail
            if(config('setting.notification_allow') == 1) {
                $mailData = [
                    'sender' => $sender,
                    'name' => $name,
                    'subject' => $subject,
                    'title' => $title,
                    'description' => $description
                ];
    
                $creator = $task->creator();
                Mail::to($creator)->send(new SendMail($mailData));
            }
        }    
        
        if(config('setting.notification_allow') == 1) {
            $recipient = User::where('id', $task->create_user)->get();
            $fcmTokens = $recipient[0]->fcm_token;
            $sender = Auth::user()->id;
            $title = "Hello";
            $message = $task->title . "(Task) is updated";
            if($fcmTokens != null)
                $recipient[0]->notify(new SendPushNotification($sender, $title,$message,$fcmTokens));
        }

        return redirect()->route('tasks.edit', $id)
                        ->with('success', trans('global.taskUpdatedSuccess'));
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $job_id = Task::find($id)->job_id;

        $subtasks = SubTask::where('task_id', $id)->get();
        foreach($subtasks as $key => $sub_task)
            $sub_task->delete();

        Task::find($id)->delete();
        return redirect()->route('jobcards.index')
                        ->with('success', trans('global.taskDeletedSuccess'));
    }

    public function SaveHeader(Request $request, $id)
    {
        $task = Task::find($id);
        $task->coordinater = $request->coordinater;
        $task->university = $request->university;

        $task->save();
        return redirect()->route('tasks.edit', $id)
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

    public function duplicate($id) {
        try {
            $task = Task::find($id);
            $data = [
                'job_id' => $task->job_id,
                'title' => $task->title,
                'create_user' => $task->create_user,
                'assign_users' => $task->assign_users,
                'status' => $task->status,
                'coordinater' => $task->coordinater,
                'university' => $task->university
            ];
            $new_task = Task::create($data);

            $comments = $task->comments;
            $descriptions = $task->descriptions;

            foreach($comments as $key => $comment) {
                $data = [
                    'create_user' => $new_task->create_user,
                    'type_id' => $new_task->id,
                    'type' => 'task',
                    'title' => $comment->title,
                    'phase' => $comment->phase,
                    'content' => $comment->content,
                ];
                $new_comment = Comment::create($data);
            }

            foreach($descriptions as $key => $description) {
                $data = [
                    'create_user' => $new_task->create_user,
                    'type_id' => $new_task->id,
                    'type' => 'task',
                    'content' => $description->content,
                ];
                $new_description = Description::create($data);
            }

            foreach($task->subtasks as $key => $sub_task) {
                $data = [
                    'task_id' => $new_task->id,
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
            }
            return redirect()->back()->with('success', 'This Task is duplicated successfully.');
        } catch(\Exception $e) {
            return redirect()->back()->with('error', 'Unexpected error occured.');
        }
    }

    public function excel($job_id)
    {
        $tasks = Task::where('job_id', $job_id)->paginate(config('pagination.per_page'));
        return view('excel.tasks', compact('tasks', 'job_id'));
    }

    public function export($job_id) 
    {
        return Excel::download(new TasksExport($job_id), 'tasks.xlsx');
    }

    public function import($job_id) 
    {
        try {
            Excel::import(new TasksImport($job_id),request()->file('file'));
            $message = trans('global.dataEntrySuccess');
            return back()->with('success', $message);
        } catch(\Exception $e) {
            $message = trans('global.dataFormatError');
            return back()->with('error', $message);
        }
    }
}

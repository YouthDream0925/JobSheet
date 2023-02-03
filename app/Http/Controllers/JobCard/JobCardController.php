<?php

namespace App\Http\Controllers\JobCard;
use Illuminate\Support\Facades\App;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JobCard;
use App\Models\Task;
use App\Models\User;
use App\Models\SubTask;
use App\Models\Comment;
use App\Models\Description;
use App\Models\Question;
use App\Models\Answer;
use Illuminate\Support\Facades\Auth;
use Mail;
use App\Mail\SendMail;
use Notification;
use App\Notifications\SendPushNotification;
use App\Exports\JobCardsExport;
use App\Imports\JobCardsImport;
use Maatwebsite\Excel\Facades\Excel;

class JobCardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $per_page = $request->per_page ? $request->per_page : config('pagination.per_page');

        $job_cards = JobCard::Popular($request->per_page);

        return view('jobcards.index', compact('job_cards'));
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::pluck('email','id')->all();
        return view('jobcards.create', compact('users'));
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
        if($request->special == true)
            $input['special'] = 1;
        $input['create_user'] = Auth::user()->id;
        $input['status'] = 0;

        $input['assign_users'] = '';
        if(count($request->assign_users) != 0) {
            for($i=0; $i<count($request->assign_users); $i++) {
                $input['assign_users'] .= ','.$request->assign_users[$i].',';
            }
        } else {
            $input['assign_users'] = null;
        }
    
        $job_card = JobCard::create($input);
        return redirect()->route('jobcards.edit', $job_card->id)
                        ->with('success', trans('global.wpCreateSuccess'));
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $job_card = JobCard::find($id);
        $comments = Comment::where('type', 'job')->where('type_id', $id)->get();
        $tasks = Task::orderBy('updated_at', 'desc')->where('job_id', $id)->get();

        $users = User::pluck('email','id')->all();
        $assign_users = [];
        foreach(explode(',', $job_card->assign_users) as $item) {
            foreach($users as $key => $user) {
                if($key == $item) {
                    $assign_users[$key] = $user;
                }                    
            }
        }
        return view('jobcards.show',compact('job_card', 'assign_users', 'tasks', 'comments'));
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $user = Auth::user();
        $job_card = JobCard::find($id);
        $comments = Comment::where('type', 'job')->where('type_id', $id)->get();
        $users = User::all();

        $list_users = [];
        $search_users = [
            null => 'Assigned to'
        ];

        foreach($users as $key => $user) {
            $search_users[$user->id] = $user->firstname.' '.$user->lastname;
            $list_users[$user->id] = $user->firstname.' '.$user->lastname;
        }

        $assign_users = [];
        $i = 0;
        foreach(explode(',', $job_card->assign_users) as $item) {
            $assign_users[$i] = $item;
            ++$i;
        }

        if(!empty(Auth::user()->getRoleNames()) && Auth::user()->hasExactRoles('SuperAdmin') || Auth::user()->hasExactRoles('Admin')) {
            if($request->per_page != null)
                $tasks = Task::orderBy('updated_at', 'desc')->where('job_id', $id)->paginate($request->per_page)->appends(['per_page' => $request->per_page]);
            else
                $tasks = Task::orderBy('updated_at', 'desc')->where('job_id', $id)->paginate(config('pagination.per_page'))->appends(['per_page' => config('pagination.per_page')]);
                
            if($job_card->special == 1)
                return view('jobcards.edit-special',compact('job_card', 'list_users', 'assign_users'));
            else
                return view('jobcards.edit',compact('job_card', 'list_users', 'assign_users', 'tasks'))
                    ->with('i', ($request->input('page', 1) - 1) * $request->per_page);
        }            
        elseif(!empty(Auth::user()->getRoleNames()) && Auth::user()->hasExactRoles('Supervisor')) {
            if($request->assigned != null || $request->status != null || $request->title != null) {
                $tasks = Task::orderBy('updated_at', 'desc')->where('job_id', $id)->Search($request->assigned, $request->status, $request->title);
                $full_tasks = Task::orderBy('updated_at', 'desc')->where('job_id', $id)->get();
                $subTasks = [];
                foreach($full_tasks as $key => $task) {
                    $data = SubTask::orderBy('updated_at', 'desc')->where('task_id', $task->id)->Search($request->assigned, $request->status, $request->title);
                    if(count($data) != 0) {
                        foreach($data as $key => $value)
                            array_push($subTasks, $value);
                    }
                }
            }
            else {
                $tasks = Task::orderBy('updated_at', 'desc')->where('job_id', $id)->get();
                $subTasks = [];
                foreach($tasks as $key => $task) {
                    $data = SubTask::orderBy('updated_at', 'desc')->where('task_id', $task->id)->get();
                    if(count($data) != 0) {
                        foreach($data as $key => $value)
                            array_push($subTasks, $value);
                    }
                }
                // echo json_encode($subTasks);
                // die();
            }
            if($job_card->special == 1)
                return view('jobcards.edit-simple-special',compact('job_card', 'list_users', 'assign_users'));
            else
                return view('jobcards.edit-supervisor',compact('job_card', 'tasks', 'subTasks', 'search_users', 'assign_users', 'comments'));
        }
        else {
            if($job_card->special == 1)
                return view('jobcards.edit-simple-special',compact('job_card', 'list_users', 'assign_users'));
            else
                return view('jobcards.edit-simple',compact('job_card', 'list_users', 'assign_users'));
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
        $job_card = JobCard::find($id);
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

            $job_card->update($input);
        }
        else {
            $this->validate($request, [
                'status' => 'required',
            ]);

            $job_card->status = $request->status;
            $job_card->save();

            $sender = Auth::user()->email;
            $name = Auth::user()->firstname. ' ' .Auth::user()->lastname;
            $subject = '';
            if($job_card->status == 0) {
                $subject = $job_card->title . trans('global.jobPending');
            } else if($job_card->status == 1) {
                $subject = $job_card->title . trans('global.jobActive');
            } else if($job_card->status == 2) {
                $subject = $job_card->title . trans('global.jobComplete');
            } else if($job_card->status == 3) {
                $subject = $job_card->title . trans('global.jobUnComplete');
            } else {
                $subject = $job_card->title . trans('global.jobPending');
            }
            $title = $job_card->title;
            $description = $job_card->description;

            // send a mail
            if(config('setting.notification_allow') == 1) {
                $mailData = [
                    'sender' => $sender,
                    'name' => $name,
                    'subject' => $subject,
                    'title' => $title,
                    'description' => $description
                ];
    
                $creator = $job_card->creator();
                Mail::to($creator)->send(new SendMail($mailData));
            }
        }

        if(config('setting.notification_allow') == 1) {
            $recipient = User::where('id', $job_card->create_user)->get();
            $fcmTokens = $recipient[0]->fcm_token;
            $sender = Auth::user()->id;
            $title = "Hello";
            $message = $job_card->title . "(WP) is updated";
            if($fcmTokens != null)
                $recipient[0]->notify(new SendPushNotification($sender, $title,$message,$fcmTokens));
        }

        return redirect()->route('jobcards.edit', $id)
                        ->with('success', trans('global.wpUpdateSuccess'));
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tasks = Task::orderBy('updated_at', 'desc')->where('job_id', $id)->get();
        foreach($tasks as $key => $task)
        {
            $task_id = $task->id;
            $subtasks = SubTask::orderBy('updated_at', 'desc')->where('task_id', $task_id)->get();
            foreach($subtasks as $key => $sub_task)
                $sub_task->delete();

            $task->delete();
        }

        JobCard::find($id)->delete();
        return redirect()->route('jobcards.index')
                        ->with('success', trans('global.wpDestroySuccess'));
    }

    public function SaveHeader(Request $request, $id)
    {
        $job_card = JobCard::find($id);
        $job_card->coordinater = $request->coordinater;
        $job_card->university = $request->university;

        $job_card->save();
        return redirect()->route('jobcards.edit', $id)
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
            $data['phase'] = $request->phase;
            $data['type'] = $request->type;
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

    public function DeleteComments(Request $request)
    {
        $delete_id = $request->old_id;
        $type = $request->type;
        $type_id = $request->type_id;
        $result = [];

        try {
            $comment = Comment::find($delete_id);
            if($comment->type_id == $type_id && $comment->type == $type) {
                $comment->delete();
                $result = [
                    'success' => true,
                    'msg' => null,
                ];
            }                
            else {
                $result = [
                    'success' => false,
                    'msg' => trans('global.unexpectedError'),
                ];
            }                
        } catch(\Exception $e) {
            $result = [
                'success' => false,
                'msg' => trans('global.unexpectedError'),
            ];
        }
        return $result;
    }

    public function SaveDescriptions(Request $request)
    {
        $old_id = $request->old_id;
        if($old_id) {
            $comment = Description::find($old_id);
            $comment->type = $request->type;
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
            $data['type'] = $request->type;
            $data['type_id'] = $request->type_id;
            $data['content'] = $request->content;
            $description = Description::create($data);

            $result = [
                "success" => true,
                "new" => $description->id
            ];
        }

        return $result;
    }

    public function DeleteDescriptions(Request $request)
    {
        $delete_id = $request->delete_id;
        $result = [];

        try {
            $description = Description::find($delete_id);
            $description->delete();

            $result = [
                'success' => true,
                'delete' => $delete_id
            ];
        } catch(\Exception $e) {
            $result = [
                'error' => 'Unexpected error occured.',
                'delete' => $delete_id
            ];
        }
        return $result;
    }

    public function SaveQuestions(Request $request)
    {
        $user = Auth::user();
        if(!empty($user->getRoleNames()) && $user->hasExactRoles('SuperAdmin') || $user->hasExactRoles('Admin')) {
            if($request->type == 0) {
                $this->validate($request, [
                    'job_id' => 'required',
                    'content' => 'required',
                    'type' => 'required',
                    'answers' => 'required'
                ]);
            } else {
                $this->validate($request, [
                    'job_id' => 'required',
                    'content' => 'required',
                    'type' => 'required',
                ]);
            }
        } else {
            $this->validate($request, [
                'job_id' => 'required',
                'content' => 'required',
                'type' => 'required',
                'answers' => 'required'
            ]);
        }

        if($request->old_id) {
            $question = Question::find($request->old_id);

            $question->create_user = Auth::user()->id;
            $question->job_id = $request->job_id;
            $question->content = $request->content;
            $question->type = $request->type;
            $question->order = $request->order;
            $question->save();

            if(!empty($user->getRoleNames()) && $user->hasExactRoles('SuperAdmin') || $user->hasExactRoles('Admin')) {
                Answer::where('question_id', $question->id)->delete();
                $answers = $request->answers;

                if($request->type == 0) {
                    foreach($answers as $key => $value) {
                        $data = [];
                        $data['question_id'] = $question->id;
                        $data['answer'] = $value;
            
                        $answer = Answer::create($data);
                    }
                }
            } else {
                if($request->type == 0) {
                    $answers = $request->answers;
                    $current_answers = Answer::where('question_id', $question->id)->get();
                    
                    foreach($current_answers as $key => $current_answer) {
                        $current_answer->correct = null;
                        $current_answer->save();
                    }

                    foreach($current_answers as $key => $current_answer) {
                        if($answers == $current_answer->id) {
                            $current_answer->correct = 1;
                            $current_answer->save();
                        }
                    }
                } else {
                    Answer::where('question_id', $question->id)->delete();
                    $answers = $request->answers;
    
                    $data = [];
                    $data['question_id'] = $question->id;
                    $data['answer'] = $answers;
        
                    $answer = Answer::create($data);
                }
            }

            $result = [
                "success" => true,
                "new" => null
            ];
        } else {
            $job_id = $request->job_id;
            $content = $request->content;
            $type = $request->type;
            $answers = $request->answers;
            $order = $request->order;
    
            $data = [];
            $data['create_user'] = Auth::user()->id;
            $data['job_id'] = $job_id;
            $data['content'] = $content;
            $data['type'] = $type;
            $data['order'] = $order;
    
            $question = Question::create($data);

            if(!empty($user->getRoleNames()) && $user->hasExactRoles('SuperAdmin') || $user->hasExactRoles('Admin')) {
                if($request->type == 0) {
                    foreach($answers as $key => $value) {
                        $data = [];
                        $data['question_id'] = $question->id;
                        $data['answer'] = $value;
            
                        $answer = Answer::create($data);
                    }
                }
            } else {
                foreach($answers as $key => $value) {
                    $data = [];
                    $data['question_id'] = $question->id;
                    $data['answer'] = $value;
        
                    $answer = Answer::create($data);
                }
            }
            

            $result = [
                "success" => true,
                "new" => $question->id
            ];
        }

        return $result;
    }

    public function SaveAnswers(Request $request)
    {
        $result = [];
        $save_answers = $request->save_data;

        try {
            foreach($save_answers as $key => $answer) {
                $id = $answer['question_oldid'];
                $question = Question::find($id);
                if($question->type == $answer['question_type'] && $question->type == 0) {
                    $answers = $answer['answers'];
                    $current_answers = Answer::where('question_id', $question->id)->get();
                    
                    foreach($current_answers as $key => $current_answer) {
                        $current_answer->correct = null;
                        $current_answer->save();
                    }
    
                    foreach($current_answers as $key => $current_answer) {
                        if($answers == $current_answer->id) {
                            $current_answer->correct = 1;
                            $current_answer->save();
                        }
                    }
                } else if($question->type == $answer['question_type'] && $question->type == 1) {
                    Answer::where('question_id', $question->id)->delete();
                    $answers = $answer['answers'];
    
                    $data = [];
                    $data['question_id'] = $question->id;
                    $data['answer'] = $answers;
        
                    $answer = Answer::create($data);
                }
            }

            $result = ['success' => true];
        } catch(\Exception $e) {
            $result = ['success' => false];
        }
        return $result;
    }

    public function DeleteQuestions(Request $request)
    {
        $delete_id = $request->delete_id;
        $result = [];

        try {
            $question = Question::find($delete_id);
            $answers = Answer::where('question_id', $question->id)->delete();
            $question->delete();

            $result = [
                'success' => true,
                'delete' => $delete_id
            ];
        } catch(\Exception $e) {
            $result = [
                'error' => 'Unexpected error occured.',
                'delete' => $delete_id
            ];
        }
        return $result;
    }

    public function duplicate($id)
    {
        try {
            $job_card = JobCard::find($id);

            $data = [
                'title' => $job_card->title,
                'create_user' => $job_card->create_user,
                'assign_users' => $job_card->assign_users,
                'status' => $job_card->status,
                'coordinater' => $job_card->coordinater,
                'university' => $job_card->university,
                'special' => $job_card->special,
            ];
            $new_jod_card = JobCard::create($data);

            $comments = $job_card->comments;
            $descriptions = $job_card->descriptions;
            $questions = $job_card->questions;

            foreach($comments as $key => $comment) {
                $data = [
                    'create_user' => $new_jod_card->create_user,
                    'type_id' => $new_jod_card->id,
                    'type' => 'job',
                    'title' => $comment->title,
                    'phase' => $comment->phase,
                    'content' => $comment->content,
                ];
                $new_comment = Comment::create($data);
            }

            foreach($descriptions as $key => $description) {
                $data = [
                    'create_user' => $new_jod_card->create_user,
                    'type_id' => $new_jod_card->id,
                    'type' => 'job',
                    'content' => $description->content,
                ];
                $new_description = Description::create($data);
            }
    
            foreach($questions as $key => $question) {
                $data = [
                    'create_user' => $new_jod_card->create_user,
                    'job_id' => $new_jod_card->id,
                    'content' => $question->content,
                    'type' => $question->type,
                    'order' => $question->order,
                ];
                $new_question = Question::create($data);

                foreach($question->answers as $key => $answer) {
                    $data = [
                        'question_id' => $new_question->id,
                        'answer' => $answer->answer,
                        'correct' => $answer->correct,
                    ];
                    $new_answer = Answer::create($data);
                }
            }
            
            return redirect()->back()->with('success', 'This WP is duplicated successfully.');
        } catch(\Exception $e) {
            return redirect()->back()->with('error', 'Unexpected error occured.');
        }
    }

    public function excel()
    {
        $job_cards = JobCard::paginate(config('pagination.per_page'));
        return view('excel.jobcards', compact('job_cards'));
    }

    public function export() 
    {
        return Excel::download(new JobCardsExport, 'wps.xlsx');
    }

    public function import() 
    {
        try {
            Excel::import(new JobCardsImport,request()->file('file'));
            $message = trans('global.dataEntrySuccess');
            return back()->with('success', $message);
        } catch(\Exception $e) {
            $message = trans('global.dataFormatError');
            return back()->with('error', $message);
        }
    }
}
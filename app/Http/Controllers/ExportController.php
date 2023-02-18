<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JobCard;
use App\Models\Task;
use App\Models\SubTask;
use App\Models\User;
use App\Exports\JobCardExport;
use App\Exports\JobCardsExport;
use App\Exports\TaskExport;
use App\Exports\TasksExport;
use App\Exports\SubTaskExport;
use App\Exports\SubTasksExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Traits\GlobalTrait;
use PDF;
use View;

class ExportController extends Controller
{
    public function wps()
    {
        $job_cards = JobCard::orderBy('updated_at', 'desc')->pluck('title', 'id')->all();
        $users = User::pluck('email', 'id')->all();
        return view('exports.wps', compact('job_cards', 'users'));
    }

    public function wps_excute(Request $request)
    {
        $job_id = $request->job;
        $field = $request->field;
        $question = $request->question;
        $phase = $request->phase;
        $export_type = $request->export_type;

        $job_card = JobCard::find($job_id);
        $data = [
            'job_card' => $job_card,
            'field' => $field,
            'question' => $question,
            'phase' => $phase,
        ];
        
        if($export_type == "pdf") {
            $pdf = PDF::loadView('pdf.filter_job', $data);
            
            $document_name= str_replace(array("/", "\\", ":", "*", "?", "«", "<", ">", "|"), "-", $job_card->title);
            // echo json_encode($document_name);
            // die();
            return $pdf->download($document_name.'.pdf');
            // return view('pdf.filter_job', compact('job_card', 'field', 'question', 'phase'));
        } else if($export_type == "excel") {
            $document_name= str_replace(array("/", "\\", ":", "*", "?", "«", "<", ">", "|"), "-", $job_card->title);
            return Excel::download(new JobCardExport($job_card->id), $document_name.'.xlsx');
        } else if($export_type == "doc") {
            $view = View::make('pdf.filter_job', $data)->render();
            $file_name = strtotime(date('Y-m-d H:i:s')) . '_advertisement_template.docx';
            $headers = array(
                "Content-type"=>"text/html",
                "Content-Disposition"=>"attachment;Filename=".$job_card->title.".doc"
            );
    
            return response()->make($view, 200, $headers);        
        }
    }

    public function wps_excute_by_user(Request $request)
    {
        $user_id = $request->user_id;
        $user_export_type = $request->user_export_type;
        $user = User::find($user_id);

        $user_jobs = [];
        $job_cards = JobCard::orderBy('updated_at', 'desc')->get();
        foreach($job_cards as $job_card) {
            $wp_assign_check = false;
            foreach($job_card->assign() as $assign_user) {
                if($assign_user == $user->email)
                    $wp_assign_check = true;
            }
            if($wp_assign_check == true)
                array_push($user_jobs, $job_card);
        }

        $data = [
            'job_cards' => $user_jobs,
            'user' => $user
        ];

        if(count($user_jobs) == 0)
            return redirect()->back()->with('error', 'There is no WP assigned to '. $user->firstname . ' ' . $user->lastname);

        if($user_export_type == "pdf") {
            $pdf = PDF::loadView('pdf.filter_job_by_user', $data);
            return $pdf->download($user->firstname.' '.$user->lastname.'.pdf');
        } else if($user_export_type == "excel") {
            // return Excel::download(new JobCardsExport($user_jobs), $user->firstname.'.xlsx');
            // return Excel::download(new JobCardExport($job_card->id), $job_card->title.'.xlsx');
            return Excel::download(new JobCardsExport($user_jobs), $user->firstname.'.xlsx');
        } else if($user_export_type == "doc") {
            $view = View::make('pdf.filter_job_by_user', $data)->render();
            $file_name = strtotime(date('Y-m-d H:i:s')) . '_advertisement_template.docx';
            $headers = array(
                "Content-type"=>"text/html",
                "Content-Disposition"=>"attachment;Filename=".$user_jobs->title.".doc"
            );
    
            return response()->make($view, 200, $headers);        
        }
    }

    public function wps_special($id)
    {
        $job_card = JobCard::find($id);
        $data = [
            'job_card' => $job_card,
            'field' => null,
            'question' => true,
            'phase' => null,
        ];
        
        $pdf = PDF::loadView('pdf.filter_job', $data);
        return $pdf->download($job_card->title.'.pdf');
    }

    public function tasks()
    {
        $tasks = Task::orderBy('updated_at', 'desc')->pluck('title', 'id')->all();
        $users = User::pluck('email', 'id')->all();
        return view('exports.tasks', compact('tasks', 'users'));
    }

    public function tasks_excute(Request $request)
    {
        $task_id = $request->task;
        $field = $request->field;
        $phase = $request->phase;
        $export_type = $request->export_type;

        $task = Task::find($task_id);
        $data = [
            'task' => $task,
            'field' => $field,
            'phase' => $phase,
        ];
        
        if($export_type == "pdf") {
            $pdf = PDF::loadView('pdf.filter_task', $data);
            return $pdf->download($task->title.'.pdf');
            // return view('pdf.filter_job', compact('job_card', 'field', 'question', 'phase'));
        } else if($export_type == "excel") {
            return Excel::download(new TaskExport($task->id), $task->title.'.xlsx');
        } else if($export_type == "doc") {
            
            $view = View::make('pdf.filter_task', $data)->render();
            $file_name = strtotime(date('Y-m-d H:i:s')) . '_advertisement_template.docx';
            $headers = array(
                "Content-type"=>"text/html",
                "Content-Disposition"=>"attachment;Filename=".$task->title.".doc"
            );

            return response()->make($view, 200, $headers);        
        }

    }

    public function tasks_excute_by_user(Request $request)
    {
        $user_id = $request->user_id;
        $user_export_type = $request->user_export_type;
        $user = User::find($user_id);

        $user_tasks = [];
        $tasks = Task::orderBy('updated_at', 'desc')->get();
        foreach($tasks as $task) {
            $task_assign_check = false;
            foreach($task->assign() as $assign_user) {
                if($assign_user == $user->email)
                    $task_assign_check = true;
            }
            if($task_assign_check == true)
                array_push($user_tasks, $task);
        }

        $data = [
            'tasks' => $user_tasks,
            'user' => $user
        ];

        if(count($user_tasks) == 0)
            return redirect()->back()->with('error', 'There is no Task assigned to '. $user->firstname . ' ' . $user->lastname);

        if($user_export_type == "pdf") {
            $pdf = PDF::loadView('pdf.filter_task_by_user', $data);
            return $pdf->download($user->firstname.' '.$user->lastname.'.pdf');
        } else if($user_export_type == "excel") {
            // return Excel::download(new JobCardExport($job_card->id), $job_card->title.'.xlsx');
            return Excel::download(new TasksExport($user_tasks), $user->firstname.' '.$user->lastname.'.xlsx');
        } else if($user_export_type == "doc") {
            $view = View::make('pdf.filter_task_by_user', $data)->render();
            $file_name = strtotime(date('Y-m-d H:i:s')) . '_advertisement_template.docx';
            $headers = array(
                "Content-type"=>"text/html",
                "Content-Disposition"=>"attachment;Filename=".$user_tasks->title.".doc"
            );
            return response()->make($view, 200, $headers);                
        }
    }

    public function subtasks()
    {
        $subtasks = SubTask::pluck('title', 'id')->all();
        $users = User::pluck('email', 'id')->all();
        return view('exports.subTasks', compact('subtasks', 'users'));
    }

    public function subtasks_excute(Request $request)
    {
        $sub_task_id = $request->subtask;
        $field = $request->field;
        $phase = $request->phase;
        $export_type = $request->export_type;

        $sub_task = SubTask::find($sub_task_id);
        $data = [
            'sub_task' => $sub_task,
            'field' => $field,
            'phase' => $phase,
        ];
        
        if($export_type == "pdf") {
            $pdf = PDF::loadView('pdf.filter_sub', $data);
            return $pdf->download($sub_task->title.'.pdf');
            // return view('pdf.filter_job', compact('job_card', 'field', 'question', 'phase'));
        } else if($export_type == "excel") {
            return Excel::download(new SubTaskExport($sub_task->id), $sub_task->title.'.xlsx');
        } else if($export_type == "doc") {
            $view = View::make('pdf.filter_sub', $data)->render();
            $headers = array(
                "Content-type"=>"text/html",
                "Content-Disposition"=>"attachment;Filename=".$sub_task->title.".doc"
            );

            return response()->make($view, 200, $headers);
        }
    }

    public function subtasks_excute_by_user(Request $request)
    {
        $user_id = $request->user_id;
        $user_export_type = $request->user_export_type;
        $user = User::find($user_id);

        $user_sub_tasks = [];
        $sub_tasks = SubTask::orderBy('updated_at', 'desc')->get();
        foreach($sub_tasks as $sub_task) {
            $sub_task_assign_check = false;
            foreach($sub_task->assign() as $assign_user) {
                if($assign_user == $user->email)
                    $sub_task_assign_check = true;
            }
            if($sub_task_assign_check == true)
                array_push($user_sub_tasks, $sub_task);
        }

        $data = [
            'sub_tasks' => $user_sub_tasks,
            'user' => $user
        ];

        if(count($user_sub_tasks) == 0)
            return redirect()->back()->with('error', 'There is no SubTask assigned to '. $user->firstname . ' ' . $user->lastname);

        if($user_export_type == "pdf") {
            $pdf = PDF::loadView('pdf.filter_sub_by_user', $data);
            return $pdf->download($user->firstname.' '.$user->lastname.'.pdf');
        } else if($user_export_type == "excel") {
            // return Excel::download(new JobCardExport($job_card->id), $job_card->title.'.xlsx');
            return Excel::download(new SubTasksExport($user_sub_tasks), $user->firstname.' '.$user->lastname.'.xlsx');
        }
        else if($user_export_type == "doc") {
            // return Excel::download(new JobCardExport($job_card->id), $job_card->title.'.xlsx');

            $view = View::make('pdf.filter_sub_by_user', $data)->render();
            $file_name = strtotime(date('Y-m-d H:i:s')) . '_advertisement_template.docx';
            $headers = array(
                "Content-type"=>"text/html",
                "Content-Disposition"=>"attachment;Filename=".$user_sub_tasks->title.".doc"
            );
    
            return response()->make($view, 200, $headers);
        }
    }
}

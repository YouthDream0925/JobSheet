<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JobCard;
use App\Models\Task;
use App\Models\SubTask;
use App\Models\User;
use App\Exports\JobCardExport;
use App\Exports\TaskExport;
use App\Exports\SubTaskExport;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class ExportController extends Controller
{
    public function wps()
    {
        $job_cards = JobCard::pluck('title', 'id')->all();
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
            return $pdf->download($job_card->title.'.pdf');
            // return view('pdf.filter_job', compact('job_card', 'field', 'question', 'phase'));
        } else if($export_type == "excel") {
            return Excel::download(new JobCardExport($job_card->id), $job_card->title.'.xlsx');
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
        $tasks = Task::pluck('title', 'id')->all();
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
            return Excel::download(new TaskExport($sub_task->id), $sub_task->title.'.xlsx');
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exports\JobCardExport;
use App\Exports\TaskExport;
use App\Exports\SubTaskExport;
use App\Models\JobCard;
use App\Models\Task;
use App\Models\SubTask;
use Maatwebsite\Excel\Facades\Excel;

class ExcelController extends Controller
{
    public function job($id)
    {
        $job_card = JobCard::find($id);
        return Excel::download(new JobCardExport($job_card->id), $job_card->title.'.xlsx');
    }

    public function task($id)
    {
        $task = Task::find($id);
        return Excel::download(new TaskExport($task->id), $task->title.'.xlsx');
    }

    public function subTask($id)
    {
        $sub_task = SubTask::find($id);
        return Excel::download(new SubTaskExport($sub_task->id), $sub_task->title.'.xlsx');
    }
}

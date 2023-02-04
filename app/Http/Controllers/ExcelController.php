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
        $document_name= str_replace(array("/", "\\", ":", "*", "?", "«", "<", ">", "|"), "-", $job_card->title);
        return Excel::download(new JobCardExport($job_card->id), $document_name.'.xlsx');
    }

    public function task($id)
    {
        $task = Task::find($id);
        $document_name= str_replace(array("/", "\\", ":", "*", "?", "«", "<", ">", "|"), "-", $task->title);
        return Excel::download(new TaskExport($task->id), $document_name.'.xlsx');
    }

    public function subTask($id)
    {
        $sub_task = SubTask::find($id);
        $document_name= str_replace(array("/", "\\", ":", "*", "?", "«", "<", ">", "|"), "-", $sub_task->title);
        return Excel::download(new SubTaskExport($sub_task->id), $document_name.'.xlsx');
    }
}

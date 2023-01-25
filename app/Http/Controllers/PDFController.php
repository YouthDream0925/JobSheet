<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JobCard;
use App\Models\Task;
use App\Models\SubTask;
use PDF;

class PDFController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function job($id)
    {
        $job_card = JobCard::find($id);
        $data = ['job_card' => $job_card];
        if($job_card->special == 1)
            $pdf = PDF::loadView('pdf.special_job', $data);
        else
            $pdf = PDF::loadView('pdf.normal_job', $data);

        return $pdf->download($job_card->title.'.pdf');
    }

    public function task($id)
    {
        $task = Task::find($id);
        $data = ['task' => $task];
        $pdf = PDF::loadView('pdf.task', $data);
        return $pdf->download($task->title.'.pdf');
    }

    public function subTask($id)
    {
        $sub_task = SubTask::find($id);
        $data = ['sub_task' => $sub_task];
        $pdf = PDF::loadView('pdf.subTask', $data);
        return $pdf->download($sub_task->title.'.pdf');
    }
}

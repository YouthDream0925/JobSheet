<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PhpOffice\PhpWord;
use App\Models\JobCard;
use App\Models\Task;
use App\Models\SubTask;
use View;

class DocController extends Controller
{
    public function job($id)
    {

        $job_card = JobCard::find($id);

        if($job_card->special == 1)
            $view = View::make('pdf.special_job')->with('job_card', $job_card)->render();
        else 
            $view = View::make('pdf.normal_job')->with('job_card', $job_card)->render();
        $file_name = strtotime(date('Y-m-d H:i:s')) . '_advertisement_template.docx';
        $headers = array(
            "Content-type"=>"text/html",
            "Content-Disposition"=>"attachment;Filename=".$job_card->title.".doc"
        );
        return response()->make($view, 200, $headers);
    }

    public function task($id)
    {
        $task = Task::find($id);
        $view = View::make('pdf.task')->with('task', $task)->render();
        $file_name = strtotime(date('Y-m-d H:i:s')) . '_advertisement_template.docx';
        $headers = array(
            "Content-type"=>"text/html",
            "Content-Disposition"=>"attachment;Filename=".$task->title.".doc"
        );

        return response()->make($view, 200, $headers);
    }

    public function subTask($id)
    {
        $sub_task = SubTask::find($id);

        $view = View::make('pdf.subTask')->with('sub_task', $sub_task)->render();
        $file_name = strtotime(date('Y-m-d H:i:s')) . '_advertisement_template.docx';
        $headers = array(
            "Content-type"=>"text/html",
            "Content-Disposition"=>"attachment;Filename=".$sub_task->title.".doc"
        );

        return response()->make($view, 200, $headers);
    }
}

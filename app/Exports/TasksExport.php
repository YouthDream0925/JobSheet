<?php
  
namespace App\Exports;
  
use App\Models\Task;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
  
class TasksExport implements FromCollection, WithHeadings
{
    protected $job_id;

    public function __construct($job_id)
    {
        $this->job_id = $job_id;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $tasks = Task::where('job_id', $this->job_id)->select("id", "job_id", "title", "create_user", "status", "coordinater", "university")->get();
        foreach($tasks as $key => $task) {
            $task->create_user = $task->creator();
            $task->status = config('status')[$task->status];
            if($task->coordinater == null)
                $task->coordinater = "NONE";
            if($task->university == null)
                $task->university = "NONE";
        }
        return $tasks;
    }
  
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function headings(): array
    {
        return ["ID", "WP", "Title", "Creator", "Status", "Coordinater", "University"];
    }
}
<?php
  
namespace App\Exports;
  
use App\Models\SubTask;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
  
class SubTasksExport implements FromView
{
    // protected $task_id;

    // public function __construct($task_id)
    // {
    //     $this->task_id = $task_id;
    // }
    // /**
    // * @return \Illuminate\Support\Collection
    // */
    // public function collection()
    // {
    //     $subtasks = SubTask::where('task_id', $this->task_id)->select("id", "task_id", "title", "create_user", "status", "coordinater", "university")->get();
    //     foreach($subtasks as $key => $sub_task) {
    //         $sub_task->create_user = $sub_task->creator();
    //         $sub_task->status = config('status')[$sub_task->status];
    //         if($sub_task->coordinater == null)
    //             $sub_task->coordinater = "NONE";
    //         if($sub_task->university == null)
    //             $sub_task->university = "NONE";
    //     }
    //     return $subtasks;
    // }
  
    // /**
    //  * Write code on Method
    //  *
    //  * @return response()
    //  */
    // public function headings(): array
    // {
    //     return ["ID", "Task", "Title", "Creator", "Status", "Coordinater", "University"];
    // }
    protected $sub_tasks = [];

    public function __construct($data)
    {
        $this->sub_tasks = $data;
    }

    public function view(): View
    {
        return view('excel.component.subTasks_by_user', [
            'sub_tasks' => $this->sub_tasks
        ]);
    }
}
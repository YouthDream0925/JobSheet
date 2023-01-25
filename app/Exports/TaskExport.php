<?php

namespace App\Exports;

use App\Models\Task;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class TaskExport implements FromView
{
    protected $task_id;

    public function __construct($id)
    {
        $this->task_id = $id;
    }

    public function view(): View
    {
        $task = Task::find($this->task_id);
        return view('excel.component.task', [
            'task' => $task
        ]);
    }
}
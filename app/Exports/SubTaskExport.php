<?php

namespace App\Exports;

use App\Models\SubTask;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class SubTaskExport implements FromView
{
    protected $sub_task_id;

    public function __construct($id)
    {
        $this->sub_task_id = $id;
    }

    public function view(): View
    {
        $sub_task = SubTask::find($this->sub_task_id);
        return view('excel.component.subTask', [
            'sub_task' => $sub_task
        ]);
    }
}
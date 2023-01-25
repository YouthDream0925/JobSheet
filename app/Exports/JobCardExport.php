<?php

namespace App\Exports;

use App\Models\JobCard;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class JobCardExport implements FromView
{
    protected $job_id;

    public function __construct($id)
    {
        $this->job_id = $id;
    }

    public function view(): View
    {
        $job_card = JobCard::find($this->job_id);
        return view('excel.component.jobcard', [
            'job_card' => $job_card
        ]);
    }
}
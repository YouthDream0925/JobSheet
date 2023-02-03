<?php
  
namespace App\Exports;
  
use App\Models\JobCard;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
  
class JobCardsExport implements FromView
{
    protected $job_cards = [];

    public function __construct($data)
    {
        $this->job_cards = $data;
    }

    public function view(): View
    {
        return view('excel.component.jobcards_by_user', [
            'job_cards' => $this->job_cards
        ]);
    }
}
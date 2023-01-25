<?php
  
namespace App\Exports;
  
use App\Models\JobCard;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
  
class JobCardsExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $job_cards = JobCard::select("id", "title", "create_user", "status", "coordinater", "university", "special")->get();
        foreach($job_cards as $key => $job_card) {
            $job_card->create_user = $job_card->creator();
            $job_card->status = config('status')[$job_card->status];
            if($job_card->coordinater == null)
                $job_card->coordinater = "NONE";
            if($job_card->university == null)
                $job_card->university = "NONE";
            $job_card->special = config('special')[$job_card->special];
        }
        return $job_cards;
    }
  
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function headings(): array
    {
        return ["ID", "Title", "Creator", "Status", "Coordinater", "University", "Special"];
    }
}
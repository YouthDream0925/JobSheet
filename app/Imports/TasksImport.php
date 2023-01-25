<?php
  
namespace App\Imports;

use App\Models\JobCard;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Hash;
use \Exception;
  
class TasksImport implements ToModel, WithHeadingRow
{
    protected $job_id;

    public function __construct($job_id)
    {
        $this->job_id = $job_id;
    }
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $job_id = $row['wp'];
        $job = JobCard::find($job_id);
        if($job && $this->job_id == $job_id) {
            $user = User::where('email', $row['creator'])->get()->first();

            if($user != null)
                $create_user = $user->id;
            else
                $create_user = Auth::user()->id;
            
            $status = config('reverse_status')[$row['status']];
            
            if($row['coordinater'] == "NONE")
                $coordinater = null;
            else
                $coordinater = $row['coordinater'];
    
            if($row['university'] == "NONE")
                $university = null;
            else
                $university = $row['university'];
    
            return new Task([
                'job_id' => $job_id,
                'title' => $row['title'],
                'create_user' => $create_user,
                'status'    => $status,
                'coordinater' => $coordinater,
                'university' => $university
            ]);
        } else {
            return new Task([]);
        }
    }
}
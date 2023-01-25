<?php
  
namespace App\Imports;

use App\Models\JobCard;
use App\Models\Task;
use App\Models\SubTask;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Hash;
use \Exception;
  
class SubTasksImport implements ToModel, WithHeadingRow
{
    protected $task_id;

    public function __construct($task_id)
    {
        $this->task_id = $task_id;
    }
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $task_id = $row['task'];
        $task = Task::find($task_id);
        if($task && $this->task_id == $task_id) {
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
    
            return new SubTask([
                'task_id' => $task_id,
                'title' => $row['title'],
                'create_user' => $create_user,
                'status'    => $status,
                'coordinater' => $coordinater,
                'university' => $university
            ]);
        } else {
            return new SubTask([]);
        }
    }
}
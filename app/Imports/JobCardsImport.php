<?php
  
namespace App\Imports;
  
use App\Models\JobCard;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Hash;
use \Exception;
  
class JobCardsImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
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

        if($row['special'] == "Yes")
            $special = 1;
        else if($row['special'] == "No")
            $special = 0;

        return new JobCard([
            'title' => $row['title'],
            'create_user' => $create_user,
            'status'    => $status,
            'coordinater' => $coordinater,
            'university' => $university,
            'special' => $special
        ]);
    }
}
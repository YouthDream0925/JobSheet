<?php
  
namespace App\Imports;
  
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Hash;
  
class UsersImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $firstname = $row['firstname'];
        $lastname = $row['lastname'];
        $email = $row['email'];
        $password = Hash::make($row['password']);
        if($row['allowlogin'] == "Yes")
            $allow_login = 1;
        else
            $allow_login = 0;

        return new User([
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email'    => $email, 
            'password' => $password,
            'allow_login' => $allow_login
        ]);
    }
}
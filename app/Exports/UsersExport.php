<?php
  
namespace App\Exports;
  
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
  
class UsersExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $users = User::select("id", "firstname", "lastname", "email", "allow_login")->get();
        foreach($users as $key => $user) {
            $user->allow_login = config('special')[$user->allow_login];
        }
        return $users;
    }
  
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function headings(): array
    {
        return ["ID", "FirstName", "LastName", "Email", "AllowLogin"];
    }
}
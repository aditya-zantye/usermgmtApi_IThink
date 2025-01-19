<?php 

namespace App\Services;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Log;

class AdminService
{
    public function fetchAllUsersService(){
        try{
            $allUsers = [];
            $getAllUsers = (new User())->fetchAllUsersQuery();
            if(!empty($getAllUsers)){
                foreach($getAllUsers as $user){
                    $allUsers[] = [
                        'employee_code' => $user->employee_code ?? '',
                        'employee_name' => $user->name ?? '',
                        'employee_email' => $user->email ?? '',
                        'employee_status' => $user->status == 1 ? 'Active' : 'Inactive'
                    ];
                }
            }
            return $allUsers;
        }
        catch(Exception $e){
            Log::error("An error occured for user list service: ".$e->getMessage());
            return [];
        }
    }
}
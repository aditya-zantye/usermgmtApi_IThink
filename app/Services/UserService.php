<?php 

namespace App\Services;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Log;

class UserService
{
    public function fetchUserDetailsService($employee_code){
        try{
            $user_details = [];
            $getUser = (new User())->fetchUserQuery(null, $employee_code);
            if(!empty($getUser)){
                $user_details = [
                    'employee_code' => $getUser->employee_code ?? '',
                    'employee_name' => $getUser->name ?? '',
                ];
            }
            return $user_details;
        }
        catch(Exception $e){
            Log::error([
                'status' => false,
                'message' => "An error occured while fetching user details service",
                'data' => $e->getMessage()
            ]);
            return [];
        }
    }
}
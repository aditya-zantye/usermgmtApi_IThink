<?php 

namespace App\Services;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Log;

class AuthService
{
    public function userRegisterService($data=[]){
        try{
            if(!empty($data)){
                $randomEmpCode = generate_employee_code();
                $createUser = (new User())->registerUserQuery($data, $randomEmpCode);
                return $createUser ? true : false;
            }
            return false;
        }
        catch(Exception $e){
            Log::error([
                'status' => false,
                'message' => "An error occured while registering the user service",
                'data' => $e->getMessage()
            ]);
            return false;
        }
    }
}
<?php 

namespace App\Services;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
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

    public function userLoginService($data = []){
        try{
            $getUser = (new User())->fetchUserQuery($data['email'] ?? '');
            if(!empty($getUser)){
                if(!Hash::check($data['password'], $getUser->password)){
                    return [
                        'status' => false,
                        'message' => "Input password for the user is incorrect !",
                        'data' => []
                    ];
                }

                $token = $getUser->createToken('auth_token')->plainTextToken;
                return [
                    'status' => true,
                    'message' => "User logged in successfully",
                    'data' => [
                        'user_email' => $getUser->email ?? '',
                        'user_bearer_token' => $token ?? ''
                    ]
                ];
            }
            return [
                'status' => false,
                'message' => "User not found for entered email address !",
                'data' => []
            ];
        }
        catch(Exception $e){
            Log::error([
                'status' => false,
                'message' => "An error occured during user login service",
                'data' => $e->getMessage()
            ]);
        }
    }
}
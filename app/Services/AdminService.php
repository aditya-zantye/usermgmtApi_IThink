<?php 

namespace App\Services;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class AdminService
{
    public function fetchAllUsersService(){
        try{
            $allUsers = [];
            $getAllUsers = Cache::remember("all_users_list", 60 * 60, function() {
                return (new User())->fetchAllUsersQuery();
            });
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

    public function updateUserService($data = [], $employee_code){
        try{
            $fetchUser = (new User())->fetchUserQuery(null, $employee_code);
            if(!$fetchUser){
                return [
                    'status' => false,
                    'message' => "User with employee_code ".$employee_code." not found",
                    'data' => []
                ];
            }
            $allowed_fields = ['name', 'email', 'password'];
            $filtered_data = array_filter($data, function($key) use($allowed_fields){
                return in_array($key, $allowed_fields);
            }, ARRAY_FILTER_USE_KEY);

            if(empty($filtered_data)){
                return [
                    'status' => false,
                    'message' => "No valid input fields provided for update",
                    'data' => []
                ];
            }

            foreach($filtered_data as $key => $value){
                if(!empty($value)){
                    $fetchUser->$key = $key === 'password' ? bcrypt($value) : $value;     
                }
            }

            if($fetchUser->save()){
                Cache::forget('all_users_list');
                return [
                    'status' => true,
                    'message' => "User with employee code ".$employee_code." updated successfully",
                    'data' => []
                ];
            }

            return [
                'status' => false,
                'message' => "User updation for employee code ".$employee_code." failed",
                'data' => []
            ];

        }
        catch(Exception $e){
            Log::error("An error occured while updating the employee details: ".$e->getMessage());
            return [];
        }
    }
}
<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';
    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
        'employee_code',
        'role'
    ];


    public function registerUserQuery($data = [], $random_emp_code = null){
        try{
            if(!empty($data)){
                $create_user = self::create([
                    'employee_code' => $random_emp_code ?? '',
                    'name' => $data['name'] ?? '',
                    'email' => $data['email'] ?? '',
                    'password' => Hash::make($data['password']) ?? '',
                ]);
                return $create_user ? true : false;
            }
            return false;
        }
        catch(Exception $e){
            Log::error([
                'status' => false,
                'message' => "Something went wrong while registering the user: ".$e->getMessage()
            ]);
            return false;
        }
    }

    public function fetchUserQuery($email = null, $employee_code = null){
        try{
            return self::where('email', $email)
            ->orWhere('employee_code', $employee_code)
            ->where('status',1)
            ->first();
        }
        catch(Exception $e){
            Log::error([
                'status' => false,
                'message' => "Something went wrong while fetching the user: ".$e->getMessage()
            ]);
            return collect();
        }
    }

    public function fetchAllUsersQuery(){
        try{
            return self::where('role','user')->get();
        }
        catch(Exception $e){
            Log::error("Something went wrong while fetching all users query: ".$e->getMessage());
            return collect();
        }
    }


}

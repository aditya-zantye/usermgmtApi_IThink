<?php

namespace App\Http\Controllers;

use App\Services\AdminService;
use App\Validators\ApiValidator;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    protected $adminService;

    public function __construct(AdminService $adminService) {
        $this->adminService = $adminService;
    }

    public function viewAllUsersMethod(){
        try{
            $getAllUsers = $this->adminService->fetchAllUsersService();
            return response()->json([
                'status' => true,
                'message' => !empty($getAllUsers) ? "Employee list fetched successfully" : "No employee data found",
                'data' => $getAllUsers
            ]);
        }
        catch(Exception $e){
            Log::error("Something went wrong while displaying user list: ".$e->getMessage());
            return response()->json([
                'status' => false,
                'message' => "Something went wrong while showing user list",
                'data' => []
            ],500);
        }
    }

    public function updateUserMethod(Request $request){
        try{

            $validator = ApiValidator::validateUpdateUser($request->all());
            if($validator->fails()){
                return response()->json([
                    'status' => false,
                    'message' => "Update user validation failed",
                    'data' => $validator->errors()
                ]);
            }

            $data = [
                'name' => $request->input('name') ?? '',
                'email' => $request->input('email') ?? '',
                'password' => $request->input('password') ?? ''
            ];
            $employee_code = $request->input('employee_code') ?? '';
            $updateUser = $this->adminService->updateUserService($data, $employee_code);
            return response()->json([
                'status' => $updateUser['status'],
                'message' => $updateUser['message'],
                'data' => $updateUser['data']
            ]);
        }
        catch(Exception $e){
            Log::error("Something whent wrong while updating the user: ".$e->getMessage());
            return response()->json([
                'status' => false,
                'message' => "Something went wrong while updating the user",
                'data' => []
            ],500);
        }
    }
}

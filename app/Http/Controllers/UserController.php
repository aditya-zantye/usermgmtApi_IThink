<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Auth;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    public function userDetailMethod(Request $request){
        try{

            $employee_code = $request->input('employee_code') ?? Auth::user()->employee_code;
            $employeeRole = Auth::user()->role;
            if($employeeRole !== 'admin' && $employee_code !== Auth::user()->employee_code){
                return response()->json([
                    'status' => false,
                    'message' => "You are not allowed to view profile of other user",
                    'data' => []
                ],401);
            }

            $getUserDetails = $this->userService->fetchUserDetailsService($employee_code);
            return response()->json([
                'status' => !empty($getUserDetails) ? true : false,
                'message' => !empty($getUserDetails) ? "User details fetched successfully" : "Failed to fetch user details",
                'data' => $getUserDetails
            ]);
        }
        catch(Exception $e){
            Log::error([
                'status' => false,
                'message' => "An error occured while fetching user details",
                'data' => $e->getMessage()
            ]);

            return response()->json([
                'status' => false,
                'message' => "An error occured while fetching user details",
                'data' => $e->getMessage()
            ],500);
        }
    }
}

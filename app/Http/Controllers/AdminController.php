<?php

namespace App\Http\Controllers;

use App\Services\AdminService;
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
}

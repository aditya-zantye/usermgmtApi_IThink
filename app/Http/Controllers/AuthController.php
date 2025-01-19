<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService) {
        $this->authService = $authService;
    }

    // USER-REGISTRATION
    public function userCreateMethod(Request $request){
        try{
            $data = [
                'name' => $request->input('name') ?? '',
                'email' => $request->input('email') ?? '',
                'password' => $request->input('password') ?? ''
            ];
            $createUser = $this->authService->userRegisterService($data);
            return response()->json([
                'status' => $createUser,
                'message' => $createUser ? "User created successfully" : "Something went wrong while creating user",
                'data' => []
            ]);
        }
        catch(Exception $e){
            Log::error([
                'status' => false,
                'message' => "An error occured while registering the user",
                'data' => $e->getMessage()
            ]);

            return response()->json([
                'status' => false,
                'message' => "An error occured while registering the user",
                'data' => $e->getMessage()
            ]);
        }
    }

    // USER-LOGIN
    public function userLoginMethod(Request $request){
        try{
            $data = [
                'email' => $request->input('email') ?? '',
                'password' => $request->input('password') ?? ''
            ];
            $loginUser = $this->authService->userLoginService($data);
            return response()->json([
                'status' => $loginUser['status'],
                'message' => $loginUser['message'],
                'data' => $loginUser['data']
            ]);

        }
        catch(Exception $e){
            Log::error([
                'status' => false,
                'message' => "Something went wrong while logging in user",
                'data' => $e->getMessage()
            ]);

            return response()->json([
                'status' => false,
                'message' => "Something went wrong while logging in user",
                'data' => $e->getMessage()
            ],500);
        }
    }

}

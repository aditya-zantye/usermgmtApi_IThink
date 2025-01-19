<?php 

namespace App\Validators;

use Illuminate\Support\Facades\Validator;

class ApiValidator
{
    public static function validateUserRegistration($data){
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => [
                'required',
                'string',
                'confirmed',
                'min:6',
                'max:15',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{6,15}$/'
            ]
        ];

        $messages = [
            'name.required' => 'Employee name is required',
            'email.required' => 'Employee email is required',
            'email.unique' => 'Employee email is already registered',
            'password.required' => 'The password field is required.',
            'password.min' => 'The password must be at least 6 characters.',
            'password.max' => 'The password may not be greater than 15 characters.',
            'password.regex' => 'The password must include at least one uppercase letter, one lowercase letter, one digit, and one special character.',
            'password.confirmed' => 'The password confirmation does not match.',
        ];

        return Validator::make($data,$rules,$messages);
    }

    public static function validateUpdateUser($data){
        $rules = [
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:users,email',
            'password' => [
                'nullable',
                'string',
                'min:6',
                'max:15',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{6,15}$/'
            ]
        ];

        $messages = [
            'email.unique' => 'Employee email is already registered',
            'password.min' => 'The password must be at least 6 characters.',
            'password.max' => 'The password may not be greater than 15 characters.',
            'password.regex' => 'The password must include at least one uppercase letter, one lowercase letter, one digit, and one special character.',
        ];

        return Validator::make($data,$rules,$messages);
    }
}
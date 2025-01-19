<?php

use Illuminate\Support\Str;

if(!function_exists('generate_employee_code')){
    function generate_employee_code(){
        return strtoupper(Str::random(6));
    }
}
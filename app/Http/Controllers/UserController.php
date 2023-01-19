<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function index(Request $request)
    {
        
        return response('hello');
        return auth('sanctum')->user();
    }

    public function edit(Request $request) 
    {
        return $request->user();
    }

}

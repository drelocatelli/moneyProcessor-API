<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{

    public function index()
    {
        return Auth::user();
    }

    public function update() 
    {
        $validator = Validator::make(Request::all(), [
            'password' => 'required',
            'new_password' => 'required',
            'email' => 'required|email'
        ]);
        
        $errorMsg = response()->json(['message' => 'Não foi possível atualizar os dados'] , 401);

        if($validator->fails()) {
            return $errorMsg;
        }

        try {
            $user = User::where('email', Request::get('email'))->first();
            $validateUser = Hash::check(Request('password'), $user->password);
    
            if($validateUser) {
                $user->password = Hash::make(Request::get('new_password'));
                $user->save();
    
                return response()->json(['message' => 'Dados atualizados com sucesso.'], 200);
    
            } else {
                return $errorMsg;
            }

        } catch(\Throwable $th) {
            return response()->json([
                'message' => 'Ocorreu um erro inesperado.',
                'errors' => $th->getMessage()
            ], 500);
        }

        
    }

}

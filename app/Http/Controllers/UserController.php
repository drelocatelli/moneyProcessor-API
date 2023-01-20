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
            'password' => 'string',
            'new_password' => 'string',
            'email' => 'email',
            'salary' => 'numeric'
        ]);
        
        $errorMsg = 'Não foi possível atualizar os dados';

        if($validator->fails()) {
            return response()->json(['mesage' => $errorMsg, 'errors' => $validator->errors()], 401);
        }

        try {
            $user = User::where('email', Request::get('email'))->first();
            $validateUser = Hash::check(Request('password'), $user->password);
    
            if($validateUser) {
                $user->salary = Request::get('salary') ?: $user->salary;
                if(Request::get('new_password')) {
                    $user->password = Hash::make(Request::get('new_password'));
                }
                $user->save();
    
                return response()->json(['message' => 'Dados atualizados com sucesso.'], 200);
    
            } else {
                return response()->json(['mesage' => "$errorMsg. Senha inválida"], 401);
            }

        } catch(\Throwable $th) {
            return response()->json([
                'message' => 'Ocorreu um erro inesperado.',
                'errors' => $th->getMessage()
            ], 500);
        }

        
    }

}

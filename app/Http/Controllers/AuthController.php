<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Request;

class AuthController extends Controller
{
    public function register()
    {

        try {
            $validateUser = Validator::make(Request::all(), [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required',
                'repeat_password' => 'required'
            ]);

            if($validateUser->fails()) {
                return response()->json([
                    'message' => 'Ocorreu um erro ao se registrar',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            if(Request::get('repeat_password') !== Request::get('password')) {
                return response()->json([
                    'message' => 'Senhas não conferem.'
                ]);
            }

            $user = User::create([
                'name' => Request::get('name'),
                'email' => Request::get('email'),
                'password' => Hash::make(Request::get('password'))
            ]);

            return response()->json([
                'message' => 'Registro realizado com sucesso!',
                'token' => $user->createToken('API TOKEN')->plainTextToken
            ], 201);
            
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function login() 
    {
        
        try {
            $validateUser = Validator::make(Request::all(), [
                'email' => 'required|email',
                'password' => 'required',
            ]);
    
            if($validateUser->fails()) {
                return response()->json([
                    'message' => 'Não foi possível fazer login',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $attempt = !(Auth::attempt(Request::only(['email', 'password']), false));
    
            if($attempt) {
                return response()->json([
                    'message' => 'E-mail e senha não conferem'
                ], 401);
            }
    
            $user = User::where('email', Request::get('email'))->first();
            return response()->json([
                'message' => 'Usuário autenticado',
                'token' => $user->createToken('API TOKEN')->plainTextToken
            ], 200);
        } catch(\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
        
    }
    
}

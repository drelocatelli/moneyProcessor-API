<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {

        try {
            $validateUser = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required'
            ]);

            if($validateUser->fails()) {
                return response()->json([
                    'message' => 'Ocorreu um erro ao se registrar',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $user = User::create([
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'password' => Hash::make($request->get('password'))
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

    public function login(Request $request) 
    {
        
        try {
            $validateUser = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required',
            ]);
    
            if($validateUser->fails()) {
                return response()->json([
                    'message' => 'Não foi possível fazer login',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $attempt = !(Auth::attempt($request->only(['email', 'password']), false));
    
            if($attempt) {
                return response()->json([
                    'message' => 'E-mail e senha não conferem'
                ], 401);
            }
    
            $user = User::where('email', $request->get('email'))->first();
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

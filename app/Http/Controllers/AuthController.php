<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{

    public function __construct(
        private User $user
    ) 
    {}
    
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed'],
        ]);

        $user = $this->user->query()->create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password'))
        ]);

        return response()->json([
            'message' => 'Registro realizado com sucesso!',
            'token' => $user->createToken('API TOKEN')->plainTextToken
        ], Response::HTTP_CREATED);
    }

    public function login(LoginRequest $request)
    {
        if (!Auth::attempt($request->validated())) {
            return response()->json(['message' => 'E-mail e senha não conferem'], 401);
        }

        $user = $this->user->where('email', $request->get('email'))->first();

        return response()->json([
            'message' => 'Usuário autenticado',
            'token' => $user->createToken('API TOKEN')->plainTextToken
        ]);
    }

    public function details(Request $request)
    {

        return response()->json(Auth::user());
    }
    
}

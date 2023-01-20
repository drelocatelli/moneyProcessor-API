<?php

namespace App\Http\Controllers;

use App\Models\Expenses;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ExpensesController extends Controller
{

    private $expenses;
    
    public function __construct(Expenses $expenses) {
        $this->expenses = $expenses;
    }
    
    public function index()
    {

        $userId = Auth::id();
        $expenses = Expenses::where('user_id', $userId)->simplePaginate(15);
        
        return response()->json($expenses);
    }

    public function create(Request $request)
    {
        
        $validate = Validator::make($request->all(), [
            'title' => 'required',
            'total' => 'required|numeric'
        ]);

        if($validate->fails()) {
            return response()->json(['message' => 'Não foi possível cadastrar despesa', 'errors' => $validate->errors()], 422);
        }

        $data = [
            'user_id' => Auth::id(),
            ...$validate->validated()
        ];

        // return response()->json($data);

        try {

            $this->expenses->create($data);

            return response()->json(['message' => 'despesa cadastrada'], 201);
        } catch(\Throwable $err) {
            return response()->json([
                'message' => $err->getMessage()
            ], 500);
        }

    }

    public function update()
    {
        
    }

    public function delete()
    {
        
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Revenue\Revenue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use TheSeer\Tokenizer\Exception;
use Request;


class RevenuesController extends Controller
{
    
    private $revenues;
    
    public function __construct(Revenue $revenues) {
        $this->revenues = $revenues;
    }
    
    public function index()
    {

        $userId = Auth::id();
        $revenues = $this->revenues->where('user_id', $userId)->simplePaginate(15);
        
        return response()->json($revenues);
    }

    public function create()
    {
        
        $validate = Validator::make(Request::all(), [
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

        try {

            $this->revenues->create($data);

            return response()->json(['message' => 'despesa cadastrada'], 201);
        } catch(\Throwable $err) {
            return response()->json([
                'message' => $err->getMessage()
            ], 500);
        }

    }

    public function update()
    {
        $validator = Validator::make(Request::all(),[
            'id' => 'required',
            'title' => 'nullable',
            'total' => 'numeric|nullable'
        ]);

        if($validator->fails()) {
            return response()->json(['message' => 'Não foi possível alterar despesa', 'errors' => $validator->errors()], 401);
        }
        try {

            $this->revenues
                ->where('id', Request::get('id'))
                ->update([
                    'id' => Request::get('id'),
                    'title' => Request::get('title') ?: DB::raw('title'),
                    'total' => Request::get('total') ?: DB::raw('total'),
                ]);
            $data = $this->revenues
                    ->where('id', Request::get('id'))->first();
                        
            return response()->json(['message' => 'Despesa alterada!', 'data' => $data]);
        } catch(\Throwable $err) {
            return response()->json([
                'message' => $err->getMessage()
            ], 500);
        }
        
    }

    public function delete()
    {
        $validator = Validator::make(Request::all(), [
            'id' => 'required'
        ]);

        if($validator->fails()) {
            return response()->json(['message' => 'Não foi possível deletar despesa', 'errors' => $validator->errors()], 401);
        }

        try {

            $find = $this->revenues
                ->where('id', Request::get('id'))
                ->first();

            if($find) {
                $this->revenues
                    ->where('id', Request::get('id'))->delete();
            } else {
                throw new Exception('Essa despesa não existe');
            }

            return response()->json(['message' => 'Despesa deletada!'], 200);
        } catch(\Throwable $err) {
            return response()->json([
                'message' => $err->getMessage()
            ], 500);
        }
        
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Revenue\Revenue;
use CreateRevenueRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use RevenueRepository;
use TheSeer\Tokenizer\Exception;
use Request;
use Symfony\Component\HttpFoundation\Response;

class RevenuesController extends Controller
{
    
    public function __construct(
        private RevenueRepository $repository,
        private Revenue           $revenue
    ) {
    }
    
    public function index(): JsonResponse
    {

        return response()->json($this->repository->paginateByUserId(Auth::id()));
    }

    public function create(CreateRevenueRequest $request)
    {
        $this->repository->create(Auth::id(), $request->validated());

        return response()->json(['message' => 'receita cadastrada'], Response::HTTP_CREATED);
    }

    public function update()
    {
        $validator = Validator::make(Request::all(),[
            'id' => 'required',
            'title' => 'nullable',
            'total' => 'numeric|nullable'
        ]);

        if($validator->fails()) {
            return response()->json(['message' => 'Não foi possível alterar receita', 'errors' => $validator->errors()], 401);
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
                        
            return response()->json(['message' => 'Receita alterada!', 'data' => $data]);
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
            return response()->json(['message' => 'Não foi possível deletar receita', 'errors' => $validator->errors()], 401);
        }

        try {

            $find = $this->revenues
                ->where('id', Request::get('id'))
                ->first();

            if($find) {
                $this->revenues
                    ->where('id', Request::get('id'))->delete();
            } else {
                throw new Exception('Essa receita não existe');
            }

            return response()->json(['message' => 'Receita deletada!'], 200);
        } catch(\Throwable $err) {
            return response()->json([
                'message' => $err->getMessage()
            ], 500);
        }
        
    }
}

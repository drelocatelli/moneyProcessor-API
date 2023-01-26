<?php

namespace App\Http\Controllers;

use App\Http\Requests\Expenses\CreateExpenseRequest;
use App\Models\Expenses\Expenses;
use App\Repositories\ExpenseRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Request;
use Symfony\Component\HttpFoundation\Response;
use TheSeer\Tokenizer\Exception;

class ExpensesController extends Controller
{


    public function __construct(
        private ExpenseRepository $repository,
        private Expenses          $expenses
    )
    {
    }

    public function index(): JsonResponse
    {
        return response()->json($this->repository->paginateByUserId(Auth::id()));
    }

    public function create(CreateExpenseRequest $request)
    {
        $this->repository->create(Auth::id(), $request->validated());

        return response()->json(['message' => 'despesa cadastrada'], Response::HTTP_CREATED);
    }

    public function update()
    {
        $validator = Validator::make(Request::all(), [
            'id' => 'required',
            'title' => 'nullable',
            'total' => 'numeric|nullable'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Não foi possível alterar despesa', 'errors' => $validator->errors()], 401);
        }
        try {

            $this->expenses
                ->where('id', Request::get('id'))
                ->update([
                    'id' => Request::get('id'),
                    'title' => Request::get('title') ?: DB::raw('title'),
                    'total' => Request::get('total') ?: DB::raw('total'),
                ]);
            $data = $this->expenses
                ->where('id', Request::get('id'))->first();

            return response()->json(['message' => 'Despesa alterada!', 'data' => $data]);
        } catch (\Throwable $err) {
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

        if ($validator->fails()) {
            return response()->json(['message' => 'Não foi possível deletar despesa', 'errors' => $validator->errors()], 401);
        }

        try {

            $find = $this->expenses
                ->where('id', Request::get('id'))
                ->first();

            if ($find) {
                $this->expenses
                    ->where('id', Request::get('id'))->delete();
            } else {
                throw new Exception('Essa despesa não existe');
            }

            return response()->json(['message' => 'Despesa deletada!'], 200);
        } catch (\Throwable $err) {
            return response()->json([
                'message' => $err->getMessage()
            ], 500);
        }

    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\Expenses\CreateExpenseRequest;
use App\Http\Requests\Expenses\DeleteExpenseRequest;
use App\Models\Expenses\Expenses;
use App\Repositories\ExpenseRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\Expenses\UpdateExpenseRequest;

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

    public function update(UpdateExpenseRequest $request)
    {
        $this->repository->update(Auth::id(), $request->get('id'),$request->validated());

        return response()->json(['message' => 'despesa atualizada']);
    }

    public function delete(DeleteExpenseRequest $request)
    {

        $this->repository->delete(Auth::id(), $request->get('id'));

        return response()->json(['message' => 'despeda deletada']);
    }
}

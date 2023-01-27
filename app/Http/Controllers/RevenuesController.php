<?php

namespace App\Http\Controllers;

use App\Http\Requests\Revenues\CreateRevenueRequest;
use App\Http\Requests\Revenues\DeleteRevenueRequest;
use App\Http\Requests\Revenues\UpdateRevenueRequest;
use App\Models\Revenue\Revenue;
use App\Repositories\RevenueRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
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

    public function update(UpdateRevenueRequest $request)
    {
        $this->repository->update(Auth::id(), $request->get('id'), $request->validated());

        return response()->json(['message' => 'receita atualizada']);
    }

    public function delete(DeleteRevenueRequest $request)
    {
        $this->repository->delete(Auth::id(), $request->get('id'));

        return response()->json(['message' => 'receita deletada']);
    }
}

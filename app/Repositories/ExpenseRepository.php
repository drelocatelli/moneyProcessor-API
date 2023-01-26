<?php

namespace App\Repositories;

use App\Models\Expenses\Expenses;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;

class ExpenseRepository
{
    public function __construct(private Expenses $model)
    {
    }

    public function paginateByUserId(string $userId): Paginator
    {
        return $this->model
            ->newQuery()
            ->where('user_id', $userId)
            ->orderByDesc('created_at')
            ->simplePaginate(15);
    }

    public function create(string $id, array $payload): Expenses
    {
        $payload['user_id'] = $id;
        return $this->model
            ->create($payload);
    }
}

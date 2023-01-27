<?php

namespace App\Repositories;

use App\Models\Revenue\Revenue;
use Illuminate\Contracts\Pagination\Paginator;

class RevenueRepository 
{
    public function __construct(private Revenue $model) {
    }

    public function paginateByUserId(string $userId): Paginator
    {
        return $this->model
            ->newQuery()
            ->where('user_id', $userId)
            ->orderByDesc('created_at')
            ->simplePaginate(15);
    }

    public function create(string $userId, array $payload): Revenue 
    {
        $payload['user_id'] = $userId;
        return $this->model->create($payload);
    }
    
    public function update(string $userId, string $id, array $payload) 
    {
        return $this->model
                ->where('user_id', $userId)
                ->where('id', $id)
                ->update($payload);
    }

    public function delete(string $userId, string $id)
    {
        return $this->model
            ->where('user_id', $userId)
            ->where('id', $id)
            ->delete();
    }
    
}
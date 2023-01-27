<?php
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

    public function create(string $id, array $payload): Revenue 
    {
        $payload['user_id'] = $id;
        return $this->model->create($payload);
    }
    
}
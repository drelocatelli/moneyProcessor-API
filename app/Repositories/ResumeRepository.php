<?php

namespace App\Repositories;

use App\Models\Expenses\Expenses;
use App\Models\Revenue\Revenue;
use Illuminate\Foundation\Auth\User;

class ResumeRepository
{
    public function __construct(
        private Expenses $expensesModel,
        private Revenue $revenueModel
    ) {}

    public function getSalary(string $userId)
    {
        return User::where('id', $userId)->first()->salary;
    }

    public function getRevenues(array $payload)
    {
        $revenues = 0;

        $total = $this->revenueModel
            ->when($payload, function ($query) use ($payload) {
                return $query->whereBetween('updated_at', $payload);
            })
            ->orderBy('updated_at', 'desc')
            ->get('total');
        
        foreach($total as $value) {
            $revenues += $value->total;
        }

        $average = ($revenues === 0 ||  $total === 0) ? 0 : $revenues / count($total);

        return [
            'total' => $revenues,
            'quantity' => count($total),
            'average' => $average
        ];
        
    }

    public function getExpenses(array $payload)
    {

        $expenses = 0;
        $total = $this->expensesModel
            ->when($payload, function ($query) use ($payload) {
                return $query->whereBetween('updated_at', $payload);
            })
            ->orderBy('updated_at', 'desc')
            ->get('total');

        foreach($total as $value) {
            $expenses += $value->total;
        }

        $average = ($expenses === 0 ||  $total === 0) ? 0 : $expenses / count($total);

        return [
            'total' => $expenses,
            'quantity' => count($total),
            'average' => $average
        ];
        
    }

    public function getResume(string $userId, array $payload)
    {

        $balance = $this->getSalary($userId) + $this->getRevenues($payload)["total"] - $this->getExpenses($payload)["total"];
        
        return [
            'salary' => $this->getSalary($userId),
            'balance' => $balance,
            'status' => $balance >= 0 ? 'positive' : 'negative',
            'revenues' => $this->getRevenues($payload),
            'expenses' => $this->getExpenses($payload),
        ];
    }
    
}

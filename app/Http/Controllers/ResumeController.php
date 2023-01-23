<?php

namespace App\Http\Controllers;

use App\Models\Expenses\Expenses;
use App\Models\Revenue\Revenue;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Request;

class ResumeController extends Controller
{

    public function index()
    {

        $requestedDate = Request::get('date');
        
        $expenses = Expenses::where('user_id', Auth::id());
        $revenues = Revenue::where('user_id', Auth::id());
        
        if($requestedDate) {
            $expenses = $expenses->whereDate('created_at', $requestedDate);
            $revenues = $revenues->whereDate('created_at', $requestedDate);
        }
        
        $salary = User::where('id', Auth::id())->first()->salary;

        if($salary == 0) {
            return response()->json(['message' => 'Informe o salário maior que zero.']);
        }
        
        $expenses = $expenses->get();
        $revenues = $revenues->get();

        $expense = 0;
        $expenseN = 0;
        foreach($expenses as $e) {
            $expense = $expense + $e->total;
            $expenseN = $expenseN + 1;
        }

        $expenseArr = [
            'total' => $expense,
            'items' => $expenseN,
            'average' => round($expense / $expenseN)
        ];

        $revenueN = 0;
        $revenue = 0;
        foreach($revenues as $e) {
            $revenue = $revenue + $e->total;
            $revenueN = $revenueN + 1;
        }

        $revenueArr = [
            'total' => $revenue,
            'items' => $revenueN,
            'average' => round($revenue / $revenueN)

        ];

        $balance = $salary + $revenue - $expense;
        $max = floor($salary / 3);

        $data = [
            'salary' => $salary,
            'requested_date' => $requestedDate,
            'expenses' => $expenseArr,
            'revenues' => $revenueArr,
            'balance' => $balance,
            'max' => $max
        ];

        return response()->json(['data' => $data]);
    }
    
}

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

        try {
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

            try {
                $expenseAvg = round($expense / $expenseN);
            } catch(\Throwable $err) {
                $expenseAvg = null;
            }

            $expenseArr = [
                'total' => $expense,
                'items' => $expenseN,
                'average' => $expenseAvg
            ];
            
            $revenueN = 0;
            $revenue = 0;
          
            foreach($revenues as $e) {
                $revenue = $revenue + $e->total;
                $revenueN = $revenueN + 1;
            }

            try {
                $revenueAvg = round($revenue / $revenueN);
            } catch(\Throwable $err) {
                $revenueAvg = null;
            }

            $revenueArr = [
                'total' => $revenue,
                'items' => $revenueN,
                'average' => $revenueAvg
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

            if($expenseN == 0) {
                return response()->json(['message' => 'Você precisa acrescentar uma despesa', 'data' => $data], 200);
            }

            if($revenueN == 0) {
                return response()->json(['message' => 'Você precisa acrescentar uma receita', 'data' => $data], 200);
            }
    
            return response()->json(['data' => $data]);
        } catch(\DivisionByZeroError $e) {
            return response()->json(['message' => 'Não foi possível ', 'errors' => $e->getMessage(), 'line' => $e->getLine()], 422);
        } catch(\Throwable $e) {
            return response()->json(['message' => 'Não foi possível resgatar resumo', 'errors' => $e->getMessage()]);
        }
    }
    
}

<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SummaryController extends Controller
{
    public function financialSummary(){
        $user_id = Auth::id();

        $total_income = User::where('id',$user_id)->get('average_monthly_income');
        $total_expense = Expense::where('user_id',$user_id)->sum('total_amount');

        $balance = $total_expense - $total_expense;
        return response()->json([
            'total_income' => $total_income,
            'total_expenses' => $total_expense,
            'balance' => $balance,
        ]);
    }

    public function customFinancial($start, $end)
    {
        $user_id = Auth::id();
        
        $total_income = User::where('id', $user_id)->value('average_monthly_income');
        
        $total_expense = Expense::where('user_id', $user_id)->whereBetween('date', [$start, $end])->sum('total_amount');
        
        $balance = $total_income - $total_expense;
    
        return response()->json([
            'total_income' => $total_income,
            'total_expenses' => $total_expense,
            'balance' => $balance,
        ]);
    }
    
}

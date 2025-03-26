<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnomaliesController extends Controller
{
    public function anomalies(){
        $month = now()->month;
        $year = now()->year;
        // anjibo l'expenses dyal db
        $currentExpenses = Expense::whereMonth('date',$month)->whereYear('date',$year)->get();
        // jbna jami3 les expenses dyal xhora lfaytin
        $previousExpenses = Expense::whereMonth('date','<',$month)->whereYear('date',$year)->orWhereYear('date','<',$year)->get();

        $user_id = Auth::id();
        $userPastExpenses = $previousExpenses->where('user_id', $user_id)->groupBy('user_id')->map(function ($expenses) {
            return $expenses->sum('total_amount') / max($expenses->count(), 1);
        });

        $anomalies = [];

        foreach($currentExpenses as $expense){
            $averagePastExpenses = $userPastExpenses[$user_id] ?? 0;
            if ($averagePastExpenses > 0){
                $increasePercentage = ($expense->total_amount - $averagePastExpenses) / $averagePastExpenses*100;
                if ($increasePercentage > 50) { 
                    $anomalies[] = [
                        'user_id' => $user_id,
                        'expense_id' => $expense->id,
                        'total_amount' => $expense->total_amount,
                        'increase_percentage' => $increasePercentage,
                        'reason' => 'Augmentation soudaine des dépenses'
                    ];
                }
            }
        }

        foreach ($currentExpenses as $expense){
            $user = User::find($user_id);
            $averageIncome = $user->average_monthly_income ?? 0;

            if ($averageIncome > 0 && $expense->total_amount > ($averageIncome * 0.4)){
                $anomalies[] = [
                    'user_id' => $user_id,
                    'expense_id' => $expense->id,
                    'amount' => $expense->amount,
                    'reason' => 'Dépense dépassant 40% du revenu mensuel'
                ];
            }
        }
        return response()->json(['anomalies' => $anomalies], 200);
    }
}

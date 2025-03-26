<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;

class BalanceController extends Controller
{
    public function balance($groupId)
    {
        $expenses = Expense::where('group_id', $groupId)->with(['payers', 'participants'])->get();
        $balances = [];
        foreach ($expenses as $expense) {
            foreach ($expense->payers as $payer) {
                if (!isset($balances[$payer->id])) {
                    $balances[$payer->id] = 0;
                }
                $balances[$payer->id] += $payer->pivot->amount_paid;
            }
    
            foreach ($expense->participants as $participant) {
                if (!isset($balances[$participant->id])) {
                    $balances[$participant->id] = 0;
                }
                $balances[$participant->id] -= $participant->pivot->amount_owed;
            }
        }
    
        $debtors = [];
        $creditors = [];
    
        foreach ($balances as $userId => $balance) {
            if ($balance < 0) {
                $debtors[] = ['user_id' => $userId, 'amount' => abs($balance)];
            } elseif ($balance > 0) {
                $creditors[] = ['user_id' => $userId, 'amount' => $balance];
            }
        }

        $vairement = [];

        for ($i = 0;$i<count($creditors);$i++){
            for ($j = 0;$j<count($debtors);$j++){
                if($debtors[$j]['amount'] === $creditors[$i]['amount']){
                    $montant = $creditors[$i]['amount'];
                    $vairement[] = [
                        'from' => $debtors[$j]['user_id'], 
                        'to' => $creditors[$i]['user_id'], 
                        'amount' => $montant
                    ];
                    $debtors[$j]['amount'] -= $montant;
                    $creditors[$i]['amount'] -= $montant;
                }
            }
        }
    
        return response()->json([
            'transactions' => $vairement
        ], 200);
    }
    
}   

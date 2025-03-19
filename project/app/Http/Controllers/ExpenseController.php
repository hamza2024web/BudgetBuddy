<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Google\Service\CloudSearch\GroupId;
use Illuminate\Http\Request;
use App\Models\Expense;

class ExpenseController extends Controller
{
    public function index($groupId){
        $group = Group::with(['users','payers','participants'])->findOrFail($groupId);
        return response()->json($group->expenses);
    }
    public function store(Request $request, $groupId)
    {
        $group = Group::findOrFail($groupId);
        $formFields = $request->validate([
            'name' => 'required|string',
            'total_amount' => 'required|numeric|min:0',
            'payers' => 'required|array',
            'payers.*.user_id' => 'exists:users,id',
            'payers.*.amount_paid' => 'required|numeric|min:0',
            'participants' => 'required|array',
            'participants.*.user_id' => 'exists:users,id',
            'participants.*.amount_owed' => 'nullable|numeric|min:0', 
            'split_method' => 'required|in:equal,custom'
        ]);
        $expense = Expense::create([
            'name' => $formFields['name'],
            'group_id' => $group->id,
            'total_amount' => $formFields['total_amount'],
            'split_method' => $formFields['split_method']
        ]);
        foreach ($formFields['payers'] as $payer) {
            $expense->payers()->attach($payer['user_id'], ['amount_paid' => $payer['amount_paid']]);
        }
        if ($formFields['split_method'] === 'equal') {
            $numParticipants = count($formFields['participants']);
            $equalShare = $formFields['total_amount'] / max($numParticipants, 1); 
    
            foreach ($formFields['participants'] as $participant) {
                $expense->participants()->attach($participant['user_id'], ['amount_owed' => $equalShare]);
            }
        } else {
            foreach ($formFields['participants'] as $participant) {
                $expense->participants()->attach($participant['user_id'], ['amount_owed' => $participant['amount_owed']]);
            }
        }
    
        return response()->json($expense->load('payers', 'participants'), 201);
    }
    
    public function destroy($groupId,$expenseId){
        $expense = Expense::where('group_id',$groupId)->findOrFails($expenseId);
        $expense->delete();
        return response()->json(['message' => 'Expense deleted successfully'],200);
    }
}

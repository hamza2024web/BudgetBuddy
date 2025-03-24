<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use App\Models\Budget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BudgetController extends Controller
{
    public function storeBudget(Request $request)
    {

        $formFields = $request->validate([
            'category' => 'required|string|unique:budgets,category',
            'amounts' => 'required|numeric|min:0'
        ]);
    
        $user_id = Auth::id();
        $budget = Budget::create([
            'user_id' => $user_id,
            'category' => $formFields['category'],
            'amounts' => $formFields["amounts"],
            'spent' => 0 
        ]);
    
        return response()->json(['message' => 'Budget created successfully', 'budget' => $budget], 201);
    }
    
    public function Expense(Request $request)
    {
        $formFields = $request->validate([
            'category' => 'required|string|exists:budgets,category',
            'amounts' => 'numeric|min:0',
            'spent' => 'required|numeric|min:0'
        ]);
    
        $budget = Budget::where('category', $formFields['category'])->first();
    
        if (!$budget) {
            return response()->json(['error' => 'Budget not found'], 404);
        }
    
        $budget->spent += $formFields['spent'];
        $budget->save();
    
        if ($budget->spent >= 0.8 * $budget->amount) {
            Alert::create([
                'budget_id' => $budget->id,
                'message' => "Vous avez atteint 80% du budget {$budget->category}.",
                'is_active' => true
            ]);
        }

        return response()->json(['message' => 'Expense added successfully']);
    }
    public function destroy($budgetId){
        $budget = Budget::where('id',$budgetId)->findOrFail($budgetId);
        $budget->delete();
        return response()->json(['message' => 'Budget deleted successfully'],200);
    }
    public function alerts(){
        $alert = Alert::all();
        return $alert;
    }
}

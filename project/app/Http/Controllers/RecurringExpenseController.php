<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RecurringExpense;
use Illuminate\Support\Facades\Auth;

class RecurringExpenseController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'category' => 'required|string',
            'next_due_date' => 'required|date'
        ]);

        $expense = RecurringExpense::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'amount' => $request->amount,
            'category' => $request->category,
            'next_due_date' => $request->next_due_date
        ]);

        return response()->json(['message' => 'Dépense récurrente ajoutée avec succès', 'expense' => $expense]);
    }

    public function index()
    {
        $user_id = Auth::id();
        $expenses = RecurringExpense::where('user_id', $user_id)->get();
        return response()->json($expenses);
    }

    public function destroy($id)
    {
        $user_id = Auth::id();
        $expense = RecurringExpense::where('user_id', $user_id)->find($id);
        if (!$expense) {
            return response()->json(['error' => 'Dépense non trouvée'], 404);
        }

        $expense->delete();
        return response()->json(['message' => 'Dépense supprimée avec succès']);
    }
}

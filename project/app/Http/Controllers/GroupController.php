<?php

namespace App\Http\Controllers;

use App\Http\Resources\GroupCollection;
use App\Models\Expense;
use App\Models\Group as ModelsGroup;
use Google\Service\CloudIdentity\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as FacadesAuth;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $groups = ModelsGroup::with(['users','payeurs'])->get();
        return new GroupCollection($groups);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $formFields = $request->validate([
            'nom' => 'required',
            'devise' => 'required',
            'membres' => 'required|array',
            'membres.*' => 'exists:users,id',
        ]);

        $userId = FacadesAuth::id();
        $group = ModelsGroup::create([
            'nom' => $formFields['nom'],
            'devise' => $formFields['devise'],
            'isAdmin' => $userId,
        ]);

        $allMembres = array_merge($formFields['membres'],[$userId]);
        $group->users()->attach($allMembres);
        return $group;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(ModelsGroup $group)
    {
        // $this->authorize('view',$group);
        return $group;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function settle(Request $request, $groupId)
    {
        $group = ModelsGroup::findOrFail($groupId);
        if (!$group) {
            return response()->json(['error' => 'Group not found'], 404);
        }
        $formFields = $request->validate([
            'payers' => 'required|array',
            'payers.*.user_id' => 'exists:users,id', 
            'payers.*.amount_paid' => 'required|numeric|min:0',
        ]);
        $expense = Expense::where('group_id', $groupId)->first();
        if (!$expense) {
            return response()->json(['error' => 'No expense found for this group'], 404);
        }
        foreach ($formFields['payers'] as $payeur) {
            $expense->payers()->attach($payeur['user_id'], ['amount_paid' => $payeur['amount_paid']]);
        }
        return response()->json($expense->load('payers'), 201);
    }
    
    public function history($groupId)
    {
        $group = ModelsGroup::findOrFail($groupId);
        if (!$group) {
            return response()->json(['error' => 'Group not found'], 404);
        }
        $expenses = Expense::where('group_id', $groupId)->get();
        if ($expenses->isEmpty()) {
            return response()->json(['error' => 'No expenses found for this group'], 404);
        }
        $paiments = $expenses->load('payers');
        return response()->json($paiments);
    }
}

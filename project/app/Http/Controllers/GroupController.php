<?php

namespace App\Http\Controllers;

use App\Http\Resources\GroupCollection;
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
            'depense' => 'required',
            'montant' => 'required',
            'payeur_id' => 'required|array',
            'payeur_id.*' => 'exists:users,id',
            'somme' => 'required',
            'methode_somme' => 'required'
        ]);

        $userId = FacadesAuth::id();
        $group = ModelsGroup::create([
            'nom' => $formFields['nom'],
            'devise' => $formFields['devise'],
            'isAdmin' => $userId,
            'depense' => $formFields['depense'],
            'montant' => $formFields['montant'],
            'somme' => $formFields['somme'],
            'methode_somme' => $formFields['methode_somme']
        ]);

        $allMembres = array_merge($formFields['membres'],[$userId]);
        $group->payeurs()->attach($formFields['payeur_id']);
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
}

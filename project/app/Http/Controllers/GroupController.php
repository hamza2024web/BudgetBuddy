<?php

namespace App\Http\Controllers;

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
        //
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
            'device' => 'required',
            'membres' => 'required|array',
            'users.id' => 'exists:users,id'
        ]);

        $userId = FacadesAuth::id();
        $group = ModelsGroup::create([
            'nom' => $formFields['nom'],
            'device' => $formFields['device'],
            'isAdmin' => $userId
        ]);
        $group->users()->attach($formFields['membres']);
        return $group;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
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

<?php

namespace App\Http\Controllers;

use App\Http\Resources\DependenceCollection;
use App\Models\Dependence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as FacadesAuth;

class DependenceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dependences = Dependence::with('tags')->get();
        return new DependenceCollection($dependences);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreDependenceRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create',Dependence::class);
        $fileds = $request->validate([
            'name' => 'required',
            'tags' => 'required|array',
            'tags.*' => 'exists:tags,id'
        ]);

        $userId = FacadesAuth::id();
        $dependence = Dependence::create([
            'name' => $fileds["name"],
            'user_id' => $userId
        ]);
        $dependence->tags()->attach($fileds['tags']);
        return $dependence;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Dependence  $dependence
     * @return \Illuminate\Http\Response
     */
    public function show(Dependence $dependence)
    {
        $this->authorize('view',Dependence::class);
        return $dependence;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateDependenceRequest  $request
     * @param  \App\Models\Dependence  $dependence
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Dependence $dependence)
    {
        $this->authorize('update',Dependence::class);
        $fileds = $request->validate([
            'name' => 'required',
            'tags' => 'required|array',
            'tags.*' => 'exists:tags,id'
        ]);
        $dependence->update([
            'name' => $fileds['name']
        ]);

        $dependence->refresh();

        $dependence->tags()->sync($fileds['tags']);

        return $dependence;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Dependence  $dependence
     * @return \Illuminate\Http\Response
     */
    public function destroy(Dependence $dependence)
    {
        $this->authorize('delete',Dependence::class);
        $dependence->tags()->detach();
        $dependence->delete();
        return ['message' => 'The dependence has been deleted'];
    }
}

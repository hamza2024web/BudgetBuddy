<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class GroupCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'groups' => $this->collection->transform(function ($group){
                return [
                    'id' =>$group->id,
                    'name' => $group->nom,
                    'isAdmin' => $group->isAdmin,
                    'depense' => $group->depense,
                    'montant' => $group->montant,
                    'somme' => $group->somme,
                    'methode_somme' => $group->methode_somme,
                    'users' => $group->users->pluck('name'),
                    'payeurs' => $group->payeurs->pluck('name'),
                    'devise' => $group->devise,
                    'created_at' => $group->created_at->toDateTimeString()
                ];
            }),
        ];
    }
}

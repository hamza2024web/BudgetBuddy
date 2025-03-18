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
                    'devise' => $group->devise,
                    'isAdmin' => $group->isAdmin,
                    'users' => $group->users->pluck('name'),
                    'created_at' => $group->created_at->toDateTimeString()
                ];
            }),
        ];
    }
}

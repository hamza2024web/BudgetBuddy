<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class DependenceCollection extends ResourceCollection
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
            'dependences' => $this->collection->transform(function($dependence){
                return [
                    'id' => $dependence->id,
                    'name' => $dependence->name,
                    'user_id' => $dependence->user_id,
                    'tags' => $dependence->tags->pluck('name'),
                    'created_at' => $dependence->created_at->toDateTimeString(),
                ];
            }),
            'meta' => [
                'total_dependences' => $this->collection->count(),
            ],
        ];
    }
}

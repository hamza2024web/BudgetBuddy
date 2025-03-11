<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index (){
        return Tag::all();
    }

    public function store (Request $request){
        $fields = $request->validate([
            'name' => 'required'
        ]);

        $tag = Tag::create($fields);
        return [ 'tag' => $tag];
    }
    public function show(Tag $tag){
        return $tag;
    }
    public function update(Request $request,Tag $tag){
        $fields = $request->validate([
            'name' => 'required'
        ]);
        $tag->update($fields);
        return $tag;
    }
    
}

<?php

namespace App\Http\Controllers;
use App\Http\Resources\UserResources;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show(Request $request){
        return new UserResources($request);
    }
}

<?php

namespace App\Http\Controllers;
use App\Http\Resources\UserResources;
use Illuminate\Http\Request;


/**
 * @OA\Get(
 *     path="/api/user",
 *     summary="Get authenticated user information",
 *     tags={"User"},
 *     security={{"sanctum": {}}},
 *     @OA\Response(
 *         response=200,
 *         description="Authenticated user data",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="id", type="integer", example=1),
 *             @OA\Property(property="name", type="string", example="John Doe"),
 *             @OA\Property(property="email", type="string", example="john@example.com"),
 *             @OA\Property(property="created_at", type="string", format="date-time", example="2023-03-13T12:34:56Z"),
 *             @OA\Property(property="updated_at", type="string", format="date-time", example="2023-03-13T12:34:56Z")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthenticated",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Unauthenticated.")
 *         )
 *     )
 * )
 */

class UserController extends Controller
{
    public function show(Request $request){
        return new UserResources($request);
    }
}

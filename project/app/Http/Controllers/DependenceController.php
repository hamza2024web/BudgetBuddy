<?php

namespace App\Http\Controllers;

use App\Http\Resources\DependenceCollection;
use App\Models\Dependence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as FacadesAuth;

/**
 * @OA\Tag(
 *     name="Dependences",
 *     description="API Endpoints for Managing Dependences"
 * )
 */
class DependenceController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/dependences",
     *     summary="Get list of dependences",
     *     tags={"Dependences"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/DependenceCollection")
     *     )
     * )
     */
    public function index()
    {
        $dependences = Dependence::with('tags')->get();
        return new DependenceCollection($dependences);
    }

    /**
     * @OA\Post(
     *     path="/api/dependences",
     *     summary="Create a new dependence",
     *     tags={"Dependences"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "tags"},
     *             @OA\Property(property="name", type="string", example="New Dependence"),
     *             @OA\Property(property="tags", type="array",
     *                 @OA\Items(type="integer", example=1)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Dependence created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Dependence")
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $this->authorize('create', Dependence::class);
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
     * @OA\Get(
     *     path="/api/dependences/{id}",
     *     summary="Get a specific dependence",
     *     tags={"Dependences"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Dependence ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Dependence details",
     *         @OA\JsonContent(ref="#/components/schemas/Dependence")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Dependence not found"
     *     )
     * )
     */
    public function show(Dependence $dependence)
    {
        $this->authorize('view', Dependence::class);
        return $dependence;
    }

    /**
     * @OA\Put(
     *     path="/api/dependences/{id}",
     *     summary="Update a dependence",
     *     tags={"Dependences"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Dependence ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "tags"},
     *             @OA\Property(property="name", type="string", example="Updated Dependence"),
     *             @OA\Property(property="tags", type="array",
     *                 @OA\Items(type="integer", example=2)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Dependence updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Dependence")
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function update(Request $request, Dependence $dependence)
    {
        $this->authorize('update', Dependence::class);

        $fileds = $request->validate([
            'name' => 'required',
            'tags' => 'required|array',
            'tags.*' => 'exists:tags,id'
        ]);

        $dependence->update([
            'name' => $fileds['name']
        ]);

        $dependence->tags()->sync($fileds['tags']);

        return $dependence;
    }

    /**
     * @OA\Delete(
     *     path="/api/dependences/{id}",
     *     summary="Delete a dependence",
     *     tags={"Dependences"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Dependence ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Dependence deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function destroy(Dependence $dependence)
    {
        $this->authorize('delete', Dependence::class);

        $dependence->tags()->detach();
        $dependence->delete();

        return ['message' => 'The dependence has been deleted'];
    }
}
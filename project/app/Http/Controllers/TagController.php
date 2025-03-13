<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Tags",
 *     description="API Endpoints for managing tags"
 * )
 */
class TagController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/tags",
     *     summary="Get all tags",
     *     tags={"Tags"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="tags", type="string")
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        return Tag::all();
    }

    /**
     * @OA\Post(
     *     path="/api/tags",
     *     summary="Create a new tag",
     *     tags={"Tags"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"tags"},
     *             @OA\Property(property="tags", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Tag created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="tag", type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="tags", type="string")
     *             )
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'tags' => 'required',
        ]);

        $tag = Tag::create($fields);
        return ['tag' => $tag];
    }

    /**
     * @OA\Get(
     *     path="/api/tags/{id}",
     *     summary="Get a specific tag",
     *     tags={"Tags"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="tags", type="string")
     *         )
     *     )
     * )
     */
    public function show(Tag $tag)
    {
        return $tag;
    }

    /**
     * @OA\Put(
     *     path="/api/tags/{id}",
     *     summary="Update a tag",
     *     tags={"Tags"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"tags"},
     *             @OA\Property(property="tags", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tag updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="tags", type="string")
     *         )
     *     )
     * )
     */
    public function update(Request $request, Tag $tag)
    {
        $fields = $request->validate([
            'tags' => 'required',
        ]);

        $tag->update($fields);
        return $tag;
    }

    /**
     * @OA\Delete(
     *     path="/api/tags/{id}",
     *     summary="Delete a tag",
     *     tags={"Tags"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tag deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string")
     *         )
     *     )
     * )
     */
    public function destroy(Tag $tag)
    {
        $tag->delete();
        return ['message' => 'The tag is deleted successfully'];
    }
}
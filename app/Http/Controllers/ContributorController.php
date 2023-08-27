<?php

namespace App\Http\Controllers;

use App\Http\Resources\ContributorDetailResource;
use App\Models\Contributor;
use App\Http\Requests\UpdateContributorRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ContributorController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/contributors",
     *     tags={"Contributors"},
     *     summary="Gets all contributors",
     *     operationId="contributorsIndex",
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(
     *             example={
     *                 "data": {
     *                     {
     *                         "id": 1,
     *                         "userName": "Eino Ratke",
     *                         "collectionId": 1,
     *                         "amount": 470.00
     *                     },
     *                     {
     *                         "id": 2,
     *                         "userName": "Leopoldo Lang",
     *                         "collectionId": 3,
     *                         "amount": 920.00
     *                     }
     *                 }
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Operation failed.")
     *         )
     *     ),
     *     security={
     *          {"bearerAuth": {}}
     *      }
     * )
     */
    public function index(): AnonymousResourceCollection
    {
        return ContributorDetailResource::collection(Contributor::all());
    }

    /**
     * @OA\Get(
     *     path="/api/contributors/{id}",
     *     tags={"Contributors"},
     *     summary="Gets contributor by id",
     *     operationId="contributorsShow",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  @OA\Property(property="id", type="integer", example=1),
     *                  @OA\Property(property="userName", type="string", example="Eino Ratke"),
     *                  @OA\Property(property="collectionId", type="integer", example=2),
     *                  @OA\Property(property="amount", type="float", example=745.00)
     *              )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Operation feiled.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Invalid operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Contributor not found.")
     *         )
     *     ),
     *     security={
     *          {"bearerAuth": {}}
     *      }
     * )
     */
    public function show(int $id): ContributorDetailResource|JsonResponse
    {
        $contributor = Contributor::find($id);

        if ($contributor === null) {
            return response()->json([
                'message' => 'Contributor not found.'
            ], 404);
        }

        return new ContributorDetailResource($contributor);
    }

    /**
     * @OA\Put(
     *     path="/api/contributors/{id}",
     *     tags={"Contributors"},
     *     summary="Updates contributor",
     *     operationId="contributorsUpdate",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="userName", type="string", example="Enrico Ratke"),
     *             @OA\Property(property="amount", type="float", example=1000.00)
     *         )
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="contributor updated",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Contributor updated.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Operation feiled.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Invalid operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Contributor not found.")
     *         )
     *     ),
     *     security={
     *          {"bearerAuth": {}}
     *      }
     * )
     */
    public function update(UpdateContributorRequest $request, int $id): JsonResponse
    {
        $contributor = Contributor::find($id);

        if ($contributor === null) {
            return response()->json([
                'message' => 'Contributor not found.'
            ], 404);
        }

        $contributor->update($request->all());

        return response()->json([
            'message' => 'Contributor updated.'
        ], 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/contributors/{id}",
     *     tags={"Contributors"},
     *     summary="Deletes contributor",
     *     operationId="contributorsDestroy",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Operation feiled.")
     *         )
     *     ),
     *     security={
     *          {"bearerAuth": {}}
     *      }
     * )
     */
    public function destroy($id): void
    {
        $contributor = Contributor::find($id);

        if ($contributor === null) {
            return;
        }

        $contributor->delete();
    }
}

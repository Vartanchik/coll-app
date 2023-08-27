<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetCollectionsWithFilterRequest;
use App\Http\Requests\StoreContributorRequest;
use App\Http\Resources\CollectionDetailResource;
use App\Http\Resources\CollectionResource;
use App\Http\Resources\ContributorDetailResource;
use App\Models\Collection;
use App\Http\Requests\StoreCollectionRequest;
use App\Http\Requests\UpdateCollectionRequest;
use App\Models\Contributor;
use App\Services\CollectionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CollectionController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/collections",
     *     tags={"Collections"},
     *     summary="Gets all collections with filtering possibility",
     *     operationId="collectionsIndex",
     *     @OA\Parameter(
     *         name="isLessThanTargetAmount",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="boolean", enum={"0", "1"})
     *     ),
     *     @OA\Parameter(
     *         name="remainingAmount[gt]",
     *         in="query",
     *         description="Greater then. You need choose only one of remainingAmount filtering parameter!",
     *         required=false,
     *         @OA\Schema(type="float")
     *     ),
     *     @OA\Parameter(
     *         name="remainingAmount[gte]",
     *         in="query",
     *         description="Greater then or equal. You need choose only one of remainingAmount filtering parameter!",
     *         required=false,
     *         @OA\Schema(type="float")
     *     ),
     *     @OA\Parameter(
     *         name="remainingAmount[lt]",
     *         in="query",
     *         description="Less then. You need choose only one of remainingAmount filtering parameter!",
     *         required=false,
     *         @OA\Schema(type="float")
     *     ),
     *     @OA\Parameter(
     *         name="remainingAmount[lte]",
     *         in="query",
     *         description="Less then or equal. You need choose only one of remainingAmount filtering parameter!",
     *         required=false,
     *         @OA\Schema(type="float")
     *     ),
     *     @OA\Parameter(
     *         name="remainingAmount[eq]",
     *         in="query",
     *         description="Equal. You need choose only one of remainingAmount filtering parameter!",
     *         required=false,
     *         @OA\Schema(type="float")
     *     ),
     *     @OA\Parameter(
     *         name="remainingAmount[ne]",
     *         in="query",
     *         description="Not equal. You need choose only one of remainingAmount filtering parameter!",
     *         required=false,
     *         @OA\Schema(type="float")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(
     *             example={
     *                 "data": {
     *                     {
     *                         "id": 1,
     *                         "title": "Title",
     *                         "description": "Some description.",
     *                         "targetAmount": 70000.00,
     *                         "link": "https://www.jakubowski.com"
     *                     },
     *                     {
     *                         "id": 2,
     *                         "title": "Title2",
     *                         "description": "Some description2.",
     *                         "targetAmount": 80000.00,
     *                         "link": "https://www.sdfjakubows.com"
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
    public function index(GetCollectionsWithFilterRequest $request): AnonymousResourceCollection
    {
        $collectionService = new CollectionService();

        $remainingAmount = $request->get('remainingAmount');
        $isLessThanTargetAmount = $request->get('isLessThanTargetAmount', false);

        $collections = $collectionService->getCollectionsWithFilters($remainingAmount, $isLessThanTargetAmount);

        return CollectionResource::collection($collections);
    }

    /**
     * @OA\Get(
     *     path="/api/collections/{id}",
     *     tags={"Collections"},
     *     summary="Gets collection by id",
     *     operationId="collectionsShow",
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
     *                  @OA\Property(property="title", type="string", example="NewTitle"),
     *                  @OA\Property(property="description", type="string", example="Some new description."),
     *                  @OA\Property(property="targetAmount", type="float", example=10000.00),
     *                  @OA\Property(property="link", type="string", example="https://www.newcolle.com"),
     *                  @OA\Property(property="contributors", type="array", example={
     *                      {
     *                          "userName": "Eino Ratke",
     *                          "amount": 700.00
     *                      },
     *                      {
     *                          "userName": "Leopoldo Lang",
     *                          "amount": 500.00,
     *                      }
     *                  }, @OA\Items(type="object"))
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
     *             @OA\Property(property="message", type="string", example="Collection not found.")
     *         )
     *     ),
     *     security={
     *          {"bearerAuth": {}}
     *      }
     * )
     */
    public function show(int $id): CollectionDetailResource|JsonResponse
    {
        $collection = Collection::find($id);

        if ($collection === null) {
            return response()->json([
                'message' => 'Collection not found.'
            ], 404);
        }

        return new CollectionDetailResource($collection->loadMissing('contributors'));
    }

    /**
     * @OA\Post(
     *     path="/api/collections",
     *     tags={"Collections"},
     *     summary="Creates new collection",
     *     operationId="collectionsStore",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string", example="NewTitle"),
     *             @OA\Property(property="description", type="string", example="Some new description."),
     *             @OA\Property(property="targetAmount", type="float", example=10000.00),
     *             @OA\Property(property="link", type="string", example="https://www.newcolle.com")
     *         )
     *      ),
     *     @OA\Response(
     *         response=201,
     *         description="collection created",
     *         @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  @OA\Property(property="title", type="string", example="NewTitle"),
     *                  @OA\Property(property="description", type="string", example="Some new description."),
     *                  @OA\Property(property="targetAmount", type="float", example=10000.00),
     *                  @OA\Property(property="link", type="string", example="https://www.newcolle.com"),
     *                  @OA\Property(property="contributors", type="array", example={}, @OA\Items(type="object"))
     *              )
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
    public function store(StoreCollectionRequest $request): CollectionDetailResource|JsonResponse
    {
        $createdCollection = Collection::create($request->all());

        if ($createdCollection === null) {
            return response()->json([
                'message' => 'Collection creation is failed.'
            ], 400);
        }

        return new CollectionDetailResource($createdCollection);
    }

    /**
     * @OA\Post(
     *     path="/api/collections/{id}/contributors",
     *     tags={"Collections"},
     *     summary="Creates new contributor",
     *     operationId="collectionsContributorStore",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="userName", type="string", example="NewTitle"),
     *             @OA\Property(property="amount", type="float", example=750.00)
     *         )
     *      ),
     *     @OA\Response(
     *         response=201,
     *         description="collection created",
     *         @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  @OA\Property(property="id", type="integer", example="15"),
     *                  @OA\Property(property="userName", type="string", example="NewTitle"),
     *                  @OA\Property(property="collectionId", type="integer", example=1),
     *                  @OA\Property(property="amount", type="float", example=750.00)
     *              )
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
    public function contributorStore(StoreContributorRequest $request, int $id): ContributorDetailResource|JsonResponse
    {
        $contributor = $request->all();

        $contributor['collection_id'] = $id;

        $createdContributor = Contributor::create($contributor);

        if ($createdContributor === null) {
            return response()->json([
                'message' => 'Contributor creation is failed.'
            ], 400);
        }

        return new ContributorDetailResource($createdContributor);
    }

    /**
     * @OA\Put(
     *     path="/api/collections/{id}",
     *     tags={"Collections"},
     *     summary="Updates collection",
     *     operationId="collectionsUpdate",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string", example="NewTitle"),
     *             @OA\Property(property="description", type="string", example="Some new description."),
     *             @OA\Property(property="targetAmount", type="float", example=10000.00),
     *             @OA\Property(property="link", type="string", example="https://www.newcolle.com")
     *         )
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="collection updated",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Collection updated.")
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
     *             @OA\Property(property="message", type="string", example="Collection not found.")
     *         )
     *     ),
     *     security={
     *          {"bearerAuth": {}}
     *      }
     * )
     */
    public function update(UpdateCollectionRequest $request, int $id): JsonResponse
    {
        $collection = Collection::find($id);

        if ($collection === null) {
            return response()->json([
                'message' => 'Collection not found.'
            ], 404);
        }

        $collection->update($request->all());

        return response()->json([
            'message' => 'Collection updated.'
        ], 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/collections/{id}",
     *     tags={"Collections"},
     *     summary="Deletes collection",
     *     operationId="collectionsDestroy",
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
    public function destroy(int $id): void
    {
        $collection = Collection::find($id);

        if ($collection === null) {
            return;
        }

        $collection->delete();
    }
}

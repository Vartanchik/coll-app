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
     * Display a listing of the resource.
     *
     * @param GetCollectionsWithFilterRequest $request
     * @return AnonymousResourceCollection
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
     * Store a newly created resource in storage.
     *
     * @param StoreCollectionRequest $request
     * @return CollectionDetailResource|JsonResponse
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
     * Store a newly created contributor in storage.
     *
     * @param StoreContributorRequest $request
     * @param int $id
     * @return ContributorDetailResource|JsonResponse
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
     * Display the specified resource.
     *
     * @param int $id
     * @return CollectionDetailResource|JsonResponse
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
     * Update the specified resource in storage.
     *
     * @param UpdateCollectionRequest $request
     * @param Collection $collection
     * @return void
     */
    public function update(UpdateCollectionRequest $request, collection $collection): void
    {
        $collection->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return void
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

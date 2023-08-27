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
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return ContributorDetailResource::collection(Contributor::all());
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return ContributorDetailResource|JsonResponse
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
     * Update the specified resource in storage.
     *
     * @param UpdateContributorRequest $request
     * @param Contributor $contributor
     * @return void
     */
    public function update(UpdateContributorRequest $request, contributor $contributor): void
    {
        $contributor->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return void
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

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Organization\StoreOrganizationRequest;
use App\Http\Requests\Organization\UpdateOrganizationRequest;
use App\Http\Resources\OrganizationResource;
use App\Models\Organization;
use App\Services\OrganizationService;
use Illuminate\Http\JsonResponse;

class OrganizationController extends Controller
{

    public function __construct(private OrganizationService $organizationService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    // GET /api/organizations
    public function index(): JsonResponse
    {
        $organizations = $this->organizationService->getAll();
        return response()->json(OrganizationResource::collection($organizations));
    }

    /**
     * Store a newly created resource in storage.
     */
    // POST /api/organizations
    public function store(StoreOrganizationRequest $request): JsonResponse
    {
        $organizations = $this->organizationService->store($request->validated());
        return response()->json(new OrganizationResource($organizations), 201);
    }

    /**
     * Display the specified resource.
     */
    // GET /api/organizations/{organization}
    public function show(Organization $organization): JsonResponse
    {
        $this->authorize('view', $organization);
        $organization = $this->organizationService->show($organization);
        return response()->json(new OrganizationResource($organization));
    }

    /**
     * Update the specified resource in storage.
     */
    // PUT /api/organizations/{organization}
    public function update(UpdateOrganizationRequest $request, Organization $organization): JsonResponse
    {
        $this->authorize('update', $organization);
        $organization = $this->organizationService->update($organization, $request->validated());
        return response()->json(new OrganizationResource($organization));
    }

    /**
     * Remove the specified resource from storage.
     */
    // DELETE /api/organizations/{organization}
    public function destroy(Organization $organization): JsonResponse
    {
        $this->authorize('delete', $organization);
        $this->organizationService->destroy($organization);
        return response()->json(['message' => 'Organización eliminada correctamente.']);
    }
}

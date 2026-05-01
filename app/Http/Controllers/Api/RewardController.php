<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reward\StoreRewardRequest;
use App\Http\Requests\Reward\UpdateRewardRequest;
use App\Http\Resources\RewardResource;
use App\Models\Reward;
use App\Services\RewardService;
use Illuminate\Http\JsonResponse;

class RewardController extends Controller
{

    public function __construct(
        private RewardService $rewardService
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    // GET /api/rewards
    public function index(): JsonResponse
    {
        $rewards = $this->rewardService->getAll();
        return response()->json(RewardResource::collection($rewards));
    }

    /**
     * Store a newly created resource in storage.
     */
    // POST /api/rewards
    public function store(StoreRewardRequest $request): JsonResponse
    {
        $reward = $this->rewardService->store($request->validated());
        return response()->json(new RewardResource($reward), 201);
    }

    /**
     * Update the specified resource in storage.
     */
    // PUT /api/rewards/{reward}
    public function update(UpdateRewardRequest $request, Reward $reward): JsonResponse
    {
        $reward = $this->rewardService->update($reward, $request->validated());
        return response()->json(new RewardResource($reward));
    }

    /**
     * Remove the specified resource from storage.
     */
    // DELETE /api/rewards/{reward}
    public function destroy(Reward $reward): JsonResponse
    {
        $this->rewardService->destroy($reward);
        return response()->json(['message' => 'Recompensa eliminada correctamente.']);
    }

    // POST /api/rewards/{reward}/redeem
    public function redeem(Reward $reward): JsonResponse
    {
        $redemption = $this->rewardService->redeem($reward);
        return response()->json($redemption, 201);
    }

    // GET /api/rewards/redemptions
    public function redemptions(): JsonResponse
    {
        $redemptions = $this->rewardService->getRedemptions();
        return response()->json($redemptions);
    }
}

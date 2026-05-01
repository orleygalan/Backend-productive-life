<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DailyTask\StoreDailyTaskRequest;
use App\Http\Requests\DailyTask\UpdateDailyTaskRequest;
use App\Http\Resources\DailyTaskResource;
use App\Models\DailyTask;
use App\Services\DailyTaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DailyTaskController extends Controller
{

    public function __construct(private DailyTaskService $dailyTaskService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    // GET /api/daily-tasks?date=2026-04-24
    public function index(Request $request): JsonResponse
    {
        $dailyTasks = $this->dailyTaskService->getAll($request->query('date'));
        return DailyTaskResource::collection($dailyTasks)->response();
    }

    /**
     * Store a newly created resource in storage.
     */
    // POST /api/daily-tasks
    public function store(StoreDailyTaskRequest $request): JsonResponse
    {
        $dailyTask = $this->dailyTaskService->store($request->validated());
        return response()->json(new DailyTaskResource($dailyTask), 201);
    }

    /**
     * Update the specified resource in storage.
     */
    // PUT /api/daily-tasks/{dailyTask}
    public function update(UpdateDailyTaskRequest $request, DailyTask $dailyTask): JsonResponse
    {
        $dailyTask = $this->dailyTaskService->update($dailyTask, $request->validated());
        return response()->json(new DailyTaskResource($dailyTask));
    }

    /**
     * Remove the specified resource from storage.
     */
    // DELETE /api/daily-tasks/{dailyTask}
    public function destroy(DailyTask $dailyTask): JsonResponse
    {
        $this->dailyTaskService->destroy($dailyTask);
        return response()->json(['message' => 'Tarea eliminada correctamente.']);
    }

    // PATCH /api/daily-tasks/{dailyTask}/complete
    public function complete(DailyTask $dailyTask): JsonResponse
    {
        $dailyTask = $this->dailyTaskService->complete($dailyTask);
        return response()->json(new DailyTaskResource($dailyTask));
    }

    // PATCH /api/daily-tasks/{dailyTask}/uncomplete
    public function uncomplete(DailyTask $dailyTask): JsonResponse
    {
        $dailyTask = $this->dailyTaskService->uncomplete($dailyTask);
        return response()->json(new DailyTaskResource($dailyTask));
    }
}

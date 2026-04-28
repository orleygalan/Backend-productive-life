<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\WeeklyPointsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WeeklyPointsController extends Controller
{
    public function __construct(
        private WeeklyPointsService $weeklyPointsService
    ) {
    }

    // GET /api/weekly-points/current
    public function current(): JsonResponse
    {
        $data = $this->weeklyPointsService->currentWeek();
        return response()->json($data);
    }

    // GET /api/weekly-points/history
    public function history(): JsonResponse
    {
        $data = $this->weeklyPointsService->history();
        return response()->json($data);
    }
}

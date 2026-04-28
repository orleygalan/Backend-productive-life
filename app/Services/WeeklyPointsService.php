<?php

namespace App\Services;

use App\Models\WeeklyPointsSummary;
use App\Models\DailyPointsLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class WeeklyPointsService
{
    // Resumen de la semana actual
    public function currentWeek()
    {
        $weekStart = Carbon::now()->startOfWeek(Carbon::MONDAY)->toDateString();

        $summary = WeeklyPointsSummary::where('user_id', Auth::id())
            ->where('week_start', $weekStart)
            ->first();

        // Puntos por dia de la semana actual para la grafica
        $dailyBreakdown = DailyPointsLog::where('user_id', Auth::id())
            ->whereBetween('log_date', [
                $weekStart,
                Carbon::now()->endOfWeek(Carbon::SUNDAY)->toDateString()
            ])
            ->selectRaw('log_date, SUM(points_earned) as total')
            ->groupBy('log_date')
            ->orderBy('log_date')
            ->get();

        return [
            'summary' => $summary,
            'daily_breakdown' => $dailyBreakdown,
            'can_redeem' => Carbon::today()->isSunday() && $summary?->total_points > 0,
        ];
    }

    // Historial de semanas anteriores
    public function history()
    {
        return WeeklyPointsSummary::where('user_id', Auth::id())
            ->orderByDesc('week_start')
            ->get();
    }
}
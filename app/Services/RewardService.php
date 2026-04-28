<?php

namespace App\Services;

use App\Models\Reward;
use App\Models\RewardRedemption;
use App\Models\WeeklyPointsSummary;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class RewardService
{
    // Mostrar todas las recompensas del usuario 
    public function getAll()
    {
        $reward = Reward::where('user_id', Auth::id())->get();
        return $reward;
    }

    // Crear recompensa 
    public function store(array $data)
    {
        return Reward::create([
            'user_id' => Auth::id(),
            'name' => $data['name'],
            'points_cost' => $data['points_cost'],
        ]);
    }

    // Actualizar recompesa 
    public function update(Reward $reward, array $data)
    {
        $this->checkOwner($reward);
        $reward->update($data);
        return $reward->refresh();
    }

    // ELiminar recompesa
    public function destroy(Reward $reward): void
    {
        $this->checkOwner($reward);
        $reward->delete();
    }

    // Canjear las recompesas solo los domingos 
    public function redeem(Reward $reward)
    {
        $this->checkOwner($reward);

        if (!Carbon::today()->isSunday()) {
            abort(422, 'Solo puedes canjear recompesas los domingos.');
        }

        // obtener resumen de la semana 
        $weekStart = Carbon::now()->startOfWeek(Carbon::MONDAY)->toDateString();
        $summary = WeeklyPointsSummary::where('user_id', Auth::id())
            ->where('week_start', $weekStart)
            ->first();

        if (!$summary) {
            abort(422, 'No tienes puntos acumulados esta semana.');
        }

        // Verifica que tienes puntos 
        if ($summary->total_points < $reward->points_cost) {
            abort(422, "No tienes suficientes puntos. Necesitas {$reward->points_cost} pts y tienes {$summary->total_points} pts.");
        }

        // Descontar puntos del resumen semanal
        $summary->decrement('total_points', $reward->points_cost);

        // Registrar el canje
        return RewardRedemption::create([
            'user_id' => Auth::id(),
            'reward_id' => $reward->id,
            'weekly_summary_id' => $summary->id,
            'redeemed_on' => Carbon::today(),
        ]);
    }

    // Historial de canjes del usuario
    public function getRedemptions()
    {
        return RewardRedemption::where('user_id', Auth::id())
            ->with(['reward', 'weeklySummary'])
            ->orderByDesc('redeemed_on')
            ->get();
    }

    // Verificar que sea el dueño 
    private function checkOwner(Reward $reward): void
    {
        if ($reward->user_id !== Auth::id()) {
            abort(403, 'No tienes permiso para realizar esta acción.');
        }
    }
}
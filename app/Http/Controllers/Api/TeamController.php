<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Team\StoreTeamRequest;
use App\Http\Requests\Team\UpdateTeamRequest;
use App\Models\Organization;
use App\Models\Team;
use Illuminate\Http\Request;
use App\Services\TeamService;

class TeamController extends Controller
{

    public function __construct(private TeamService $teamService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    // GET /api/organizations/{organization}/teams
    public function index(Organization $organization)
    {
        $team = $this->teamService->getAll($organization);
        return response()->json($team);
    }

    /**
     * Store a newly created resource in storage.
     */
    // POST /api/teams
    public function store(StoreTeamRequest $request)
    {
        $team = $this->teamService->store($request->validated());
        return response()->json($team, 201);
    }

    /**
     * Display the specified resource.
     */
    // GET /api/teams/{team}
    public function show(Team $team)
    {
        $team = $this->teamService->show($team);
        return response()->json($team);
    }

    /**
     * Update the specified resource in storage.
     */
    // PUT /api/teams/{team}
    public function update(UpdateTeamRequest $request, Team $team)
    {
        $team = $this->teamService->update($team, $request->validated());
        return response()->json($team);
    }

    /**
     * Remove the specified resource from storage.
     */
    // DELETE /api/teams/{team}
    public function destroy(Team $team)
    {
        $this->teamService->destroy($team);
        return response()->json(['message' => 'Equipo eliminado correctamente.']);
    }

    // POST /api/teams/{team}/members
    public function addMembers(Request $request, Team $team)
    {
        $request->validate([
            'user_id' => ['required', 'uuid', 'exists:users,id'],
            'role' => ['sometimes', 'in:admin,editor,viewer'],
        ]);

        $this->teamService->addMember($team, $request->user_id, $request->role ?? 'editor');

        return response()->json(['message' => 'Miembro agregado correctamente.']);
    }

    // DELETE /api/teams/{team}/members/{user}
    public function removeMember(Team $team, string $userId)
    {
        $this->teamService->removeMember($team, $userId);

        return response()->json(['message' => 'Miembro eliminado correctamente.']);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Project\StoreProjectRequest;
use App\Http\Requests\Project\UpdateProjectRequest;
use App\Models\Project;
use App\Models\Team;
use App\Services\ProjectService;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function __construct(private ProjectService $projectService)
    {
    }

    // GET /api/teams/{team}/projects
    public function index(Team $team)
    {
        $project = $this->projectService->getAll($team);
        return response()->json($project);
    }

    // POST /api/projects
    public function store(StoreProjectRequest $request)
    {
        $project = $this->projectService->store($request->validated());
        return response()->json($project, 201);
    }

    // GET /api/projects/{project}
    public function show(Project $project)
    {
        $project = $this->projectService->show($project);
        return response()->json($project);
    }

    // PUT /api/projects/{project}
    public function update(UpdateProjectRequest $data, Project $project)
    {
        $project = $this->projectService->update($project, $data->validated());
        return response()->json($project);
    }

    // DELETE /api/projects/{project}
    public function destroy(Project $project)
    {
        $this->projectService->destroy($project);
        return response()->json(['message' => 'Proyecto eliminado correctamente.']);
    }
}

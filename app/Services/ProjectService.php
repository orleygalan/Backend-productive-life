<?php

namespace App\Services;

use App\Models\Project;
use App\Models\Team;
use Illuminate\Support\Facades\Auth;

class ProjectService
{
    // Listar los proyectos de un equipo
    public function getAll(Team $team)
    {
        $this->checkMember($team);
        return $team->projects()->get();

    }

    // Crear un proyecto
    public function store(array $data)
    {
        $team = Team::findOrFail($data['team_id']);
        $this->checkMember($team);

        return Project::create([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'status' => 'active',
            'team_id' => $data['team_id']
        ]);

    }

    // Mostrar un proyectos con todas sus tareas 
    public function show(Project $project)
    {
        $this->checkMember($project->team);
        return [
            'project' => $project,
            'tasks' => $project->tasksByStatus(),
        ];
    }

    // Actualizar equipo
    public function update(Project $project, array $data)
    {
        $this->checkMember($project->team);
        $project->update($data);
        return $project->fresh();
    }

    // Eliminar equipo
    public function destroy(Project $project): void
    {
        $this->checkAdminOrOwner($project->team);
        $project->delete();
    }

    // Verificar que el usuario es miembro del equipo
    private function checkMember(Team $team): void
    {
        $isMember = $team->members()
            ->where('user_id', Auth::id())
            ->exists();
        if (!$isMember) {
            abort(403, 'No eres miembro de este equipo.');
        }
    }

    // Verificar que el usuario es admin del equipo o dueño de la organizacion
    private function checkAdminOrOwner(Team $team): void
    {
        $isAdmin = $team->members()
            ->where('user_id', Auth::id())
            ->wherePivot('role', 'admin')
            ->exists();

        $isOwner = $team->organization->owner_id === Auth::id();

        if (!$isAdmin && !$isOwner) {
            abort(403, 'Necesitas ser admin para realizar esta acción.');
        }
    }

}
<?php

namespace App\Services;

use App\Models\Organization;
use App\Models\Team;
use Illuminate\Support\Facades\Auth;


class TeamService
{

    // Mostrar los equipos de una organizacion 
    public function getAll(Organization $organization)
    {
        $this->checkOwner($organization);
        return $organization->teams()->get();
    }
    // Crear un equipo dentro de una organizacion 
    public function store(array $data)
    {
        $organization = Organization::findOrFail($data['organization_id']);

        $this->checkOwner($organization);

        $team = Team::create([
            'name' => $data['name'],
            'organization_id' => $data['organization_id'],
        ]);
        // El creador del equipo se agrega automáticamente como admin
        $team->members()->attach(Auth::id(), ['role' => 'admin']);

        return $team->load('members');
    }


    // Mostrar un equipo con sus miembros y proyectos
    public function show(Team $team)
    {
        $this->checkOwner($team->organization);
        return $team->load(['members', 'projects']);
    }

    // Actualizar un equipo 
    public function update(Team $team, array $data)
    {
        $this->checkOwner($team->organization);
        $team->update($data);
        return $team->refresh();
    }

    // Eliminar un equipo
    public function destroy(Team $team): void
    {
        $this->checkOwner($team->organization);
        $team->delete();
    }

  // Agregar miembro al equipo
    public function addMember(Team $team, string $userId, string $role = 'editor'): void
    {
        $this->checkOwner($team->organization);
        $team->members()->attach($userId, ['role' => $role]);
    }

    // Eliminar miembro del equipo
    public function removeMember(Team $team, string $userId): void
    {
        $this->checkOwner($team->organization);
        $team->members()->detach($userId);
    }

    // Verifica que el usuuario Autenticado sea el dueño de la organizacion
    private function checkOwner(Organization $organization)
    {
        if ($organization->owner_id !== Auth::id()) {
            abort(403, 'No tienes permiso para realizar esta acción.');
        }
    }
}
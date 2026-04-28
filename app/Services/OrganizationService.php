<?php

namespace App\Services;

use App\Models\Organization;
use Illuminate\Support\Facades\Auth;

class OrganizationService
{
    public function getAll()
    {
        return Organization::where('owner_id', Auth::id())->get();
    }

    // crear una organizacion 
    public function store(array $data)
    {
        return Organization::create([
            'name' => $data['name'],
            'owner_id' => Auth::id(),
        ]);
    }

    // Mostrar una organizacion 
    public function show(Organization $organization)
    {
        $this->checkOwner($organization);
        return $organization->load('teams');
    }

    // actualizar una Organization 
    public function update(Organization $organization, array $data)
    {
        $this->checkOwner($organization);
        $organization->update($data);
        return $organization->fresh();
    }

    // Eliminar una organizacion 
    public function destroy(Organization $organization): void
    {
        $this->checkOwner($organization);
        $organization->delete();
    }

    // verifica que el usuario autenticado es el dueño 
    private function checkOwner(Organization $organization)
    {
        if ($organization->owner_id !== Auth::id()) {
            abort(403, 'No tienes permiso para realizar esta acción.');
        }
    }
}
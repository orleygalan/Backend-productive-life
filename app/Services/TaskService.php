<?php
namespace App\Services;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class TaskService
{

    public function getAll(Project $project)
    {
        $this->checkMember($project);
        return $project->tasks()->with('assignedTo')->paginate(20);
    }

    // crear tarea 
    public function store(array $data)
    {
        $project = Project::findOrFail($data['project_id']);
        $this->checkMember($project);

        // Si se asigna a alguien, verificar que es miembro del equipo
        if (isset($data['assigned_to'])) {
            $this->checkAssignedIsMember($project, $data['assigned_to']);
        }

        return Task::create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'project_id' => $data['project_id'],
            'assigned_to' => $data['assigned_to'] ?? null,
            'due_date' => $data['due_date'] ?? null,
            'status' => 'todo',
        ]);
    }

    // Mostrar tarea 
    public function show(Task $task)
    {
        $this->checkMember($task->project);
        return $task->load('assignedTo');

    }
    // Actualizar tarea 
    public function update(Task $task, array $data)
    {
        $this->checkMember($task->project);

        // Si se reasigna, verificar que el nuevo usuario es miembro
        if (isset($data['assigned_to'])) {
            $this->checkAssignedIsMember($task->project, $data['assigned_to']);
        }

        $task->update($data);
        return $task->fresh()->load('assignedTo');
    }

    // Eliminar tarea 
    public function destroy(Task $task): void
    {
        $this->checkAdminOrOwner($task->project);
        $task->delete();
    }

    // Cambiar estado de la tarea (para el kanba drag y drop )
    public function changeStatus(Task $task, string $status)
    {
        $this->checkMember($task->project);
        $task->update(['status' => $status]);
        return $task->fresh();
    }

    // Verifica que el usuario autenticado es miembro del equipo del projecto 
    private function checkMember(Project $project): void
    {

        $isMember = $project->team
            ->members()
            ->where('user_id', Auth::id())
            ->exists();

        if (!$isMember) {
            abort(403, 'NO eres miembro de este equipo');
        }
    }

    // Verificar que el usuario asignado también es miembro del equipo
    private function checkAssignedIsMember(Project $project, string $userId): void
    {
        $isMember = $project->team
            ->members()
            ->where('user_id', $userId)
            ->exists();

        if (!$isMember) {
            abort(403, 'El usuario asignado no es miembro de este equipo.');
        }
    }

    // Verificar que el usuario es admin o dueño para eliminar
    private function checkAdminOrOwner(Project $project): void
    {
        $isAdmin = $project->team
            ->members()
            ->where('user_id', Auth::id())
            ->wherePivot('role', 'admin')
            ->exists();

        $isOwner = $project->team->organization->owner_id === Auth::id();

        if (!$isAdmin && !$isOwner) {
            abort(403, 'Necesitas ser admin para eliminar tareas.');
        }
    }

}
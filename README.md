# ProductiveLife — Backend API
 
API REST construida con **Laravel 11**, **PostgreSQL** y **Laravel Sanctum** para la aplicación de productividad ProductiveLife.
 
---
 
## Stack
 
- **Laravel 11**
- **PostgreSQL**
- **Laravel Sanctum** (autenticación por tokens)
---
 
## Características
 
La app tiene dos modos que el usuario puede alternar en cualquier momento:
 
**Modo Work** — Gestión de proyectos y equipos
- Organizaciones con múltiples equipos
- Roles por equipo: `admin`, `editor`, `viewer`
- Proyectos con tablero Kanban (`todo` → `in_progress` → `done`)
- Asignación de tareas a miembros del equipo
**Modo Life** — Productividad personal
- Tareas diarias con puntos XP por completar
- Acumulación de puntos diaria → resumen semanal
- Canje de recompensas los domingos con puntos semanales
- Sistema de niveles y racha de días activos
---
 
## Estructura del proyecto
 
```
app/
├── Http/
│   ├── Controllers/Api/
│   │   ├── AuthController.php
│   │   ├── OrganizationController.php
│   │   ├── TeamController.php
│   │   ├── ProjectController.php
│   │   ├── TaskController.php
│   │   ├── DailyTaskController.php
│   │   ├── RewardController.php
│   │   └── WeeklyPointsController.php
│   └── Requests/
│       ├── Auth/
│       ├── Organization/
│       ├── Team/
│       ├── Project/
│       ├── Task/
│       ├── DailyTask/
│       └── Reward/
├── Models/
│   ├── User.php
│   ├── Organization.php
│   ├── Team.php
│   ├── Project.php
│   ├── Task.php
│   ├── DailyTask.php
│   ├── DailyPointsLog.php
│   ├── WeeklyPointsSummary.php
│   ├── UserPoints.php
│   ├── Reward.php
│   └── RewardRedemption.php
└── Services/
    ├── AuthService.php
    ├── OrganizationService.php
    ├── TeamService.php
    ├── ProjectService.php
    ├── TaskService.php
    ├── DailyTaskService.php
    ├── RewardService.php
    └── WeeklyPointsService.php
```
 
---
 
## Base de datos
 
### Modo Work
| Tabla | Descripción |
|---|---|
| `users` | Usuarios con campo `mode` (work/life) |
| `organizations` | Organizaciones creadas por un usuario |
| `teams` | Equipos dentro de una organización |
| `team_members` | Tabla pivot users ↔ teams con rol |
| `projects` | Proyectos dentro de un equipo |
| `tasks` | Tareas asignadas dentro de un proyecto |
 
### Modo Life
| Tabla | Descripción |
|---|---|
| `daily_tasks` | Tareas del día con puntos XP |
| `daily_points_log` | Registro de puntos por tarea completada |
| `weekly_points_summary` | Resumen de puntos por semana |
| `user_points` | Total histórico, nivel y racha |
| `rewards` | Recompensas definidas por el usuario |
| `reward_redemptions` | Historial de canjes (solo domingos) |
 
Todas las tablas usan **UUID** como primary key.

---

## Arquitectura
 
Patrón **Service + Controller + FormRequest**:
 
- `FormRequest` → valida los datos entrantes
- `Controller` → recibe la petición y devuelve la respuesta JSON
- `Service` → contiene toda la lógica de negocio
- `Model` → interactúa con la base de datos
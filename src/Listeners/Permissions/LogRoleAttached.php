<?php

namespace Backstage\UserManagement\Listeners\Permissions;

use Spatie\Permission\Events\RoleAttached;
use Backstage\UserManagement\Models\PermissionEventLog;

class LogRoleAttached
{
    public function handle(RoleAttached $event): void
    {
        foreach ($event->rolesOrIds as $role) {
            PermissionEventLog::create([
                'event' => class_basename($event), // e.g. RoleAttached
                'model_type' => get_class($event->model),
                'model_id' => $event->model->getKey(),
                'target_type' => config('permission.models.role'),
                'target_id' => $role,
                'meta' => [
                    'slug' => config('permission.models.role')::find($role)['name']
                ],
            ]);
        }
    }
}

<?php

namespace Backstage\UserManagement\Listeners\Permissions;

use Spatie\Permission\Events\RoleDetached;
use Backstage\UserManagement\Models\PermissionEventLog;

class LogRoleDetached
{
    public function handle(RoleDetached $event): void
    {
        PermissionEventLog::create([
            'event' => class_basename($event),
            'model_type' => get_class($event->model),
            'model_id' => $event->model->getKey(),
            'target_type' => config('permission.models.role'),
            'target_id' => $event->rolesOrIds->getKey(),
            'meta' => [
                'slug' => $event->rolesOrIds->name,
            ],
        ]);
    }
}

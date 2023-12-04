<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Permission;

class AssignPermissionsToUser extends Command
{
    protected $signature = 'permissions:assign {user_id} {permissions*}';
    protected $description = 'Assign permissions to a user';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $userId = $this->argument('user_id');
        $permissions = $this->argument('permissions');

        // Find the user by ID
        $user = User::find($userId);

        if (!$user) {
            $this->error("User with ID {$userId} not found.");
            return;
        }

        // Loop through each permission and assign it to the user
        foreach ($permissions as $permissionName) {
            $permission = Permission::where('name', $permissionName)->first();

            if (!$permission) {
                $this->error("Permission '{$permissionName}' not found.");
                return;
            }

            // Assign the permission to the user
            $user->givePermissionTo($permission);
        }

        $this->info("Permissions assigned to user with ID {$userId}: " . implode(', ', $permissions));
    }
}

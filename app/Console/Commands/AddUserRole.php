<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AddUserRole extends Command
{
    protected $signature = 'user:add-role {user_id} {role}';

    protected $description = 'Add a role to an existing user';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $userId = $this->argument('user_id');
        $roleName = $this->argument('role');

        $user = User::find($userId);

        if (!$user) {
            $this->error('User not found.');
            return;
        }

        $role = Role::where('name', $roleName)->first();

        if (!$role) {
            $this->error('Role not found.');
            return;
        }

        $user->assignRole($role);

        $this->info("Role '$roleName' added to user with ID $userId.");
    }
}

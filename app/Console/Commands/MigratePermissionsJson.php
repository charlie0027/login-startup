<?php

namespace App\Console\Commands;

use App\Models\Libraries\Permission;
use App\Models\Libraries\UserRole;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigratePermissionsJson extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:migrate-permissions-json';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrating allowed_roles from JSON to pivot table...';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        UserRole::chunk(100, function ($roles) {
            foreach ($roles as $role) {
                // Decode JSON permissions into array of names
                $permissionNames = is_array($role->permissions)
                    ? $role->permissions
                    : json_decode($role->permissions, true);

                if (!empty($permissionNames)) {
                    foreach ($permissionNames as $permissionName) {
                        // Find the permission record by name
                        $permission = Permission::where('name', $permissionName)->first();

                        if ($permission) {
                            DB::table('permission_role')->insert([
                                'permission_id' => $permission->id, // ✅ use numeric ID
                                'user_role_id'       => $role->id,       // ✅ current role ID
                                'created_at'    => now(),
                                'updated_at'    => now(),
                            ]);
                        }
                    }
                }
            }
        });

        $this->info('Migration complete!');
    }
}

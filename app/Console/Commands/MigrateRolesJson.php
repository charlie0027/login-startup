<?php

namespace App\Console\Commands;

use App\Models\UserDetail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateRolesJson extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:migrate-roles-json';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate roles from JSON column to pivot table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting migration of roles...');

        UserDetail::chunk(100, function ($details) {
            foreach ($details as $detail) {
                // Decode JSON safely
                $roles = is_array($detail->roles)
                    ? $detail->roles
                    : json_decode($detail->roles, true);

                if (!empty($roles)) {
                    foreach ($roles as $roleId) {
                        DB::table('user_detail_role')->insert([
                            'user_detail_id' => $detail->id,
                            'role_id'        => $roleId,
                            'created_at'     => now(),
                            'updated_at'     => now(),
                        ]);
                    }
                }
            }
        });

        $this->info('Migration complete!');
    }
}

<?php

namespace App\Console\Commands;

use App\Models\Ability;
use App\Models\Resources\Division;
use App\Models\Resources\Registry;
use App\Models\Role;
use App\Models\User;
use App\Traits\RegistryUserAttachable;
use Illuminate\Console\Command;

class DeployKTSurvivalRegistry extends Command
{
    use RegistryUserAttachable;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deploy:kt-survival-registry';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deploy KT survival registry';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        /**
         * Step to add new registry
         * 1. Add new registry
         * 2. Add new note types
         * 3. Add new abilities and roles
         * 4. Clear ability-registry-user related cache
         * 5. Attach new abilities to root role
         * 6. Attach new registry and role to related users
         * 7. Add new actions to ResourceActionLog if needed
         * 8. Add new resources if needed
         */

        // 1. Add new registry
        $registry = Registry::query()->create([
            'name' => 'kt_survival',
            'label' => 'Kidney Transplant Survival Registry',
            'label_eng' => 'Kidney Transplant Survival Registry',
            'route' => 'clinics.post-kt.index',
            'division_id' => Division::query()->where('name_en_short', 'nephrology')->first()->id,
        ]);

        $timestamps = ['created_at' => now(), 'updated_at' => now()];

        // 2. Add new note types if needed

        // 3. Add new abilities and roles
        Ability::query()->insert([
            // cases - index
            ['registry_id' => $registry->id, 'name' => 'view_any_kt_survival_cases'] + $timestamps,
            // cases - create
            ['registry_id' => $registry->id, 'name' => 'create_kt_survival_case'] + $timestamps,
            // cases - update
            ['registry_id' => $registry->id, 'name' => 'update_kt_survival_case'] + $timestamps,
        ]);
        $ktSurvivalCaseCoordinator = Role::query()->create([
            'name' => 'kt_survival_case_coordinator',
            'label' => 'KT Survival Case Coordinator',
            'registry_id' => $registry->id,
        ]);
        $ktSurvivalCaseCoordinator->abilities()
            ->attach(Ability::query()
                ->where('name', 'like', '%_kt_survival_%')
                ->pluck('id')
            );

        // 4. Clear ability-registry-user related cache
        cache()->forget('ability-registry-map');
        cache()->forget('clinics-index-route-names');

        // 5. Attach new abilities to root role
        $root = Role::query()->where('name', 'root')->first();
        $root->abilities()->attach(
            Ability::query()
                ->select('id')
                ->where('name', 'like', '%_kt_survival_%')
                ->pluck('id')
        );
        $root->users()->with('roles')->each(fn (User $user) => $this->toggleRegistryUser($user));

        // 6. Attach new registry and roles to related users

        // 7. Add new actions to ResourceActionLog if needed

        // 8. Add new resources if needed
    }
}

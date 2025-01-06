<?php

namespace App\Console\Commands;

use App\Models\Ability;
use App\Models\Resources\Division;
use App\Models\Resources\Province;
use App\Models\Resources\Registry;
use App\Models\Role;
use App\Models\User;
use App\Traits\RegistryUserAttachable;
use Illuminate\Console\Command;
use OpenSpout\Common\Exception\IOException;
use OpenSpout\Common\Exception\UnsupportedTypeException;
use OpenSpout\Reader\Exception\ReaderNotOpenedException;
use Rap2hpoutre\FastExcel\FastExcel;

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
    /**
     * @throws IOException
     * @throws UnsupportedTypeException
     * @throws ReaderNotOpenedException
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
            // case - view case data
            ['registry_id' => $registry->id, 'name' => 'view_kt_survival_case_data'] + $timestamps,
            // case - view clinical data
            ['registry_id' => $registry->id, 'name' => 'view_kt_survival_clinical_data'] + $timestamps,
            // case = view follow-up data
            ['registry_id' => $registry->id, 'name' => 'view_kt_survival_follow_up_data'] + $timestamps,
        ]);
        $ktSurvivalCaseCoordinator = Role::query()->create([
            'name' => 'kt_survival_case_coordinator',
            'label' => 'KT Survival Case Coordinator',
            'registry_id' => $registry->id,
        ]);
        $ktSurvivalCaseCoordinator->abilities()
            ->attach(Ability::query()
                ->where('registry_id', $registry->id)
                ->whereIn('name', ['view_any_kt_survival_cases', 'create_kt_survival_case', 'update_kt_survival_case', 'view_kt_survival_case_data'])
                ->pluck('id')
            );
        $ktSurvivalCaseStaff = Role::query()->create([
            'name' => 'kt_survival_case_staff',
            'label' => 'KT Survival Case Staff',
            'registry_id' => $registry->id,
        ]);
        $ktSurvivalCaseStaff->abilities()
            ->attach(Ability::query()
                ->where('registry_id', $registry->id)
                ->whereIn('name', ['view_any_kt_survival_cases', 'view_kt_survival_case_data', 'view_kt_survival_clinical_data', 'view_kt_survival_follow_up_data'])
                ->pluck('id')
            );
        $ktSurvivalCaseFellow = Role::query()->create([
            'name' => 'kt_survival_case_fellow',
            'label' => 'KT Survival Case Fellow',
            'registry_id' => $registry->id,
        ]);
        $ktSurvivalCaseFellow->abilities()
            ->attach(Ability::query()
                ->where('registry_id', $registry->id)
                ->whereIn('name', ['view_any_kt_survival_cases', 'update_kt_survival_case', 'view_kt_survival_case_data', 'view_kt_survival_clinical_data', 'view_kt_survival_follow_up_data'])
                ->pluck('id')
            );
        $ktSurvivalCaseManager = Role::query()->create([
            'name' => 'kt_survival_case_manager',
            'label' => 'KT Survival Case Manager',
            'registry_id' => $registry->id,
        ]);
        $ktSurvivalCaseManager->abilities()
            ->attach(Ability::query()
                ->where('registry_id', $registry->id)
                ->whereIn('name', ['view_any_kt_survival_cases', 'create_kt_survival_case', 'update_kt_survival_case', 'view_kt_survival_case_data', 'view_kt_survival_clinical_data', 'view_kt_survival_follow_up_data'])
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
        $this->line('Init provinces...');
        $provinces = (new FastExcel)->import(storage_path('app/excel/provinces.xlsx'));
        foreach ($provinces as $province) {
            Province::query()->create($province);
        }
        $this->info('Init provinces Done.');

        $this->info('Init hospitals...');
        $hospitals = (new FastExcel)->import(storage_path('app/excel/hospitals.xlsx'));
        foreach ($hospitals as $hospital) {
            Province::query()
                ->where('name', $hospital['province_name'])
                ->first()
                ->hospitals()
                ->create([
                    'name' => $hospital['name'],
                    'district' => $hospital['district'],
                ]);
        }
        $this->info('Init hospitals Done.');
    }
}

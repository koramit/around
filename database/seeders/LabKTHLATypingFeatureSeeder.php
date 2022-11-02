<?php

namespace Database\Seeders;

use App\Models\Ability;
use App\Models\Resources\Division;
use App\Models\Resources\NoteType;
use App\Models\Resources\Registry;
use App\Models\Role;
use App\Models\User;
use App\Traits\RegistryUserAttachable;
use Illuminate\Database\Seeder;

class LabKTHLATypingFeatureSeeder extends Seeder
{
    use RegistryUserAttachable;

    public function run(): void
    {
        /**
         * Step to add new registry
         * 1. Add new registry
         * 2. Add new note types
         * 3. Add new abilities and roles
         * 4. Clear ability-registry-user related cache
         * 5. Attach new abilities to root role
         * 6. Attach new registry to root user
        */

        // new registry
        $registry = Registry::query()->create([
            'name' => 'kt_hla_typing',
            'route' => 'labs.kt-hla-typing.index',
            'division_id' => Division::query()->where('name_en_short', 'nephrology')->first()->id,
        ]);

        // new note types
        $timestamps = ['created_at' => now(), 'updated_at' => now()];
        NoteType::query()->insert([
            ['name' => 'kt_hla_typing_note', 'label' => 'Kidney Transplant HLA Typing Note'] + $timestamps,
            ['name' => 'kt_hla_cxm_note', 'label' => 'Kidney Transplant Crossmatch Note'] + $timestamps,
            ['name' => 'kt_hla_typing_report', 'label' => 'Kidney Transplant HLA Typing Report'] + $timestamps,
        ]);

        // new abilities
        Ability::query()->insert([
            // cases - index
            ['registry_id' => $registry->id, 'name' => 'view_any_kt_hla_typing_cases'] + $timestamps,
            // reports - index + view
            ['registry_id' => $registry->id, 'name' => 'view_any_kt_hla_typing_reports'] + $timestamps,
            // reports - create + edit + update + cancel
            ['registry_id' => $registry->id, 'name' => 'create_kt_hla_typing_report'] + $timestamps,
            // reports - addendum
            ['registry_id' => $registry->id, 'name' => 'addendum_kt_hla_typing_report'] + $timestamps,
        ]);

        // create reporter for kt hla typing role
        $reporter = Role::query()->create([
            'name' => 'kt_hla_typing_reporter',
            'label' => 'Kidney Transplant HLA Typing Reporter',
            'registry_id' => $registry->id,
        ]);

        // attach abilities to reporter
        $reporter->abilities()
            ->attach(Ability::query()
                ->where('name', 'like', '%_kt_hla_typing_%')
                ->select('id')
                ->pluck('id')
            );

        // attach new abilities to root role
        $root = Role::query()->where('name', 'root')->first();
        $root->abilities()->attach(
            Ability::query()
                ->select('id')
                ->where('name', 'like', '%_kt_hla_typing_%')
                ->pluck('id')
        );

        // clear ability-registry-user related cache
        cache()->forget('ability-registry-map');
        cache()->forget('clinics-index-route-names');
        cache()->forget('procedures-index-route-names');
        cache()->forget('labs-index-route-names');

        $root->users->each(fn (User $user) => $this->toggleRegistryUser($user));
    }
}

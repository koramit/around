<?php

namespace App\Console\Commands;

use App\Actions\Procedures\AcuteHemodialysis\CaseRecordStoreAction;
use App\Managers\Resources\AdmissionManager;
use App\Models\Ability;
use App\Models\Registries\AcuteHemodialysisCaseRecord;
use App\Models\Resources\Admission;
use App\Models\Resources\Ward;
use App\Models\Role;
use Illuminate\Console\Command;

class DeployAcuteHemodialysisCaseRecordValidation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deploy:acute_hd_case_record_validation {mode}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rename AcuteHemodialysisCaseRecord comorbidities form | Split Case by admission';

    public function handle(): int
    {
        AcuteHemodialysisCaseRecord::query()
            ->with('lastOrder')
            ->get()
            ->each(function ($c) {
                /* move an and ward_admit and remove ward_discharge */
                $c->meta['an'] = $c->form['an'];
                if (! isset($c->meta['ward_admit'])) {
                    $c->meta['ward_admit'] = null;
                } else {
                    if ($wardDb = Ward::query()->where('name_ref', $c->meta['ward_admit'])->first()) {
                        $c->meta['ward_admit'] = $wardDb->name;
                    }
                }
                unset($c->form['an'], $c->form['ward_admit'], $c->form['ward_discharge']);
                $c->save();

                /* rename comorbidities  */
                $dirty = false;
                foreach (['DM', 'HT', 'DLP', 'COPD'] as $field) {
                    if (isset($c->form['comorbidities'][$field])) {
                        $c->form['comorbidities'][strtolower($field)] = $c->form['comorbidities'][$field];
                        unset($c->form['comorbidities'][$field]);
                        $dirty = true;
                    }
                }

                if ($dirty) {
                    $this->line("{$c->meta['name']} update comorbidities");
                    $c->save();
                }

                /* split by admission */
                $an = $c->meta['an'];
                $dischargedAt = null;
                if ($an) {
                    if ($this->argument('mode') === 'api') {
                        $admission = (new AdmissionManager())->manage($an);
                        if ($admission['found']) {
                            $dischargedAt = $admission['admission']->dismissed_at;
                        }
                    } else {
                        $admission = Admission::findByHashKey($an)->first();
                        $dischargedAt = $admission?->dismissed_at;
                    }
                }

                if (! ($dischargedAt && $c->lastOrder && $c->lastOrder->date_note->greaterThan($dischargedAt))) {
                    return;
                }

                $this->line("{$c->meta['name']} split case");
                $orgOrders = $c->orders->filter(fn ($o) => $o->date_note->lessThan($dischargedAt));
                $splitOrders = $c->orders->filter(fn ($o) => $o->date_note->greaterThan($dischargedAt));
                $this->line("org orders: {$orgOrders->count()} | split orders: {$splitOrders->count()}");
                $c->update(['status' => 'discharged']);
                $c->actionLogs()->create([
                    'action' => 'discharge',
                    'actor_id' => 1,
                ]);
                $splitUser = $splitOrders->first()->author;
                $data['hn'] = $c->meta['hn'];
                $admission = (new AdmissionManager())->manage($data['hn'], true);
                if ($admission['found']) {
                    $data['an'] = $admission['admission']->an;
                }
                $splitCase = (new CaseRecordStoreAction())($data, $splitUser);
                $splitOrders->each(function ($o) use ($splitCase) {
                    $o->update([
                        'case_record_id' => $splitCase->id,
                    ]);
                });
            });

        /* new ability */
        $new = Ability::query()->create(['name' => 'start_session_days_after_date_note']);
        $inCharge = Role::query()->where('name', 'acute_hemodialysis_nurse_manager')->first();
        $root = Role::query()->where('name', 'root')->first();
        $inCharge->allowTo($new);
        $root->allowTo($new);

        return 0;
    }
}

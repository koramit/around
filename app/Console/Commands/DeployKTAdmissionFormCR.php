<?php

namespace App\Console\Commands;

use App\Models\Registries\KidneyTransplantAdmissionCaseRecord;
use Illuminate\Console\Command;

class DeployKTAdmissionFormCR extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:deploy-kt-admission-form-cr';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // *** CR - 2024-02-06 ****
        KidneyTransplantAdmissionCaseRecord::query()
            ->each(function ($case) {
                if ($case->meta['reason_for_admission'] !== 'kt') {
                    return;
                }
                $form = $case->form;
                if (isset($form['donor_creatinine_before_harvest'])) {
                    return;
                }
                $form['donor_creatinine_before_harvest'] = null;
                $form['immunosuppressive_drugs_induction'] = [
                    'none' => false,
                    'simulect' => false,
                    'ATG' => false,
                    'rituximab' => false,
                    'induction_other' => null,
                ];
                $form['creatinine_at_discharge'] = null;
                $case->update(['form' => $form]);
                $this->info("Updated case {$case->meta['title']}");
            });
    }
}

<?php

namespace App\Actions\Clinics\PostKT;

use App\Extensions\Auth\AvatarUser;
use App\Models\User;
use Illuminate\Support\Carbon;

class CaseEditAction extends CaseBaseAction
{
    public function __invoke(string $hashedKey, User|AvatarUser $user): array
    {
        if ($link = $this->shouldLinkAvatar()) {
            return $link;
        }

        $case = $this->getCaseRecord($hashedKey);
        $dateTx = Carbon::create($case->form['date_transplant']);
        $yearTh = now()->year - $dateTx->year;
        if (isset($case->form["year_{$yearTh}_cr"])) {
            $case->form['annual_cr'] = $case->form["year_{$yearTh}_cr"];
            $case->form["date_annual_cr"] = $case->form["date_year_{$yearTh}_cr"];
            $case->form["annual_year"] = $yearTh;
        }

        $form = $case->form;
        foreach ($form as $field => $value) {
            if (in_array(gettype($value), ['double', 'float', 'integer'])) {
                $form[$field] = (string) $value;
            }
        }
        $flash = $this->getFlash($case->title, $user);
        $flash['action-menu'] = [];
        $flash['hn'] = $case->patient->hn;
        $flash['breadcrumbs'] = $this->BREADCRUMBS;

        $configs = [
            'graft_status_options' => ['graft function', 'graft loss', 'loss follow up'],
            'patient_status_options' => ['alive', 'dead', 'loss follow up'],
        ];

        return [
            'formData' => $form,
            'flash' => $flash,
            'formConfigs' => $configs,
        ];
    }
}

<?php

namespace App\Actions\Clinics\PostKT;

use App\Extensions\Auth\AvatarUser;
use App\Models\User;

class CaseEditAction extends CaseBaseAction
{
    public function __invoke(string $hashedKey, User|AvatarUser $user): array
    {
        if ($link = $this->shouldLinkAvatar()) {
            return $link;
        }

        $case = $this->getCaseRecord($hashedKey);
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

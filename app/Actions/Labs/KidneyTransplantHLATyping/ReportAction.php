<?php

namespace App\Actions\Labs\KidneyTransplantHLATyping;

use App\Models\Resources\Registry;

class ReportAction
{
    protected int $REGISTRY_ID;

    protected array $RECIPIENT_IS_OPTIONS = ['น้อง','พี่','บุตร','ภรรยา', 'สามี', 'มารดา', 'บิดา', 'หลาน', 'ป้า', 'น้า', 'อา', 'ลุง'];

    protected array $DONOR_IS_OPTIONS = [
        'น้อง' => ['พี่'],
        'พี่' => ['น้อง'],
        'บุตร' => ['บิดา', 'มารดา'],
        'ภรรยา' => ['สามี'],
        'สามี' => ['ภรรยา'],
        'มารดา' => ['บุตร'],
        'บิดา' => ['บุตร'],
        'หลาน' => ['ป้า', 'น้า', 'อา', 'ลุง'],
        'ป้า' => ['หลาน'],
        'น้า' => ['หลาน'],
        'อา' => ['หลาน'],
        'ลุง' => ['หลาน'],
    ];

    protected array $ABO_OPTIONS = ['A', 'B', 'AB', 'O'];

    protected array $RH_OPTIONS = ['positive', 'negative'];

    protected array $LYMPHOCYTE_CROSSMATCH_OPTIONS = ['N', 'WkP', 'P'];

    protected array $PATIENT_TYPES = [
        'Patient', 'Recipient with LD'
    ];

    public function __construct()
    {
        $this->REGISTRY_ID = cache()->rememberForever(
            'registry-id-kt_hla_typing',
            fn () => Registry::query()->where('name', 'kt_hla_typing')->first()->id
        );
    }
}

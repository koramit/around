<?php

namespace App\Traits\AcuteHemodialysis;

trait CaseRecordShareValidatable
{
    protected array $SEROLOGY_RESULTS = ['positive', 'intermediate', 'negative'];

    protected array $RENAL_DIAGNOSIS = ['AKI', 'AKI on top CKD', 'ESRD', 'Post KT'];

    protected array $RENAL_OUTCOMES = ['Recovery', 'ESRD', 'KT'];

    protected array $PATIENT_OUTCOMES = ['Alive', 'Dead'];
}

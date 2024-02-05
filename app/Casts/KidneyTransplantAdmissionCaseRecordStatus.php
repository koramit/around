<?php

namespace App\Casts;

use App\Contracts\NoteStatusCast;
use App\Traits\StatusCastable;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class KidneyTransplantAdmissionCaseRecordStatus implements CastsAttributes, NoteStatusCast
{
    use StatusCastable;

    protected array $statuses = [
        '',
/**  1 */ 'draft',
/**  2 */ 'completed',
/**  3 */ 'deleted',
/**  4 */ 'edited',
/**  5 */ 'canceled',
/**  6 */ 'offed',
    ];

    public function getActiveStatusCodes(): array
    {
        return [
            $this->getCode('draft'),
            $this->getCode('completed'),
            $this->getCode('edited'),
        ];
    }
}

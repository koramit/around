<?php

namespace App\Casts;

use App\Contracts\NoteStatusCast;
use App\Traits\StatusCastable;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class AcuteHemodialysisCaseRecordStatus implements NoteStatusCast, CastsAttributes
{
    use StatusCastable;

    protected array $statuses = [
        '',
/**  1 */ 'active',
/**  2 */ 'canceled', // have no orders
/**  3 */ 'discharged', // have at least one order
/**  4 */ 'dismissed', // discharged OPD case
/**  5 */ 'completed',
/**  6 */ 'expired', // auto cancel - idle 2 weeks or discharged without performed order
/**  7 */ 'edited',
    ];
}

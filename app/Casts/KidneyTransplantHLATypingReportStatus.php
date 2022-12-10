<?php

namespace App\Casts;

use App\Contracts\NoteStatusCast;
use App\Traits\StatusCastable;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class KidneyTransplantHLATypingReportStatus implements CastsAttributes, NoteStatusCast
{
    use StatusCastable;

    protected array $statuses = [
        '',
/**  1 */'draft',
/**  2 */'published',
/**  3 */'deleted',
/**  4 */'edited',
/**  5 */'canceled',
    ];
}

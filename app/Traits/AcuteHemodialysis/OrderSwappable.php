<?php

namespace App\Traits\AcuteHemodialysis;

use App\Models\Notes\AcuteHemodialysisOrderNote;
use Exception;

trait OrderSwappable
{
    /**
     * @throws Exception
     */
    protected function genSwapCode(): string
    {
        do {
            $code = str_pad((string) random_int(0, 9999), 4, '0');
            $unique = AcuteHemodialysisOrderNote::query()
                ->activeStatuses()
                ->where('meta->swap_code', $code)
                ->count() === 0;
        } while (! $unique);

        return $code;
    }
}

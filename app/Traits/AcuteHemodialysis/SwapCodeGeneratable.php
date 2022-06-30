<?php

namespace App\Traits\AcuteHemodialysis;

use App\Models\Notes\AcuteHemodialysisOrderNote;

trait SwapCodeGeneratable
{
    protected function genSwapCode(): string
    {
        $unique = false;
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

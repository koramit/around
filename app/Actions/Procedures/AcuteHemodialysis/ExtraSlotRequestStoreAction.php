<?php

namespace App\Actions\Procedures\AcuteHemodialysis;

class ExtraSlotRequestStoreAction
{
    public function __invoke()
    {
        if (config('atuh.guards.web.provider') === 'avatar') {
            return []; // call api
        }
    }
}

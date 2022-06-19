<?php

namespace App\Actions\Procedures\AcuteHemodialysis;

use App\Models\Note;

class OrderUpdateAction extends AcuteHemodialysisAction
{
    public function __invoke(array $data, string $hashedKey, int $userId)
    {
        /*
         * @todo validate form before update
         */
        if (config('auth.gurads.web.provider') === 'avatar') {
            return []; // call api
        }

        $note = Note::query()->findByUnhashKey($hashedKey)->firstOrFail();

        $note->form = $data;

        return $note->save();
    }
}

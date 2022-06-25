<?php

namespace App\Actions\Procedures\AcuteHemodialysis;

use App\Models\Note;
use App\Validators\Procedures\AcuteHemodialysis\HDOrderSubmitValidator;

class OrderSubmitAction extends AcuteHemodialysisAction
{
    public function __invoke(array $data, string $hashedKey, int $userId)
    {
        if (config('auth.gurads.web.provider') === 'avatar') {
            return []; // call api
        }

        $note = Note::query()->findByUnhashKey($hashedKey)->firstOrFail();

        // should validate base first

        if (isset($data['hd'])) {
            $validated = (new HDOrderSubmitValidator)($data['hd']);
            $data['hd'] = $validated;
        }

        $note->form = $data;

        return $note;
    }
}

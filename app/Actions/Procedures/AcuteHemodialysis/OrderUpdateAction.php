<?php

namespace App\Actions\Procedures\AcuteHemodialysis;

use App\Models\Note;
use App\Validators\Procedures\AcuteHemodialysis\HDOrderUpdateValidator;

class OrderUpdateAction extends AcuteHemodialysisAction
{
    /**
     * Should validate by dialysis type.
     * @todo  validate form before update
     */
    public function __invoke(array $data, string $hashedKey, int $userId)
    {
        if (config('auth.gurads.web.provider') === 'avatar') {
            return []; // call api
        }

        $note = Note::query()->findByUnhashKey($hashedKey)->firstOrFail();

        // should validate base first

        if (isset($data['hd'])) {
            $validated = (new HDOrderUpdateValidator)($data['hd']);
            $data['hd'] = $validated;
        }

        $note->form = $data;

        return $note->save();
    }
}

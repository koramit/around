<?php

namespace App\Actions\Resources;

use App\Traits\AvatarLinkable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UploadStoreAction
{
    use AvatarLinkable;

    public function __invoke(array $data): array
    {
        if ($link = $this->shouldLinkAvatar()) {
            return $link;
        }

        $validated = Validator::make($data, [
            'file' => 'required|file',
            'pathname' => 'required|string',
            'old' => 'nullable|string',
        ])->validate();

        $path = Storage::putFile('uploads/'.$validated['pathname'], $validated['file']);

        if (($validated['old'])) {
            if (Storage::exists('uploads/'.$validated['pathname'].'/'.$validated['old'])) {
                Storage::delete('uploads/'.$validated['pathname'].'/'.$validated['old']);
            }
        }

        return [
            'filename' => basename($path),
        ];
    }
}

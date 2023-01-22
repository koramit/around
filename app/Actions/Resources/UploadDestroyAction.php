<?php

namespace App\Actions\Resources;

use App\Traits\AvatarLinkable;
use Illuminate\Support\Facades\Storage;

class UploadDestroyAction
{
    use AvatarLinkable;

    public function __invoke(string $path): array
    {
        if ($link = $this->shouldLinkAvatar()) {
            return $link;
        }

        return [ 'ok' => Storage::delete('uploads/'.$path)];
    }
}

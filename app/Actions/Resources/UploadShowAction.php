<?php

namespace App\Actions\Resources;

use App\Traits\AvatarLinkable;
use Illuminate\Support\Facades\Storage;

class UploadShowAction
{
    use AvatarLinkable;

    public function __invoke(string $path)
    {
        if ($link = $this->shouldLinkAvatar()) {
            return $link;
        }

        if (! Storage::exists('uploads/'.$path)) {
            abort(404);
        }

        return Storage::response('uploads/'.$path);
    }
}

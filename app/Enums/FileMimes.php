<?php

namespace App\Enums;

use MyCLabs\Enum\Enum;

class FileMimes extends Enum
{
    const MIME_JPG = 'image/jpeg';
    const MIME_GIF = 'image/gif';
    const MIME_PNG = 'image/png';
    const MIME_WEBM = 'video/webm';
    const MIME_WEBP = 'image/webp';
}

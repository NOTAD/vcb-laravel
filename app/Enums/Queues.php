<?php

namespace App\Enums;

use MyCLabs\Enum\Enum;

class Queues extends Enum
{
    const DEFAULT = 'default';
    const HIGH = 'high';
    const LOW = 'low';
}

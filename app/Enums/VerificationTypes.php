<?php

namespace App\Enums;

use MyCLabs\Enum\Enum;

class VerificationTypes extends Enum
{
    const EMAIL = 1;
    const RESET_PASSWORD = 2;
}

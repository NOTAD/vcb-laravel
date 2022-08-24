<?php

namespace App\Enums;

use MyCLabs\Enum\Enum;

class Permissions extends Enum
{

    const VIEW_HISTORY_TRANSFER = 11;
    const EDIT_HISTORY_TRANSFER = 12;
    const DELETE_HISTORY_TRANSFER = 13;

    const VIEW_BANK = 22;
    const EDIT_BANK = 23;
    const DELETE_BANK = 24;

    const VIEW_USER = 31;
    const EDIT_USER = 32;
    const DELETE_USER = 33;

    const VIEW_ADMIN = 41;
    const EDIT_ADMIN = 42;
    const DELETE_ADMIN = 43;

    const SETTING = 101;
}

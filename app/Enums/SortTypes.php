<?php

namespace App\Enums;

use MyCLabs\Enum\Enum;

class SortTypes extends Enum
{
    const PRICE_ASC = 'price_asc';
    const PRICE_DESC = 'price_desc';
    const LATEST = 'latest';
    const OLDEST = 'oldest';
}

<?php


namespace App\Enums;


use MyCLabs\Enum\Enum;

class TransactionStatus extends Enum
{
    const PENDING = 1;
    const WAITOTP = 2;
    const SUCCESS = 3;
    const ERROR = 4;
}

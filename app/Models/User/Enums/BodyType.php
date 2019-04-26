<?php

namespace App\Models\User\Enums;

use Enum;

class BodyType extends Enum
{
    const ATHLETIC = 'athletic';
    const NORMAL = 'normal';
    const A_BIT_OVERWEIGHT = 'a_bit_overweight';
    const TOUGH = 'tough';
    const OVERWEIGHT = 'overweight';
    const SLIM = 'slim';
}

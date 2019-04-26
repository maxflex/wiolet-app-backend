<?php

namespace App\Models\User\Enums;

use Enum;

class Lives extends Enum
{
    const ALONE = 'alone';
    const WITH_PARENTS = 'with_parents';
    const WITH_ROOMMATES = 'with_roommates';
    const WITH_LOVER = 'with_lover';
}

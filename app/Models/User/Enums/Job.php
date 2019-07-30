<?php

namespace App\Models\User\Enums;

use Enum;

class Job extends Enum
{
    const UNOCCUPIED = 'unoccupied';
    const DONT_WANT_TO_WORK = 'dont_want_to_work';
    const FREELANCER = 'freelancer';
    const OCCUPIED = 'occupied';
    const HARDWORKER = 'hardworker';
}

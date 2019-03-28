<?php

namespace App\Models\User;

use Enum;

class UserList extends Enum
{
    const YOU_WANT_TO_MEET = 'you-want-to-meet';
    const WANT_TO_MEET_YOU = 'want-to-meet-you';
    const DATES = 'dates';
}

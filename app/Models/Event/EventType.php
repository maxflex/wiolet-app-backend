<?php

namespace App\Models\Event;

use Enum;

class EventType extends Enum
{
    // 1
    const LIKE = 'like';

    // 2
    const DISLIKE = 'dislike';

    // 3
    const BAN = 'ban';

    // 4
    const REMOVED_FROM_YOU_WANT_TO_MEET_LIST = 'removed-from-you-want-to-meet-list';

    // 5
    const REMOVED_FROM_WANT_TO_MEET_YOU_LIST = 'removed-from-want-to-meet-you-list';

    // 6
    const REMOVED_FROM_DATES_LIST = 'removed-from-dates-list';
}

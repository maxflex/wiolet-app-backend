<?php

namespace App\Models\Feedback;

use Enum;

class FeedbackType extends Enum
{
    const IDEA = 'idea';
    const COMPLAINT = 'complaint';
}

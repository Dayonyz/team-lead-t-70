<?php

namespace App\Domain\Entities\TeamLead\Enums;

enum MoodStateEnum: int
{
    case GOOD_MOOD = 1;
    case NORMAL_MOOD = 2;
    case BAD_MOOD = 3;
    case ANGRY_MOOD = 4;
}
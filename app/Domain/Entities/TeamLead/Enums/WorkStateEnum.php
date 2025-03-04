<?php

namespace App\Domain\Entities\TeamLead\Enums;


enum WorkStateEnum: int
{
    case SUCCESS = 1;
    case FAILED = 2;
}
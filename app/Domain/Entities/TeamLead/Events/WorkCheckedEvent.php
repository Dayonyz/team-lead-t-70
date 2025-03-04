<?php

namespace App\Domain\Entities\TeamLead\Events;

use App\Domain\Bus\EntityEvent;
use App\Domain\Entities\TeamLead\Enums\MoodStateEnum;
use App\Domain\Entities\TeamLead\Enums\WorkStateEnum;

class WorkCheckedEvent extends EntityEvent
{
    private MoodStateEnum $previousMood;
    private MoodStateEnum $currentMood;
    private WorkStateEnum $workState;

    public function __construct(MoodStateEnum $previousMood, MoodStateEnum $currentMood, WorkStateEnum $workState)
    {
        $this->previousMood = $previousMood;
        $this->currentMood = $currentMood;
        $this->workState = $workState;
    }

    public function getPreviousMood(): MoodStateEnum
    {
        return $this->previousMood;
    }

    public function getCurrentMood(): MoodStateEnum
    {
        return $this->currentMood;
    }

    public function getWorkState(): WorkStateEnum
    {
        return $this->workState;
    }
}
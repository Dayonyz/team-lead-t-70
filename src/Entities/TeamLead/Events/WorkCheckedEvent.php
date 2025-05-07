<?php

namespace Src\Entities\TeamLead\Events;

use Src\Bus\PublisherEvent;
use Src\Entities\TeamLead\Enums\MoodStateEnum;
use Src\Entities\TeamLead\Enums\WorkStateEnum;

class WorkCheckedEvent extends PublisherEvent
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
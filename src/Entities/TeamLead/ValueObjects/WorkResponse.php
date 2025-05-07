<?php

namespace Src\Entities\TeamLead\ValueObjects;

use Src\Entities\TeamLead\Enums\MoodStateEnum;

class WorkResponse
{
    private MoodStateEnum $nextMood;
    private string $phrase;

    public function __construct(MoodStateEnum $nextMood, string $phrase)
    {
        $this->nextMood = $nextMood;
        $this->phrase = $phrase;
    }

    public function getNextMood(): MoodStateEnum
    {
        return $this->nextMood;
    }

    public function getPhrase(): string
    {
        return $this->phrase;
    }
}
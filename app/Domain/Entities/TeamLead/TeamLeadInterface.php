<?php

namespace App\Domain\Entities\TeamLead;

use App\Domain\Entities\TeamLead\Enums\MoodStateEnum;
use App\Domain\Entities\TeamLead\Enums\WorkStateEnum;

interface TeamLeadInterface
{
    public function getUuid(): string;

    public function getName(): string;

    public function getMoodState(): MoodStateEnum;

    public function checkWork(WorkStateEnum $workState): void;
}
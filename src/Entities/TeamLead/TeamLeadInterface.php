<?php

namespace Src\Entities\TeamLead;

use Ramsey\Uuid\UuidInterface;
use Src\Entities\TeamLead\Enums\MoodStateEnum;
use Src\Entities\TeamLead\Enums\WorkStateEnum;

interface TeamLeadInterface
{
    public function getUuid(): UuidInterface;

    public function getName(): string;

    public function getMoodState(): MoodStateEnum;

    public function checkWork(WorkStateEnum $workState): void;
}
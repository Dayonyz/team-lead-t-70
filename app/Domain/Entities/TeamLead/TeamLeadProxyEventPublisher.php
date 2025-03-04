<?php

namespace App\Domain\Entities\TeamLead;

use App\Domain\Bus\EntityEventPublisher;
use App\Domain\Entities\TeamLead\Enums\MoodStateEnum;
use App\Domain\Entities\TeamLead\Enums\WorkStateEnum;
use App\Domain\Entities\TeamLead\Events\WorkCheckedEvent;

class TeamLeadProxyEventPublisher extends EntityEventPublisher implements TeamLeadInterface
{
    private TeamLeadEntity $entity;

    public function __construct(TeamLeadEntity $entity)
    {
        $this->entity = $entity;
    }

    public function getUuid(): string
    {
        return $this->entity->getUuid();
    }

    public function getName(): string
    {
        return $this->entity->getName();
    }

    public function getMoodState(): MoodStateEnum
    {
        return $this->entity->getMoodState();
    }

    public function checkWork(WorkStateEnum $workState): void
    {
        $previousMood = $this->entity->getMoodState();

        $this->entity->checkWork($workState);

        $this->publishEvent(new WorkCheckedEvent(
            $previousMood,
            $this->entity->getMoodState(),
            $workState
        ));
    }
}
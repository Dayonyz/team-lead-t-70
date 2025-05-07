<?php

namespace Src\Entities\TeamLead;

use Ramsey\Uuid\UuidInterface;
use Src\Bus\EventPublisher;
use Src\Bus\EventPublisherInterface;
use Src\Entities\TeamLead\Enums\MoodStateEnum;
use Src\Entities\TeamLead\Enums\WorkStateEnum;
use Src\Entities\TeamLead\Events\WorkCheckedEvent;

class TeamLeadProxyEventPublisher extends EventPublisher implements TeamLeadInterface, EventPublisherInterface
{
    private TeamLeadEntity $entity;

    public function __construct(TeamLeadEntity $entity)
    {
        $this->entity = $entity;
    }

    public function getUuid(): UuidInterface
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

    public function getInnerEntity(): TeamLeadEntity
    {
        return $this->entity;
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
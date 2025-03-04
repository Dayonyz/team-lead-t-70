<?php

namespace App\Domain\Bus;

use Ramsey\Uuid\Uuid;

abstract class EntityEventPublisher
{
    protected EventBus $eventBus;

    public function registerObserver(EventBus $eventBus): void
    {
        $this->eventBus = $eventBus;
    }

    public function publishEvent(EntityEvent $event): void
    {
        $this->eventBus->handleEvent($event, $this);
    }

    abstract public function getUuid(): string;
}
<?php

namespace Src\Bus;

use Ramsey\Uuid\UuidInterface;

abstract class EventPublisher implements EventPublisherInterface
{
    public EventBus $eventBus;

    public function registerObserver(EventBus $eventBus): void
    {
        $this->eventBus = $eventBus;
    }

    public function publishEvent(PublisherEvent $event): void
    {
        $this->eventBus->handleEvent($event, $this);
    }

    abstract public function getUuid(): UuidInterface;
}
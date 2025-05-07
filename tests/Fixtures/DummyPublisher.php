<?php

namespace Tests\Fixtures;

use Ramsey\Uuid\UuidInterface;
use Src\Bus\EventBus;
use Src\Bus\EventPublisherInterface;
use Src\Bus\PublisherEvent;

class DummyPublisher implements EventPublisherInterface
{
    public int $callCount = 0;

    public function __construct(private UuidInterface $uuid) {}

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function registerObserver(EventBus $eventBus): void
    {
        $this->callCount++;
    }

    public function publishEvent(PublisherEvent $event): void
    {
        echo "Event published: " . get_class($event);
    }
}
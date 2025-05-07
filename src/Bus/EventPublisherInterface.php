<?php

namespace Src\Bus;

use Ramsey\Uuid\UuidInterface;

interface EventPublisherInterface
{
    public function registerObserver(EventBus $eventBus): void;

    public function publishEvent(PublisherEvent $event): void;

    public function getUuid(): UuidInterface;
}
<?php

namespace Tests\Fixtures;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Src\Bus\EventSubscriberInterface;

class DummySubscriber implements EventSubscriberInterface
{
    private UuidInterface $uuid;

    public function __construct(?UuidInterface $uuid = null)
    {
        $this->uuid = $uuid ?? Uuid::fromString('00000000-0000-0000-0000-000000000001');
    }

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }
}
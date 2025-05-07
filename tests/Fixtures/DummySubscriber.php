<?php

namespace Tests\Fixtures;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Src\Bus\EventSubscriberInterface;

class DummySubscriber implements EventSubscriberInterface
{
    public function getUuid(): UuidInterface
    {
        return Uuid::uuid1();
    }
}
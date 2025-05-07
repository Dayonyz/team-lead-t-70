<?php

namespace Src\Bus;

use Ramsey\Uuid\UuidInterface;

abstract class EventSubscriber implements EventSubscriberInterface
{
    abstract public function getUuid(): UuidInterface;
}
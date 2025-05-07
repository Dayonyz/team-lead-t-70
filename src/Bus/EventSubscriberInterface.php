<?php

namespace Src\Bus;

use Ramsey\Uuid\UuidInterface;

interface EventSubscriberInterface
{
    public function getUuid(): UuidInterface;
}
<?php

namespace App\Domain\Bus;

abstract class EntityEventSubscriber
{
    abstract public function getUuid(): string;
}
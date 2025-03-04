<?php

namespace App\Domain\Entities\Manager;

use App\Domain\Bus\EntityEventSubscriber;
use Ramsey\Uuid\Uuid;

class ManagerEntity extends EntityEventSubscriber
{
    private string $uuid;
    private string $name = 'T-1001';
    private int $praiseCount;

    public function __construct()
    {
        $this->uuid = Uuid::uuid1()->toString();
        $this->praiseCount = 0;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function incrementPraiseCount()
    {
        $this->praiseCount++;
        $this->printReport();
    }

    public function printReport(): void
    {
        $plural = $this->getPraiseCount() === 1 ? '' : 's';

        echo "Manager '$this->name' with '{$this->uuid}' reported: Junior developer has {$this->praiseCount} praise{$plural}\n";
    }

    public function getPraiseCount(): int
    {
        return $this->praiseCount;
    }
}
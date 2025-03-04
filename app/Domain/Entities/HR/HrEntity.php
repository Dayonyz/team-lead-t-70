<?php

namespace App\Domain\Entities\HR;

use App\Domain\Bus\EntityEventSubscriber;
use Ramsey\Uuid\Uuid;

class HrEntity extends EntityEventSubscriber
{
    private string $uuid;
    private string $name = 'T-1000';
    private int $reprimandCount;

    public function __construct()
    {
        $this->uuid = Uuid::uuid1()->toString();
        $this->reprimandCount = 0;
    }

    public function getUuid(): string
    {
       return $this->uuid;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function incrementReprimandCount()
    {
        $this->reprimandCount++;
        $this->printReport();
    }

    public function printReport(): void
    {
        $plural = $this->getReprimandCount() === 1 ? '' : 's';

        echo "HR '$this->name' with uuid '{$this->uuid}' reported: Junior developer has {$this->reprimandCount} reprimand{$plural}\n";
    }

    public function getReprimandCount(): int
    {
        return $this->reprimandCount;
    }
}
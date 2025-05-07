<?php

namespace Src\Entities\Manager;

use Ramsey\Uuid\UuidInterface;
use Src\Bus\EventSubscriber;
use Ramsey\Uuid\Uuid;
use Src\Bus\EventSubscriberInterface;
use Src\Services\ReportPrinter;
use Src\Services\ReportPrinterInterface;

class ManagerEntity extends EventSubscriber implements EventSubscriberInterface
{
    private UuidInterface $uuid;
    private ReportPrinterInterface $printer;
    private string $name = 'T-1001';
    private string $report = "Manager '{name}' with '{uuid}' reported: Junior developer has {praiseCount} praise{plural}";
    private int $praiseCount;

    public function __construct(?ReportPrinterInterface $printer = null)
    {
        $this->uuid = Uuid::uuid1();
        $this->praiseCount = 0;
        $this->printer = $printer ? : new ReportPrinter();
    }

    public function getUuid(): UuidInterface
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

        $this->printer->print($this->report, [
            '{name}' => $this->getName(),
            '{uuid}' => $this->getUuid()->toString(),
            '{praiseCount}' => $this->getPraiseCount(),
            '{plural}' => $this->getPraiseCount() === 1 ? '' : 's'
        ]);
    }

    public function getPraiseCount(): int
    {
        return $this->praiseCount;
    }
}
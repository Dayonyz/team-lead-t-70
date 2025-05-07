<?php

namespace Src\Entities\HR;

use Ramsey\Uuid\UuidInterface;
use Src\Bus\EventSubscriber;
use Ramsey\Uuid\Uuid;
use Src\Bus\EventSubscriberInterface;
use Src\Services\ReportPrinter;
use Src\Services\ReportPrinterInterface;

class HrEntity extends EventSubscriber implements EventSubscriberInterface
{
    private UuidInterface $uuid;
    private ReportPrinterInterface $printer;
    private string $name = 'T-1000';
    private string $report = "HR '{name}' with '{uuid}' reported: Junior developer has {reprimandCount} reprimand{plural}";
    private int $reprimandCount;


    public function __construct(?ReportPrinterInterface $printer = null)
    {
        $this->uuid = Uuid::uuid1();
        $this->reprimandCount = 0;
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

    public function incrementReprimandCount()
    {
        $this->reprimandCount++;

        $this->printer->print($this->report, [
            '{name}' => $this->getName(),
            '{uuid}' => $this->getUuid()->toString(),
            '{reprimandCount}' => $this->getReprimandCount(),
            '{plural}' => $this->getReprimandCount() === 1 ? '' : 's'
        ]);
    }

    public function getReprimandCount(): int
    {
        return $this->reprimandCount;
    }
}
<?php

namespace Src\Entities\TeamLead;

use Ramsey\Uuid\UuidInterface;
use Src\Entities\TeamLead\Enums\MoodStateEnum;
use Src\Entities\TeamLead\Enums\WorkStateEnum;
use Src\Entities\TeamLead\Services\MoodConverter;
use Ramsey\Uuid\Uuid;
use Src\Services\ReportPrinter;
use Src\Services\ReportPrinterInterface;

class TeamLeadEntity implements TeamLeadInterface
{
    private MoodStateEnum $moodState;
    private MoodConverter $moodConverter;
    private UuidInterface $uuid;
    private ReportPrinterInterface $printer;
    private string $name = 'T-70';
    private string $report = "Team lead '{name}' with '{uuid}' says to Junior dev: {phrase}";

    private function __construct(?MoodStateEnum $startMood = null, ?ReportPrinterInterface $printer = null)
    {
        $this->uuid = Uuid::uuid1();
        $cases = MoodStateEnum::cases();
        $this->moodState = $startMood ?: $cases[array_rand($cases)];
        $this->moodConverter = new MoodConverter();
        $this->printer = $printer ? : new ReportPrinter();
    }

    private function __clone(): void
    {
    }

    public static function createNew(
        ?MoodStateEnum $startMood = null,
        ?ReportPrinterInterface $printer = null
    ): TeamLeadProxyEventPublisher
    {
        $entity = new static($startMood, $printer);

        return new TeamLeadProxyEventPublisher($entity);
    }

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getMoodState(): MoodStateEnum
    {
        return $this->moodState;
    }

    public function checkWork(WorkStateEnum $workState): void
    {
        $response = $this->moodConverter->convert($this->moodState, $workState);

        $this->moodState = $response->getNextMood();

        $this->printer->print($this->report, [
            '{name}' => $this->getName(),
            '{uuid}' => $this->getUuid()->toString(),
            '{phrase}' => $response->getPhrase()
        ]);
    }
}
<?php

namespace App\Domain\Entities\TeamLead;

use App\Domain\Entities\TeamLead\Enums\MoodStateEnum;
use App\Domain\Entities\TeamLead\Enums\WorkStateEnum;
use App\Domain\Entities\TeamLead\Services\MoodConverter;
use Ramsey\Uuid\Uuid;

class TeamLeadEntity implements TeamLeadInterface
{
    private MoodStateEnum $moodState;
    private MoodConverter $moodConverter;
    private string $uuid;
    private string $name = 'T-70';

    private function __construct(?MoodStateEnum $startMood = null)
    {
        $this->uuid = Uuid::uuid1()->toString();
        $cases = MoodStateEnum::cases();
        $this->moodState = $startMood ?: $cases[array_rand($cases)];
        $this->moodConverter = new MoodConverter();
    }

    private function __clone(): void
    {
    }

    public static function createNew(?MoodStateEnum $startMood = null): TeamLeadProxyEventPublisher
    {
        $entity = new static($startMood);

        return new TeamLeadProxyEventPublisher($entity);
    }

    public function getUuid(): string
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

        echo "Team lead '{$this->name}' with uuid '{$this->uuid}' says to Junior dev: {$response->getPhrase()}\n";
    }
}
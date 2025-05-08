<?php

require __DIR__ . '/vendor/autoload.php';

use Src\Bus\EventBus;
use Src\Entities\HR\HrEntity;
use Src\Entities\Manager\ManagerEntity;
use Src\Entities\TeamLead\Enums\MoodStateEnum;
use Src\Entities\TeamLead\Enums\WorkStateEnum;
use Src\Entities\TeamLead\Events\WorkCheckedEvent;
use Src\Entities\TeamLead\TeamLeadEntity;

$teamLead = TeamLeadEntity::createNew(MoodStateEnum::GOOD_MOOD);
$hr = new HrEntity();
$manager = new ManagerEntity();
$eventBus = new EventBus();

try {
    $eventBus->subscribe($hr, $teamLead, function (HrEntity $hr, WorkCheckedEvent $event) {
        if ($event->getPreviousMood() === MoodStateEnum::ANGRY_MOOD &&
            $event->getWorkState() === WorkStateEnum::FAILED &&
            $event->getCurrentMood() === MoodStateEnum::ANGRY_MOOD
        ) {
            $hr->incrementReprimandCount();
        }
    });
} catch (ReflectionException $e) {
    echo $e->getMessage() . PHP_EOL;
}

try {
    $eventBus->subscribe($manager, $teamLead, function (ManagerEntity $manager, WorkCheckedEvent $event) {
        if ($event->getPreviousMood() === MoodStateEnum::GOOD_MOOD &&
            $event->getWorkState() === WorkStateEnum::SUCCESS &&
            $event->getCurrentMood() === MoodStateEnum::GOOD_MOOD
        ) {
            $manager->incrementPraiseCount();
        }
    });
} catch (ReflectionException $e) {
    echo $e->getMessage() . PHP_EOL;
}


$teamLead->checkWork(WorkStateEnum::SUCCESS);

$teamLead->checkWork(WorkStateEnum::FAILED);
$teamLead->checkWork(WorkStateEnum::FAILED);
$teamLead->checkWork(WorkStateEnum::FAILED);
$teamLead->checkWork(WorkStateEnum::FAILED);
$teamLead->checkWork(WorkStateEnum::FAILED);
$teamLead->checkWork(WorkStateEnum::FAILED);

$teamLead->checkWork(WorkStateEnum::SUCCESS);
$teamLead->checkWork(WorkStateEnum::SUCCESS);
$teamLead->checkWork(WorkStateEnum::SUCCESS);
$teamLead->checkWork(WorkStateEnum::SUCCESS);
$teamLead->checkWork(WorkStateEnum::SUCCESS);
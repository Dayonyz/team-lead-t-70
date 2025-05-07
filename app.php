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
        if ($event->getPreviousMood()->value === MoodStateEnum::ANGRY_MOOD->value &&
            $event->getWorkState()->value === WorkStateEnum::FAILED->value &&
            $event->getCurrentMood()->value === MoodStateEnum::ANGRY_MOOD->value
        ) {
            $hr->incrementReprimandCount();
        }
    });
} catch (ReflectionException $e) {
    echo $e->getMessage() . PHP_EOL;
}

try {
    $eventBus->subscribe($manager, $teamLead, function (ManagerEntity $manager, WorkCheckedEvent $event) {
        if ($event->getPreviousMood()->value === MoodStateEnum::GOOD_MOOD->value &&
            $event->getWorkState()->value === WorkStateEnum::SUCCESS->value &&
            $event->getCurrentMood()->value === MoodStateEnum::GOOD_MOOD->value
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
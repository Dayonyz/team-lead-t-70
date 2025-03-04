<?php

namespace App\Console\Commands;

use App\Domain\Bus\EventBus;
use App\Domain\Entities\HR\HrEntity;
use App\Domain\Entities\Manager\ManagerEntity;
use App\Domain\Entities\TeamLead\Enums\MoodStateEnum;
use App\Domain\Entities\TeamLead\Enums\WorkStateEnum;
use App\Domain\Entities\TeamLead\Events\WorkCheckedEvent;
use App\Domain\Entities\TeamLead\TeamLeadEntity;
use Illuminate\Console\Command;
use ReflectionException;

class TestTeamLeadApp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-team-lead-app';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     * @throws ReflectionException
     */
    public function handle()
    {
        $teamLead = TeamLeadEntity::createNew(MoodStateEnum::GOOD_MOOD);
        $hr = new HrEntity();
        $manager = new ManagerEntity();

        $eventBus = new EventBus();

        $eventBus->subscribe($hr, $teamLead, function (HrEntity $hr, WorkCheckedEvent $event) {
            if ($event->getPreviousMood()->value === MoodStateEnum::ANGRY_MOOD->value &&
                $event->getWorkState()->value === WorkStateEnum::FAILED->value &&
                $event->getCurrentMood()->value === MoodStateEnum::ANGRY_MOOD->value
            ) {
                $hr->incrementReprimandCount();
            }
        });

        $eventBus->subscribe($manager, $teamLead, function (ManagerEntity $manager, WorkCheckedEvent $event) {
            if ($event->getPreviousMood()->value === MoodStateEnum::GOOD_MOOD->value &&
                $event->getWorkState()->value === WorkStateEnum::SUCCESS->value &&
                $event->getCurrentMood()->value === MoodStateEnum::GOOD_MOOD->value
            ) {
                $manager->incrementPraiseCount();
            }
        });

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
    }
}

<?php

namespace Tests\Unit\Entities\TeamLead;

use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Src\Bus\EventBus;
use Src\Entities\TeamLead\TeamLeadEntity;
use Src\Entities\TeamLead\Enums\MoodStateEnum;
use Src\Entities\TeamLead\Enums\WorkStateEnum;
use Src\Entities\TeamLead\Events\WorkCheckedEvent;
use Src\Entities\TeamLead\TeamLeadProxyEventPublisher;
use Src\Services\ReportPrinterInterface;

class TeamLeadProxyEventPublisherTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testCheckWorkWithEvent(): void
    {
        $printerMock = $this->createMock(ReportPrinterInterface::class);
        $printerMock->method('print')
            ->willReturnCallback(function ($template, $context) use (&$calls) {
                $calls[] = [$template, $context];
                return null;
            });

        $proxyTeamLeadEntity = TeamLeadEntity::createNew(MoodStateEnum::GOOD_MOOD, $printerMock);

        $eventBus = $this->createMock(EventBus::class);

        $eventBus->expects($this->once())
            ->method('handleEvent')
            ->with(
                $this->callback(function ($event) {
                    return $event instanceof WorkCheckedEvent
                        && $event->getPreviousMood() === MoodStateEnum::GOOD_MOOD
                        && $event->getCurrentMood() === MoodStateEnum::GOOD_MOOD
                        && $event->getWorkState() === WorkStateEnum::SUCCESS;
                }),
                $this->isInstanceOf(TeamLeadProxyEventPublisher::class)
            );

        $proxyTeamLeadEntity->registerObserver($eventBus);

        $proxyTeamLeadEntity->checkWork(WorkStateEnum::SUCCESS);
        $this->assertEquals(MoodStateEnum::GOOD_MOOD, $proxyTeamLeadEntity->getMoodState());
    }
}
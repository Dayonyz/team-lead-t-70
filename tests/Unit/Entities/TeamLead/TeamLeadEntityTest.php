<?php

namespace Tests\Unit\Entities\TeamLead;

use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Src\Entities\TeamLead\TeamLeadEntity;
use Src\Entities\TeamLead\Enums\MoodStateEnum;
use Src\Entities\TeamLead\Enums\WorkStateEnum;
use Src\Services\ReportPrinterInterface;

class TeamLeadEntityTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testCheckWorkAndPrintReport(): void
    {
        $printerMock = $this->createMock(ReportPrinterInterface::class);

        $calls = [];

        $printerMock->method('print')
            ->willReturnCallback(function ($template, $context) use (&$calls) {
                $calls[] = [$template, $context];
                return null;
            });
        $teamLead = TeamLeadEntity::createNew(MoodStateEnum::GOOD_MOOD, $printerMock)->getInnerEntity();

        $teamLead->checkWork(WorkStateEnum::SUCCESS);
        $this->assertEquals(MoodStateEnum::GOOD_MOOD, $teamLead->getMoodState());

        $teamLead->checkWork(WorkStateEnum::FAILED);
        $this->assertEquals(MoodStateEnum::NORMAL_MOOD, $teamLead->getMoodState());

        $teamLead->checkWork(WorkStateEnum::FAILED);
        $this->assertEquals(MoodStateEnum::BAD_MOOD, $teamLead->getMoodState());

        $teamLead->checkWork(WorkStateEnum::FAILED);
        $this->assertEquals(MoodStateEnum::ANGRY_MOOD, $teamLead->getMoodState());

        $teamLead->checkWork(WorkStateEnum::FAILED);
        $this->assertEquals(MoodStateEnum::ANGRY_MOOD, $teamLead->getMoodState());

        $teamLead->checkWork(WorkStateEnum::SUCCESS);
        $this->assertEquals(MoodStateEnum::BAD_MOOD, $teamLead->getMoodState());

        $this->assertCount(6, $calls);

        [$template1, $context1] = $calls[0];

        $this->assertEquals("Team lead '{name}' with '{uuid}' says to Junior dev: {phrase}", $template1);
        $this->assertEquals('T-70', $context1['{name}']);
        $this->assertIsString($context1['{uuid}']);
        $this->assertEquals('You are doing your best! Respect!', $context1['{phrase}']);

        [$template2, $context2] = $calls[1];

        $this->assertEquals("Team lead '{name}' with '{uuid}' says to Junior dev: {phrase}", $template2);
        $this->assertEquals('T-70', $context2['{name}']);
        $this->assertIsString($context2['{uuid}']);
        $this->assertEquals('Next time you will be better, try harder!', $context2['{phrase}']);

        [$template3, $context3] = $calls[2];

        $this->assertEquals("Team lead '{name}' with '{uuid}' says to Junior dev: {phrase}", $template3);
        $this->assertEquals('T-70', $context3['{name}']);
        $this->assertIsString($context3['{uuid}']);
        $this->assertEquals('Hmm... Come back tomorrow and redo everything.', $context3['{phrase}']);

        [$template4, $context4] = $calls[3];

        $this->assertEquals("Team lead '{name}' with '{uuid}' says to Junior dev: {phrase}", $template4);
        $this->assertEquals('T-70', $context4['{name}']);
        $this->assertIsString($context4['{uuid}']);
        $this->assertEquals('Hide so that no one sees you!', $context4['{phrase}']);

        [$template5, $context5] = $calls[4];

        $this->assertEquals("Team lead '{name}' with '{uuid}' says to Junior dev: {phrase}", $template5);
        $this->assertEquals('T-70', $context5['{name}']);
        $this->assertIsString($context5['{uuid}']);
        $this->assertEquals('OMG! I\'m murder killer! You will be fired!', $context5['{phrase}']);

        [$template6, $context6] = $calls[5];

        $this->assertEquals("Team lead '{name}' with '{uuid}' says to Junior dev: {phrase}", $template6);
        $this->assertEquals('T-70', $context6['{name}']);
        $this->assertIsString($context5['{uuid}']);
        $this->assertEquals('Are you still here? Go back to work.', $context6['{phrase}']);
    }
}
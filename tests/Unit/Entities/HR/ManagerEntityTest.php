<?php

namespace Tests\Unit\Entities\HR;

use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Src\Entities\Manager\ManagerEntity;
use Src\Services\ReportPrinterInterface;

class ManagerEntityTest extends  TestCase
{
    /**
     * @throws Exception
     */
    public function testIncrementReprimandCount(): void
    {
        $printerMock = $this->createMock(ReportPrinterInterface::class);

        $calls = [];

        $printerMock->method('print')
            ->willReturnCallback(function ($template, $context) use (&$calls) {
                $calls[] = [$template, $context];
                return null;
            });

        $manager = new ManagerEntity($printerMock);
        $manager->incrementPraiseCount();
        $manager->incrementPraiseCount();

        $this->assertCount(2, $calls);

        [$template1, $context1] = $calls[0];

        $this->assertEquals("Manager '{name}' with '{uuid}' reported: Junior developer has {praiseCount} praise{plural}", $template1);
        $this->assertEquals('T-1001', $context1['{name}']);
        $this->assertIsString($context1['{uuid}']);
        $this->assertEquals(1, $context1['{praiseCount}']);
        $this->assertEquals('', $context1['{plural}']);

        [$template2, $context2] = $calls[1];
        $this->assertEquals("Manager '{name}' with '{uuid}' reported: Junior developer has {praiseCount} praise{plural}", $template2);
        $this->assertEquals('T-1001', $context2['{name}']);
        $this->assertIsString($context2['{uuid}']);
        $this->assertEquals(2, $context2['{praiseCount}']);
        $this->assertEquals('s', $context2['{plural}']);

        $this->assertEquals(2, $manager->getPraiseCount());
    }
}
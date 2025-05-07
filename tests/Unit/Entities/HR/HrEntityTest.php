<?php

namespace Tests\Unit\Entities\HR;

use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Src\Entities\HR\HrEntity;
use Src\Services\ReportPrinterInterface;

class HrEntityTest extends TestCase
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

        $hr = new HrEntity($printerMock);
        $hr->incrementReprimandCount();
        $hr->incrementReprimandCount();

        $this->assertCount(2, $calls);

        [$template1, $context1] = $calls[0];

        $this->assertEquals("HR '{name}' with '{uuid}' reported: Junior developer has {reprimandCount} reprimand{plural}", $template1);
        $this->assertEquals('T-1000', $context1['{name}']);
        $this->assertIsString($context1['{uuid}']);
        $this->assertEquals(1, $context1['{reprimandCount}']);
        $this->assertEquals('', $context1['{plural}']);

        [$template2, $context2] = $calls[1];
        $this->assertEquals("HR '{name}' with '{uuid}' reported: Junior developer has {reprimandCount} reprimand{plural}", $template2);
        $this->assertEquals('T-1000', $context2['{name}']);
        $this->assertIsString($context2['{uuid}']);
        $this->assertEquals(2, $context2['{reprimandCount}']);
        $this->assertEquals('s', $context2['{plural}']);

        $this->assertEquals(2, $hr->getReprimandCount());
    }
}
<?php

namespace Tests\Unit\Services;

use PHPUnit\Framework\TestCase;
use Src\Services\ReportPrinter;

class ReportPrinterTest extends TestCase
{
    public function testPrintReport(): void
    {
        $report = "Test report: {name}";
        $replacements = ['{name}' => 'John Doe'];

        $this->expectOutputString("Test report: John Doe\n");

        $printer = new ReportPrinter();

        $printer->print($report, $replacements);
    }
}

<?php

namespace Src\Services;

class ReportPrinter implements ReportPrinterInterface
{
    public function print(string $template, array $context): void
    {
        echo strtr($template, $context) . PHP_EOL;
    }
}
<?php

namespace Src\Services;

namespace Src\Services;

interface ReportPrinterInterface
{
    public function print(string $template, array $context): void;
}
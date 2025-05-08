<?php

namespace Tests\Unit\Entities\TeamLead\Services;

use PHPUnit\Framework\TestCase;
use Src\Entities\TeamLead\Services\MoodConverter;
use Src\Entities\TeamLead\Enums\MoodStateEnum;
use Src\Entities\TeamLead\Enums\WorkStateEnum;
use InvalidArgumentException;

class MoodConverterTest extends TestCase
{
    public function testConvertValid(): void
    {
        $converter = new MoodConverter();

        $response = $converter->convert(MoodStateEnum::GOOD_MOOD, WorkStateEnum::SUCCESS);

        $this->assertEquals(MoodStateEnum::GOOD_MOOD, $response->getNextMood());
        $this->assertEquals('You are doing your best! Respect!', $response->getPhrase());
    }

    public function testConvertInvalid(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $converter = new MoodConverter([]);
        $converter->convert(MoodStateEnum::ANGRY_MOOD, WorkStateEnum::FAILED);
    }
}
<?php

namespace Tests\Unit\Bus;

use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Src\Bus\EventSubscriber;
use Ramsey\Uuid\Guid\Guid;

class EventSubscriberTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testGetUuid(): void
    {
        $eventSubscriber = $this->createMock(EventSubscriber::class);
        $uuid = Guid::uuid4();
        $eventSubscriber->method('getUuid')->willReturn($uuid);

        $this->assertEquals($uuid, $eventSubscriber->getUuid());
    }
}
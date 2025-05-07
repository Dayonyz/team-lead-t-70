<?php

namespace Tests\Unit\Bus;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\Exception;
use Ramsey\Uuid\Guid\Guid;
use Ramsey\Uuid\UuidInterface;
use Src\Bus\EventBus;
use Src\Bus\EventPublisher;
use Src\Bus\PublisherEvent;

class EventPublisherTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testRegisterObserver(): void
    {
        $eventPublisher = $this->createMock(EventPublisher::class);
        $eventBus = $this->createMock(EventBus::class);

        $eventPublisher->expects($this->once())
            ->method('registerObserver')
            ->with($eventBus);

        $eventPublisher->registerObserver($eventBus);
    }

    /**
     * @throws Exception
     */
    public function testPublishEvent(): void
    {
        $eventPublisher = new class extends EventPublisher {
            public function getUuid(): UuidInterface
            {
                return Guid::uuid1();
            }
        };

        $eventBus = $this->createMock(EventBus::class);

        $eventPublisher->registerObserver($eventBus);

        $event = $this->createMock(PublisherEvent::class);

        $eventBus->expects($this->once())
            ->method('handleEvent')
            ->with($event, $eventPublisher);

        $eventPublisher->publishEvent($event);
    }

    /**
     * @throws Exception
     */
    public function testGetUuid(): void
    {
        $eventPublisher = $this->createMock(EventPublisher::class);
        $uuid = Guid::uuid1();
        $eventPublisher->method('getUuid')->willReturn($uuid);

        $this->assertEquals($uuid, $eventPublisher->getUuid());
    }
}
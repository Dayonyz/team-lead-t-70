<?php

namespace Tests\Unit\Bus;

use InvalidArgumentException;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use ReflectionException;
use Src\Bus\EventBus;
use Src\Bus\EventSubscriberInterface;
use Src\Bus\PublisherEvent;
use stdClass;
use Tests\Fixtures\DummyPublisher;
use Tests\Fixtures\DummySubscriber;
use Tests\Fixtures\RealEvent;

class EventBusTest extends TestCase
{
    /**
     * @throws ReflectionException
     */
    public function testSuccessfulSubscribeAndHandle(): void
    {
        $bus = new EventBus();

        $uuid = Uuid::uuid4();
        $publisher = new DummyPublisher($uuid);
        $subscriber = new DummySubscriber();

        $event = new RealEvent();

        $called = false;

        $handler = function (EventSubscriberInterface $s, RealEvent $e) use (&$called, $subscriber, $event) {
            $called = true;
            $this->assertSame($subscriber, $s);
            $this->assertSame($event, $e);
        };

        $bus->subscribe($subscriber, $publisher, $handler);
        $bus->handleEvent($event, $publisher);

        $this->assertTrue($called);
    }
    /**
     * @throws ReflectionException
     */
    public function testHandlerNotCalledForDifferentEventClass(): void
    {
        $bus = new EventBus();

        $uuid = Uuid::uuid4();
        $publisher = new DummyPublisher($uuid);
        $subscriber = new DummySubscriber();

        $handler = function (EventSubscriberInterface $s, RealEvent $e) {
            $this->fail('Handler should not be called');
        };

        $bus->subscribe($subscriber, $publisher, $handler);

        $differentEvent = new class extends PublisherEvent {};

        $bus->handleEvent($differentEvent, $publisher);

        $this->assertTrue(true); // Success if handler wasn't called
    }

    /**
     * @throws ReflectionException
     */
    public function testSubscribeFailsWithInvalidHandlerArgumentsCount(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Event handler must have 2 params: EntityEventSubscriber and EntityEvent');

        $bus = new EventBus();
        $uuid = Uuid::uuid4();
        $publisher = new DummyPublisher($uuid);
        $subscriber = new DummySubscriber();

        $handler = fn($a) => null;

        $bus->subscribe($subscriber, $publisher, $handler);
    }

    /**
     * @throws ReflectionException
     */
    public function testSubscribeFailsWithInvalidFirstHandlerParamType(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Expected first param of event handler");

        $bus = new EventBus();
        $uuid = Uuid::uuid4();
        $publisher = new DummyPublisher($uuid);
        $subscriber = new DummySubscriber();

        $handler = fn(stdClass $a, PublisherEvent $b) => null;

        $bus->subscribe($subscriber, $publisher, $handler);
    }

    /**
     * @throws ReflectionException
     */
    public function testSubscribeFailsWithInvalidSecondHandlerParamType(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Expected second param of event handler");

        $bus = new EventBus();
        $uuid = Uuid::uuid4();
        $publisher = new DummyPublisher($uuid);
        $subscriber = new DummySubscriber();

        $handler = fn(EventSubscriberInterface $a, stdClass $b) => null;

        $bus->subscribe($subscriber, $publisher, $handler);
    }

    /**
     * @throws ReflectionException
     */
    public function testRegisterObserverCalledOnceForPublisher(): void
    {
        $bus = new EventBus();

        $uuid = Uuid::uuid4();
        $publisher = new DummyPublisher($uuid);
        $subscriber = new DummySubscriber();

        $handler = function (EventSubscriberInterface $s, RealEvent $e) {};

        $bus->subscribe($subscriber, $publisher, $handler);
        $bus->subscribe($subscriber, $publisher, $handler);

        $this->assertSame(1, $publisher->callCount);
    }
}
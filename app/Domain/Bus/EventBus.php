<?php

namespace App\Domain\Bus;

use InvalidArgumentException;
use ReflectionException;

class EventBus
{
    private array $publishers = [];

    public function handleEvent(EntityEvent $event, EntityEventPublisher $publisher): void
    {
        if (isset($this->publishers[$publisher::class . ':' . $publisher->getUuid()])) {
            foreach ($this->publishers[$publisher::class . ':' . $publisher->getUuid()] as $subscriberPool) {
                if ($subscriberPool['event_class'] === $event::class) {
                    $subscriberPool['handler']($subscriberPool['subscriber'], $event);
                }
            }
        }
    }

    /**
     * @throws ReflectionException
     */
    public function subscribe(
        EntityEventSubscriber $subscriber,
        EntityEventPublisher $publisher,
        callable $handler
    ): void {
        $reflection = new \ReflectionFunction($handler);
        $params = $reflection->getParameters();

        if (count($params) !== 2) {
            throw new InvalidArgumentException(
                "Event handler must have 2 params: EntityEventSubscriber and EntityEvent"
            );
        }

        $subscriberParam = $params[0];
        if ($subscriberParam->getType()->getName() !== $subscriber::class) {
            $subscriberClass = $subscriber::class;
            throw new InvalidArgumentException(
                "Expected first param of event handler: '{$subscriberClass}' type"
            );
        }

        $eventParam = $params[1];
        if (get_parent_class($eventParam->getType()->getName()) !== EntityEvent::class) {
            $type = EntityEvent::class;
            throw new InvalidArgumentException("Expected second param of event handler: '{$type}' type");
        }

        $this->registerPublisher($publisher);

        if (! isset($this->publishers[$publisher::class . ':' . $publisher->getUuid()])) {
            $this->publishers[$publisher::class . ':' . $publisher->getUuid()] = [];
        }

        $this->publishers[$publisher::class . ':' . $publisher->getUuid()][] = [
            'subscriber' => $subscriber,
            'event_class' => $eventParam->getType()->getName(),
            'handler' => $handler
        ];
    }

    protected function registerPublisher(EntityEventPublisher $publisher): void
    {
        $publisher->registerObserver($this);
    }
}
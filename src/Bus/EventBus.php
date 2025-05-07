<?php

namespace Src\Bus;

use InvalidArgumentException;
use ReflectionException;

class EventBus
{
    private array $publishers = [];

    public function handleEvent(PublisherEvent $event, EventPublisherInterface $publisher): void
    {
        $key = $this->publisherKey($publisher);

        if (isset($this->publishers[$key])) {
            foreach ($this->publishers[$key] ?? [] as $subscriberPool) {
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
        EventSubscriberInterface $subscriber,
        EventPublisherInterface  $publisher,
        callable                 $handler
    ): void {
        $reflection = new \ReflectionFunction($handler);
        $params = $reflection->getParameters();

        if (count($params) !== 2) {
            throw new InvalidArgumentException(
                "Event handler must have 2 params: EntityEventSubscriber and EntityEvent"
            );
        }

        $subscriberParam = $params[0];
        $subscriberType = $subscriberParam->getType()?->getName();

        if (!$subscriberType || !is_a($subscriber, $subscriberType, true)) {
            throw new InvalidArgumentException(
                "Expected first param of event handler: '{$subscriberType}' type"
            );
        }

        $eventParam = $params[1];
        $eventType = $eventParam->getType()?->getName();

        if (!$eventType || !is_subclass_of($eventType, PublisherEvent::class)) {
            throw new InvalidArgumentException(
                "Expected second param of event handler: '" . PublisherEvent::class . "' type"
            );
        }

        $key = $this->publisherKey($publisher);

        if (!isset($this->publishers[$key])) {
            $this->publishers[$key] = [];
            $publisher->registerObserver($this);
        }

        $this->publishers[$key][] = [
            'subscriber'  => $subscriber,
            'event_class' => $eventType,
            'handler'     => $handler
        ];
    }

    protected function publisherKey(EventPublisherInterface $publisher): string
    {
        return $publisher::class . ':' . $publisher->getUuid()->toString();
    }
}
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
            foreach ($this->publishers[$key] as $subscriberPool) {
                if ($subscriberPool['event_class'] === $event::class) {
                    ($subscriberPool['handler'])($subscriberPool['subscriber'], $event);
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

        $publisherKey = $this->publisherKey($publisher);
        $subscriberKey = $this->subscriberKey($subscriber);

        if (!isset($this->publishers[$publisherKey])) {
            $this->publishers[$publisherKey] = [];
            $publisher->registerObserver($this);
        }

        if (isset($this->publishers[$publisherKey][$subscriberKey])) {
            $existing = $this->publishers[$publisherKey][$subscriberKey];

            if ($existing['event_class'] === $eventType && $this->isSameHandler($existing['handler'], $handler)) {
                return;
            }

            throw new InvalidArgumentException(sprintf(
                "Subscriber '%s' already subscribed to event '%s' from publisher '%s' with a different handler",
                $subscriberKey,
                $eventType,
                $publisherKey
            ));
        }

        $this->publishers[$publisherKey][$subscriberKey] = [
            'subscriber'  => $subscriber,
            'event_class' => $eventType,
            'handler'     => $handler
        ];
    }

    protected function isSameHandler(callable $a, callable $b): bool
    {
        return $a === $b;
    }

    protected function publisherKey(EventPublisherInterface $publisher): string
    {
        return $publisher::class . ':' . $publisher->getUuid()->toString();
    }

    protected function subscriberKey(EventSubscriberInterface $subscriber): string
    {
        return $subscriber::class . ':' . $subscriber->getUuid()->toString();
    }
}
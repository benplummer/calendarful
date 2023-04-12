<?php

declare(strict_types=1);

namespace Plummer\Calendarful\Event;

use ArrayIterator;
use Countable;
use InvalidArgumentException;
use IteratorAggregate;

/**
 * @implements IteratorAggregate<int, EventInterface>
 */
class EventCollection implements Countable, IteratorAggregate
{
    /**
     * @param array<int, EventInterface> $events
     */
    private function __construct(
        private readonly array $events,
    ) {
        if (! $this->areAllInstancesOfEvent($events)) {
            throw new InvalidArgumentException('All array items must be an instance of EventInterface.');
        }
    }

    /**
     * @param array<int, EventInterface> $events
     */
    public static function fromArray(array $events): self
    {
        return new self(
            array_values($events)
        );
    }

    public function count(): int
    {
        return count($this->events);
    }

    /**
     * @return ArrayIterator<int, EventInterface>
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->events);
    }

    /**
     * @param array<array-key, mixed> $items
     */
    private function areAllInstancesOfEvent(array $items): bool
    {
        $nonEventItems = array_filter($items, function (mixed $item) {
            return ! $item instanceof EventInterface;
        });

        return ! $nonEventItems;
    }
}

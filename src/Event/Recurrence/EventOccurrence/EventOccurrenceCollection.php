<?php

declare(strict_types=1);

namespace Plummer\Calendarful\Event\Recurrence\EventOccurrence;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use InvalidArgumentException;

/**
 * @implements IteratorAggregate<int, EventOccurrenceInterface>
 */
class EventOccurrenceCollection implements Countable, IteratorAggregate
{
    /**
     * @param array<int, EventOccurrenceInterface> $eventOccurrences
     */
    private function __construct(
        private readonly array $eventOccurrences,
    ) {
        if (! $this->areAllInstancesOfEventOccurrence($eventOccurrences)) {
            throw new InvalidArgumentException('All array items must be an instance of EventOccurrenceInterface.');
        }
    }

    /**
     * @param array<int, EventOccurrenceInterface> $eventOccurrences
     */
    public static function fromArray(
        array $eventOccurrences,
    ): self {
        return new self(
            array_values(
                $eventOccurrences,
            ),
        );
    }

    public function count(): int
    {
        return count($this->eventOccurrences);
    }

    /**
     * @param callable(EventOccurrenceInterface):bool $filterCallable
     */
    public function filter(
        callable $filterCallable,
    ): self {
        return self::fromArray(
            array_filter(
                $this->eventOccurrences,
                $filterCallable,
            ),
        );
    }

    /**
     * @return ArrayIterator<int, EventOccurrenceInterface>
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator(
            $this->eventOccurrences,
        );
    }

    public function merge(self $otherEventOccurrenceCollection): self
    {
        $otherEventOccurrences = iterator_to_array(
            $otherEventOccurrenceCollection,
        );

        $mergedEventOccurrences = [
            ...$this->eventOccurrences,
            ...array_values($otherEventOccurrences),
        ];

        return self::fromArray(
            $mergedEventOccurrences,
        );
    }

    /**
     * @param array<array-key, mixed> $items
     */
    private function areAllInstancesOfEventOccurrence(array $items): bool
    {
        $nonEventOccurrenceItems = array_filter($items, function (mixed $item) {
            return ! $item instanceof EventOccurrenceInterface;
        });

        return ! $nonEventOccurrenceItems;
    }
}

<?php

declare(strict_types=1);

namespace Plummer\Calendarful\Event\Recurrence\EventOverride;

use ArrayIterator;
use Countable;
use InvalidArgumentException;
use IteratorAggregate;

/**
 * @implements IteratorAggregate<int, EventOverrideInterface>
 */
class EventOverrideCollection implements Countable, IteratorAggregate
{
    /**
     * @param array<int, EventOverrideInterface> $eventOverrides
     */
    private function __construct(
        private readonly array $eventOverrides,
    ) {
        if (! $this->areAllInstancesOfEventOverride($eventOverrides)) {
            throw new InvalidArgumentException('All array items must be an instance of EventOverrideInterface.');
        }
    }

    /**
     * @param array<int, EventOverrideInterface> $eventOverrides
     */
    public static function fromArray(
        array $eventOverrides,
    ): self {
        return new self(
            array_values(
                $eventOverrides,
            ),
        );
    }

    public function count(): int
    {
        return count(
            $this->eventOverrides,
        );
    }

    /**
     * @return ArrayIterator<int, EventOverrideInterface>
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator(
            $this->eventOverrides,
        );
    }

    /**
     * @param array<array-key, mixed> $items
     */
    private function areAllInstancesOfEventOverride(array $items): bool
    {
        $nonEventOverrideItems = array_filter($items, function (mixed $item) {
            return ! $item instanceof EventOverrideInterface;
        });

        return ! $nonEventOverrideItems;
    }
}

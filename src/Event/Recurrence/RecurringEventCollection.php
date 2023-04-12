<?php

declare(strict_types=1);

namespace Plummer\Calendarful\Event\Recurrence;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use InvalidArgumentException;

/**
 * @implements IteratorAggregate<int, RecurringEventInterface>
 */
class RecurringEventCollection implements Countable, IteratorAggregate
{
    /**
     * @param array<int, RecurringEventInterface> $recurringEvents
     */
    private function __construct(
        private readonly array $recurringEvents,
    ) {
        if (! $this->areAllInstancesOfRecurringEvent($recurringEvents)) {
            throw new InvalidArgumentException(
                'All array items must be an instance of RecurringEventInterface.',
            );
        }
    }

    /**
     * @param array<int, RecurringEventInterface> $recurringEvents
     */
    public static function fromArray(
        array $recurringEvents,
    ): self {
        return new self(
            array_values(
                $recurringEvents,
            ),
        );
    }

    /**
     * @param callable(RecurringEventInterface):bool $filterCallable
     */
    public function filter(
        callable $filterCallable,
    ): self {
        return self::fromArray(
            array_filter(
                $this->recurringEvents,
                $filterCallable,
            )
        );
    }

    public function count(): int
    {
        return count(
            $this->recurringEvents,
        );
    }

    /**
     * @return ArrayIterator<int, RecurringEventInterface>
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator(
            $this->recurringEvents,
        );
    }

    /**
     * @param array<array-key, mixed> $items
     */
    private function areAllInstancesOfRecurringEvent(array $items): bool
    {
        $nonRecurringEventItems = array_filter($items, function (mixed $item) {
            return ! $item instanceof RecurringEventInterface;
        });

        return ! $nonRecurringEventItems;
    }
}

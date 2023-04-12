<?php

declare(strict_types=1);

namespace Plummer\Calendarful\Event\Recurrence\RecurrenceType;

use ArrayIterator;
use Countable;
use InvalidArgumentException;
use IteratorAggregate;

/**
 * @implements IteratorAggregate<int, RecurrenceTypeInterface>
 */
class RecurrenceTypeCollection implements Countable, IteratorAggregate
{
    /**
     * @param array<int, RecurrenceTypeInterface> $recurrenceTypes
     */
    private function __construct(
        private readonly array $recurrenceTypes,
    ) {
        if (! $this->areAllInstancesOfRecurrenceType($recurrenceTypes)) {
            throw new InvalidArgumentException('All array items must be an instance of RecurrenceTypeInterface.');
        }
    }

    /**
     * @param array<int, RecurrenceTypeInterface> $recurrenceTypes
     */
    public static function fromArray(
        array $recurrenceTypes,
    ): self {
        return new self(
            array_values(
                $recurrenceTypes,
            ),
        );
    }

    public function count(): int
    {
        return count(
            $this->recurrenceTypes,
        );
    }

    /**
     * @return ArrayIterator<int, RecurrenceTypeInterface>
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator(
            $this->recurrenceTypes,
        );
    }

    /**
     * @param array<array-key, mixed> $items
     */
    private function areAllInstancesOfRecurrenceType(array $items): bool
    {
        $nonRecurrenceTypeItems = array_filter($items, function (mixed $item) {
            return ! $item instanceof RecurrenceTypeInterface;
        });

        return ! $nonRecurrenceTypeItems;
    }
}

<?php

declare(strict_types=1);

namespace Plummer\Calendarful\Event\Recurrence\RecurrenceType\InMemory;

use Plummer\Calendarful\Event\Recurrence\RecurrenceType\RecurrenceTypeCollection;
use Plummer\Calendarful\Event\Recurrence\RecurrenceType\RecurrenceTypeInterface;
use Plummer\Calendarful\Event\Recurrence\RecurrenceType\RecurrenceTypeRepositoryInterface;

class InMemoryRecurrenceTypeRepository implements RecurrenceTypeRepositoryInterface
{
    /**
     * @var array<int, RecurrenceTypeInterface>
     */
    protected array $recurrenceTypes = [];

    public function add(
        RecurrenceTypeInterface ...$recurrenceTypes,
    ): void {
        $this->recurrenceTypes = [
            ...$this->recurrenceTypes,
            ...array_values(
                $recurrenceTypes,
            ),
        ];
    }

    public function all(): RecurrenceTypeCollection
    {
        return RecurrenceTypeCollection::fromArray(
            $this->recurrenceTypes,
        );
    }
}

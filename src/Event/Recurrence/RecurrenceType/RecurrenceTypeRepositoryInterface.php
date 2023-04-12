<?php

declare(strict_types=1);

namespace Plummer\Calendarful\Event\Recurrence\RecurrenceType;

interface RecurrenceTypeRepositoryInterface
{
    public function add(
        RecurrenceTypeInterface ...$recurrenceTypes,
    ): void;

    public function all(): RecurrenceTypeCollection;
}

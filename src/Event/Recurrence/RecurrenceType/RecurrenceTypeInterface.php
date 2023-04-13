<?php

declare(strict_types=1);

namespace Plummer\Calendarful\Event\Recurrence\RecurrenceType;

use DateInterval;
use DateTimeImmutable;
use Plummer\Calendarful\Event\Recurrence\EventOccurrence\EventOccurrenceCollection;
use Plummer\Calendarful\Event\Recurrence\RecurringEvent\RecurringEventCollection;

interface RecurrenceTypeInterface
{
    /**
     * Get the unique ID that will be referenced by relevant recurring events.
     */
    public function id(): RecurrenceTypeId;

    /**
     * Get a date interval that indicates the maximum limit of time that event occurrences
     * for a given event can be generated up to.
     */
    public function recursUntilLimit(): DateInterval;

    /**
     * Generate the occurrences for the given recurring events.
     */
    public function occurrencesFor(
        RecurringEventCollection $recurringEvents,
        DateTimeImmutable $fromDate,
        DateTimeImmutable $toDate,
    ): EventOccurrenceCollection;
}

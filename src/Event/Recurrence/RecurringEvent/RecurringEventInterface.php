<?php

declare(strict_types=1);

namespace Plummer\Calendarful\Event\Recurrence\RecurringEvent;

use DateTimeImmutable;
use Plummer\Calendarful\Event\EventInterface;
use Plummer\Calendarful\Event\Recurrence\RecurrenceType\RecurrenceTypeId;

interface RecurringEventInterface extends EventInterface
{
    /**
     * Get the recurrence type id of the event.
     */
    public function recurrenceTypeId(): RecurrenceTypeId;

    /**
     * Get the date that the recurring event should recur until, if one has been set.
     */
    public function recursUntil(): ?DateTimeImmutable;
}

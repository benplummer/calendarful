<?php

declare(strict_types=1);

namespace Plummer\Calendarful\Event\Recurrence\EventOverride;

use DateTimeImmutable;
use Plummer\Calendarful\Event\EventInterface;
use Plummer\Calendarful\Event\EventIdInterface;

interface EventOverrideInterface extends EventInterface
{
    /**
     * Get the id of the recurring event that this event override overrides an event
     * occurrence of.
     */
    public function recurringEventId(): EventIdInterface;

    /**
     * Get the occurrence date of the event.
     *
     * When any data for an event occurrence that was generated from a recurring event
     * is changed, an event override should be created with its occurrence date assigned
     * the value of the start date of the event occurrence. The recurring event id and
     * the occurrence date of an event override together are what maintains the relationship
     * with the relevant event occurrence generated from a recurring event. As a result,
     * if the id of a recurring event changes (it shouldn't!) or if the start date of the
     * recurring event changes, the corresponding data of the event override (recurring
     * event id or occurrence date) should be updated to reflect that, otherwise it would
     * not be possible to determine which event occurrence the event override is actually
     * for.
     */
    public function occurrenceDate(): DateTimeImmutable;
}

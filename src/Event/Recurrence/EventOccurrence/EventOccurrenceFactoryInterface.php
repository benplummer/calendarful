<?php

declare(strict_types=1);

namespace Plummer\Calendarful\Event\Recurrence\EventOccurrence;

use DateTimeImmutable;
use Plummer\Calendarful\Event\EventIdInterface;

interface EventOccurrenceFactoryInterface
{
    public function createEventOccurrence(
        EventIdInterface $eventId,
        DateTimeImmutable $startDate,
        DateTimeImmutable $endDate,
    ): EventOccurrenceInterface;
}

<?php

declare(strict_types=1);

namespace Plummer\Calendarful\Event\Recurrence\EventOccurrence;

use DateTimeImmutable;
use Plummer\Calendarful\Event\EventIdInterface;
use Plummer\Calendarful\Event\EventInterface;

interface EventOccurrenceInterface extends EventInterface
{
    public static function create(
        EventIdInterface $id,
        DateTimeImmutable $startDate,
        DateTimeImmutable $endDate,
    ): self;
}

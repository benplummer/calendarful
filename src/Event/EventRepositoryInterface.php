<?php

declare(strict_types=1);

namespace Plummer\Calendarful\Event;

use DateTimeImmutable;

interface EventRepositoryInterface
{
    public function add(
        EventInterface ...$events,
    ): void;

    public function withinDateRange(
        DateTimeImmutable $fromDate,
        DateTimeImmutable $toDate,
    ): EventCollection;
}

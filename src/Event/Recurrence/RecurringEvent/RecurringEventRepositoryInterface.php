<?php

declare(strict_types=1);

namespace Plummer\Calendarful\Event\Recurrence\RecurringEvent;

use DateTimeImmutable;

interface RecurringEventRepositoryInterface
{
    public function add(
        RecurringEventInterface ...$recurringEvents,
    ): void;

    public function forDateRange(
        DateTimeImmutable $fromDate,
        DateTimeImmutable $toDate,
    ): RecurringEventCollection;
}

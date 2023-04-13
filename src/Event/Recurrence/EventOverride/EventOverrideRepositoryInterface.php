<?php

declare(strict_types=1);

namespace Plummer\Calendarful\Event\Recurrence\EventOverride;

use DateTimeImmutable;

interface EventOverrideRepositoryInterface
{
    public function add(
        EventOverrideInterface ...$eventOverrides,
    ): void;

    public function withinDateRange(
        DateTimeImmutable $fromDate,
        DateTimeImmutable $toDate,
    ): EventOverrideCollection;
}

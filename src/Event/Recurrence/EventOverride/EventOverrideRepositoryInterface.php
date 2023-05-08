<?php

declare(strict_types=1);

namespace Plummer\Calendarful\Event\Recurrence\EventOverride;

use DateTimeImmutable;
use Plummer\Calendarful\Event\EventIdInterface;

interface EventOverrideRepositoryInterface
{
    public function add(
        EventOverrideInterface ...$eventOverrides,
    ): void;

    public function nextIdentity(): EventIdInterface;

    public function withinDateRange(
        DateTimeImmutable $fromDate,
        DateTimeImmutable $toDate,
    ): EventOverrideCollection;
}

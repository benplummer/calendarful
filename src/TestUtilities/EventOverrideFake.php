<?php

declare(strict_types=1);

namespace Plummer\Calendarful\TestUtilities;

use DateInterval;
use DateTimeImmutable;
use Plummer\Calendarful\Event\EventIdInterface;
use Plummer\Calendarful\Event\Recurrence\EventOverride\EventOverrideInterface;

class EventOverrideFake implements EventOverrideInterface
{
    public function __construct(
        private EventIdInterface $id,
        private DateTimeImmutable $startDate,
        private DateTimeImmutable $endDate,
        private EventIdInterface $recurringEventId,
        private DateTimeImmutable $occurrenceDate,
    ) {
    }

    public function id(): EventIdInterface
    {
        return $this->id;
    }

    public function startDate(): DateTimeImmutable
    {
        return $this->startDate;
    }

    public function endDate(): DateTimeImmutable
    {
        return $this->endDate;
    }

    public function duration(): DateInterval
    {
        return $this->startDate->diff($this->endDate);
    }

    public function recurringEventId(): EventIdInterface
    {
        return $this->recurringEventId;
    }

    public function occurrenceDate(): DateTimeImmutable
    {
        return $this->occurrenceDate;
    }
}

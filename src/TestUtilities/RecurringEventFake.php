<?php

declare(strict_types=1);

namespace Plummer\Calendarful\TestUtilities;

use DateInterval;
use DateTimeImmutable;
use Plummer\Calendarful\Event\EventIdInterface;
use Plummer\Calendarful\Event\Recurrence\RecurrenceType\RecurrenceTypeId;
use Plummer\Calendarful\Event\Recurrence\RecurringEvent\RecurringEventInterface;

class RecurringEventFake implements RecurringEventInterface
{
    public function __construct(
        private EventIdInterface $id,
        private DateTimeImmutable $startDate,
        private DateTimeImmutable $endDate,
        private RecurrenceTypeId $recurrenceTypeId,
        private ?DateTimeImmutable $recursUntil = null,
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

    public function recurrenceTypeId(): RecurrenceTypeId
    {
        return $this->recurrenceTypeId;
    }

    public function recursUntil(): ?DateTimeImmutable
    {
        return $this->recursUntil;
    }
}

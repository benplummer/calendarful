<?php

declare(strict_types=1);

namespace Plummer\Calendarful\Event\Recurrence\EventOccurrence;

use DateInterval;
use DateTimeImmutable;
use Plummer\Calendarful\Event\EventIdInterface;

class EventOccurrence implements EventOccurrenceInterface
{
    private function __construct(
        private readonly EventIdInterface $id,
        private readonly DateTimeImmutable $startDate,
        private readonly DateTimeImmutable $endDate,
    ) {
    }

    public static function create(
        EventIdInterface $id,
        DateTimeImmutable $startDate,
        DateTimeImmutable $endDate,
    ): EventOccurrenceInterface {
        return new self(
            $id,
            $startDate,
            $endDate,
        );
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
}

<?php

declare(strict_types=1);

namespace Plummer\Calendarful\Event;

use DateInterval;
use DateTimeImmutable;

interface EventInterface
{
    /**
     * Get the unique id of the event.
     * Most likely a primary key of the record in a database etc.
     *
     * @return EventIdInterface
     */
    public function id(): EventIdInterface;

    /**
     * Get the start date of the event.
     *
     * @return DateTimeImmutable
     */
    public function startDate(): DateTimeImmutable;

    /**
     * Get the end date of the event.
     *
     * @return DateTimeImmutable
     */
    public function endDate(): DateTimeImmutable;

    /**
     * Get the duration between the event start date and end date.
     *
     * @return DateInterval
     */
    public function duration(): DateInterval;
}

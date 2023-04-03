<?php

declare(strict_types=1);

namespace Plummer\Calendarful\Event;

interface EventIdInterface
{
    /**
     * Get the internal id.
     *
     * @return string
     */
    public function id(): string;

    /**
     * Determine if the id is equal to the given id.
     *
     * @return bool
     */
    public function equals(EventIdInterface $otherEventId): bool;
}

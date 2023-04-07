<?php

declare(strict_types=1);

namespace Plummer\Calendarful\TestUtilities;

use Plummer\Calendarful\Event\EventIdInterface;

class EventIdFake implements EventIdInterface
{
    public function __construct(
        private string $id,
    ) {
    }

    public function id(): string
    {
        return $this->id;
    }

    public function equals(EventIdInterface $otherEventId): bool
    {
        return $this->id() === $otherEventId->id();
    }
}

<?php

declare(strict_types=1);

namespace Plummer\Calendarful\Event\InMemory;

use DateTimeImmutable;
use Plummer\Calendarful\Event\EventCollection;
use Plummer\Calendarful\Event\EventInterface;
use Plummer\Calendarful\Event\EventRepositoryInterface;

class InMemoryEventRepository implements EventRepositoryInterface
{
    /**
     * @var array<int, EventInterface>
     */
    protected array $events = [];

    public function add(
        EventInterface ...$events,
    ): void {
        $this->events = [
            ...$this->events,
            ...array_values($events),
        ];
    }

    public function withinDateRange(
        DateTimeImmutable $fromDate,
        DateTimeImmutable $toDate,
    ): EventCollection {
        return EventCollection::fromArray(
            array_filter(
                $this->events,
                function (EventInterface $event) use ($fromDate, $toDate) {
                    if (
                        ($event->startDate() < $fromDate && $event->endDate() < $fromDate) ||
                        ($event->startDate() > $toDate && $event->endDate() > $toDate)
                    ) {
                        return false;
                    }

                    return true;
                }
            ),
        );
    }
}

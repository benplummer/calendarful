<?php

declare(strict_types=1);

namespace Plummer\Calendarful\Event\Recurrence\EventOverride\InMemory;

use DateTimeImmutable;
use Plummer\Calendarful\Event\Recurrence\EventOverride\EventOverrideCollection;
use Plummer\Calendarful\Event\Recurrence\EventOverride\EventOverrideInterface;
use Plummer\Calendarful\Event\Recurrence\EventOverride\EventOverrideRepositoryInterface;

class InMemoryEventOverrideRepository implements EventOverrideRepositoryInterface
{
    /**
     * @var array<int, EventOverrideInterface>
     */
    protected array $eventOverrides = [];

    public function add(
        EventOverrideInterface ...$eventOverrides,
    ): void {
        $this->eventOverrides = [
            ...$this->eventOverrides,
            ...array_values(
                $eventOverrides,
            ),
        ];
    }

    public function withinDateRange(
        DateTimeImmutable $fromDate,
        DateTimeImmutable $toDate,
    ): EventOverrideCollection {
        return EventOverrideCollection::fromArray(
            array_filter(
                $this->eventOverrides,
                function (EventOverrideInterface $eventOverride) use ($fromDate, $toDate) {
                    if (
                        ($eventOverride->startDate() < $fromDate && $eventOverride->endDate() < $fromDate) ||
                        ($eventOverride->startDate() > $toDate && $eventOverride->endDate() > $toDate)
                    ) {
                        return false;
                    }

                    return true;
                }
            )
        );
    }
}

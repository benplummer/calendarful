<?php

declare(strict_types=1);

namespace Plummer\Calendarful\Event\Recurrence\InMemory;

use DateTimeImmutable;
use Plummer\Calendarful\Event\Recurrence\RecurringEventCollection;
use Plummer\Calendarful\Event\Recurrence\RecurringEventInterface;
use Plummer\Calendarful\Event\Recurrence\RecurringEventRepositoryInterface;

class InMemoryRecurringEventRepository implements RecurringEventRepositoryInterface
{
    /**
     * @var array<int, RecurringEventInterface>
     */
    protected array $recurringEvents = [];

    public function add(
        RecurringEventInterface ...$recurringEvents,
    ): void {
        $this->recurringEvents = [
            ...$this->recurringEvents,
            ...array_values(
                $recurringEvents
            ),
        ];
    }

    public function forDateRange(
        DateTimeImmutable $fromDate,
        DateTimeImmutable $toDate
    ): RecurringEventCollection {
        return RecurringEventCollection::fromArray(
            array_filter(
                $this->recurringEvents,
                function (RecurringEventInterface $recurringEvent) use ($fromDate, $toDate) {

                    if ($recurringEvent->startDate() > $toDate) {
                        return false;
                    }

                    $recursUntil = $recurringEvent->recursUntil();

                    if ($recursUntil && $recurringEvent->recursUntil() < $fromDate) {
                        $recurringEventDurationDate = (new DateTimeImmutable('2022-01-01 00:00:00'))
                            ->add($recurringEvent->duration());

                        $recurringEventRecursUntilDurationDate = (new DateTimeImmutable('2022-01-01 00:00:00'))
                            ->add($recursUntil->diff($fromDate));

                        // Without actually generating occurrences for a recurring event, this seems like a
                        // viable way to predict if occurrences might fall within the given date range;
                        // checking the duration of the recurring event is larger or equal to the time period
                        // between the date that the recurring event recurs to and the start of the given date
                        // range. This method may not capture all relevant recurring events but this is purely
                        // an implementation of a RecurringEventRepository for testing and an implementation
                        // for production can be altered as desired. In order to capture as many recurring
                        // events as possible, your implementation could increase the recursUntil of each
                        // recurring event to account for more event occurrences that overlap the start of the
                        // given date range.
                        if ($recurringEventDurationDate >= $recurringEventRecursUntilDurationDate) {
                            return true;
                        }

                        return false;
                    }

                    return true;
                }
            )
        );
    }
}

<?php

declare(strict_types=1);

namespace Plummer\Calendarful\Event\Recurrence\RecurrenceType\Type;

use DateInterval;
use DatePeriod;
use DateTimeImmutable;
use Plummer\Calendarful\Event\Recurrence\EventOccurrence\EventOccurrenceCollection;
use Plummer\Calendarful\Event\Recurrence\EventOccurrence\EventOccurrenceFactory;
use Plummer\Calendarful\Event\Recurrence\EventOccurrence\EventOccurrenceFactoryInterface;
use Plummer\Calendarful\Event\Recurrence\RecurrenceType\RecurrenceTypeInterface;
use Plummer\Calendarful\Event\Recurrence\RecurrenceType\RecurrenceTypeId;
use Plummer\Calendarful\Event\Recurrence\RecurringEvent\RecurringEventCollection;
use Plummer\Calendarful\Event\Recurrence\RecurringEvent\RecurringEventInterface;

class Daily implements RecurrenceTypeInterface
{
    public function __construct(
        protected ?EventOccurrenceFactoryInterface $eventOccurrenceFactory = null,
    ) {
        $this->eventOccurrenceFactory = $eventOccurrenceFactory ?? new EventOccurrenceFactory();
    }

    /**
     * Get the unique ID that will be referenced by relevant recurring events.
     */
    public function id(): RecurrenceTypeId
    {
        return RecurrenceTypeId::fromString('Daily');
    }

    /**
     * Get a date interval that indicates the maximum limit of time that event occurrences
     * for a given event can be generated up to.
     */
    public function recursUntilLimit(): DateInterval
    {
        return new DateInterval('P1Y');
    }

    /**
     * Generate the occurrences for the given recurring events.
     */
    public function occurrencesFor(
        RecurringEventCollection $recurringEvents,
        DateTimeImmutable $fromDate,
        DateTimeImmutable $toDate,
    ): EventOccurrenceCollection {
        $occurrences = [];

        $dailyRecurringEvents = $recurringEvents->filter(
            function (RecurringEventInterface $recurringEvent) {
                return $recurringEvent->recurrenceTypeId()->equals($this->id());
            },
        );

        foreach ($dailyRecurringEvents as $dailyRecurringEvent) {
            $recurringEventStartTime = $dailyRecurringEvent->startDate()->format('H:i:s');
            $recurringEventRecursUntil = $dailyRecurringEvent->recursUntil();

            $datePeriodStart = max($fromDate, $dailyRecurringEvent->startDate());

            $latestDatePeriodEnd = $datePeriodStart->add($this->recursUntilLimit());

            $datePeriodEnd = $recurringEventRecursUntil
                ? min($recurringEventRecursUntil, $toDate, $latestDatePeriodEnd)
                : min($toDate, $latestDatePeriodEnd);

            $datePeriod = new DatePeriod(
                $datePeriodStart,
                new DateInterval('P1D'),
                $datePeriodEnd,
                DatePeriod::INCLUDE_END_DATE,
            );

            foreach ($datePeriod as $date) {
                $occurrenceStartDate = new DateTimeImmutable($date->format('Y-m-d') . ' ' . $recurringEventStartTime);

                if ($occurrenceStartDate < $datePeriodStart) {
                    continue;
                }

                $occurrences[] = $this->eventOccurrenceFactory->createEventOccurrence(
                    $dailyRecurringEvent->id(),
                    $occurrenceStartDate,
                    $occurrenceStartDate->add($dailyRecurringEvent->duration()),
                );
            }
        }

        return EventOccurrenceCollection::fromArray(
            $occurrences,
        );
    }
}

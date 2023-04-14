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

class Weekly implements RecurrenceTypeInterface
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
        return RecurrenceTypeId::fromString('Weekly');
    }

    /**
     * Get a date interval that indicates the maximum limit of time that event occurrences
     * for a given event can be generated up to.
     */
    public function recursUntilLimit(): DateInterval
    {
        return new DateInterval('P5Y');
    }

    /**
     * Generate the occurrences for a given recurring event.
     */
    public function occurrencesFor(
        RecurringEventCollection $recurringEvents,
        DateTimeImmutable $fromDate,
        DateTimeImmutable $toDate,
    ): EventOccurrenceCollection {
        $occurrences = [];

        $weeklyRecurringEvents = $recurringEvents->filter(
            function (RecurringEventInterface $recurringEvent) {
                return $recurringEvent->recurrenceTypeId()->equals($this->id());
            },
        );

        foreach ($weeklyRecurringEvents as $weeklyRecurringEvent) {
            $recurringEventRecursUntil = $weeklyRecurringEvent->recursUntil();
            $weeklyEventTime = $weeklyRecurringEvent->startDate()->format('H:i:s');
            $dayOfTheWeek = $weeklyRecurringEvent->startDate()->format('w');

            $datePeriodStart = max($fromDate, $weeklyRecurringEvent->startDate());

            while ($datePeriodStart->format('w') != $dayOfTheWeek) {
                $datePeriodStart = $datePeriodStart->add(new DateInterval('P1D'));
            }

            $latestDatePeriodEnd = $datePeriodStart->add($this->recursUntilLimit());

            $datePeriodEnd = $recurringEventRecursUntil
                ? min($recurringEventRecursUntil, $toDate, $latestDatePeriodEnd)
                : min($toDate, $latestDatePeriodEnd);

            $datePeriod = new DatePeriod(
                $datePeriodStart,
                new DateInterval('P1W'),
                $datePeriodEnd,
                DatePeriod::INCLUDE_END_DATE,
            );

            foreach ($datePeriod as $date) {
                $occurrenceStartDate = new DateTimeImmutable($date->format('Y-m-d') . ' ' . $weeklyEventTime);

                if ($occurrenceStartDate < $datePeriodStart) {
                    continue;
                }

                $occurrences[] = $this->eventOccurrenceFactory->createEventOccurrence(
                    $weeklyRecurringEvent->id(),
                    $occurrenceStartDate,
                    $occurrenceStartDate->add($weeklyRecurringEvent->duration()),
                );
            }
        }

        return EventOccurrenceCollection::fromArray(
            $occurrences,
        );
    }
}

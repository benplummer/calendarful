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

class MonthlyDate implements RecurrenceTypeInterface
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
        return RecurrenceTypeId::fromString('MonthlyDate');
    }

    /**
     * Get a date interval that indicates the maximum limit of time that event occurrences
     * for a given event can be generated up to.
     *
     * @return DateInterval
     * @abstract
     */
    public function recursUntilLimit(): DateInterval
    {
        return new DateInterval('P25Y');
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

        $monthlyDateRecurringEvents = $recurringEvents->filter(
            function (RecurringEventInterface $recurringEvent) {
                return $recurringEvent->recurrenceTypeId()->equals($this->id());
            },
        );

        $startOfMonthForFromDate = new DateTimeImmutable($fromDate->format('Y-m-01 H:i:s'));

        foreach ($monthlyDateRecurringEvents as $monthlyDateRecurringEvent) {
            $recurringEventStartDate = $monthlyDateRecurringEvent->startDate();
            $recurringEventStartTime = $recurringEventStartDate->format('H:i:s');

            $monthlyDate = $recurringEventStartDate->format('d');
            $startOfMonthForRecurringEvent = new DateTimeImmutable($recurringEventStartDate->format('Y-m-01 H:i:s'));

            $recurringEventRecursUntil = $monthlyDateRecurringEvent->recursUntil();

            $datePeriodStart = max($startOfMonthForFromDate, $startOfMonthForRecurringEvent);

            $latestDatePeriodEnd = $datePeriodStart->add($this->recursUntilLimit());

            $datePeriodEnd = $recurringEventRecursUntil
                ? min($recurringEventRecursUntil, $toDate, $latestDatePeriodEnd)
                : min($toDate, $latestDatePeriodEnd);

            $datePeriod = new DatePeriod(
                $datePeriodStart,
                new DateInterval('P1M'),
                $datePeriodEnd,
                DatePeriod::INCLUDE_END_DATE,
            );

            foreach ($datePeriod as $date) {
                if ($monthlyDate > $date->format('t')) {
                    continue;
                }

                $occurrenceStartDate = new DateTimeImmutable(
                    $date->format('Y-m-' . $monthlyDate) . ' ' . $recurringEventStartTime
                );

                if ($occurrenceStartDate < $datePeriodStart) {
                    continue;
                }

                $occurrences[] = $this->eventOccurrenceFactory->createEventOccurrence(
                    $monthlyDateRecurringEvent->id(),
                    $occurrenceStartDate,
                    $occurrenceStartDate->add($monthlyDateRecurringEvent->duration()),
                );
            }
        }

        return EventOccurrenceCollection::fromArray(
            $occurrences,
        );
    }
}

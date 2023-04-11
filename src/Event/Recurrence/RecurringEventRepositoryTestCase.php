<?php

declare(strict_types=1);

namespace Plummer\Calendarful\Event\Recurrence;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Plummer\Calendarful\Event\Recurrence\RecurrenceType\RecurrenceTypeId;
use Plummer\Calendarful\Event\Recurrence\RecurringEventRepositoryInterface;
use Plummer\Calendarful\TestUtilities\EventIdFake;
use Plummer\Calendarful\TestUtilities\RecurringEventFake;

abstract class RecurringEventRepositoryTestCase extends TestCase
{
    abstract protected function createRecurringEventRepository(): RecurringEventRepositoryInterface;

    /**
     * @test
     */
    public function it_should_fetch_recurring_events_for_the_given_date_range(): void
    {
        $recurringEventRepository = $this->createRecurringEventRepository();

        /**
         * Valid Recurring Event Scenario
         *
         * The original recurring event has a start date before the given date range but
         * no recurs until date.
         */
        $recurringEventRepository->add(
            new RecurringEventFake(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-05-01 10:00:00'),
                new DateTimeImmutable('2023-05-01 12:00:00'),
                RecurrenceTypeId::fromString('Daily'),
            ),
            new RecurringEventFake(
                new EventIdFake('2'),
                new DateTimeImmutable('2023-05-06 11:30:00'),
                new DateTimeImmutable('2023-05-06 14:30:00'),
                RecurrenceTypeId::fromString('Weekly'),
            ),
        );

        /**
         * Valid Recurring Event Scenario
         *
         * The original recurring event has a start date before the given date range
         * and a recurs until date before the date range but the duration of the recurring
         * event must exceed the difference in time between the recurs until date and date
         * range from date.
         */
        $recurringEventRepository->add(
            new RecurringEventFake(
                new EventIdFake('3'),
                new DateTimeImmutable('2023-05-17 18:00:00'),
                new DateTimeImmutable('2023-05-24 18:00:00'),
                RecurrenceTypeId::fromString('Weekly'),
                new DateTimeImmutable('2023-05-25 00:00:00'),
            ),
            new RecurringEventFake(
                new EventIdFake('4'),
                new DateTimeImmutable('2023-05-25 23:00:00'),
                new DateTimeImmutable('2023-05-26 00:00:00'),
                RecurrenceTypeId::fromString('Daily'),
                new DateTimeImmutable('2023-05-31 23:59:59'),
            ),
            new RecurringEventFake(
                new EventIdFake('5'),
                new DateTimeImmutable('2023-05-15 09:00:00'),
                new DateTimeImmutable('2023-05-15 17:00:00'),
                RecurrenceTypeId::fromString('Weekly'),
                new DateTimeImmutable('2023-05-31 23:59:59'),
            ),
        );

        /**
         * Valid Recurring Event Scenario
         *
         * The original recurring event start date falls within the given date range.
         */
        $recurringEventRepository->add(
            new RecurringEventFake(
                new EventIdFake('6'),
                new DateTimeImmutable('2023-06-01 00:00:00'),
                new DateTimeImmutable('2023-06-01 08:00:00'),
                RecurrenceTypeId::fromString('Daily'),
            ),
            new RecurringEventFake(
                new EventIdFake('7'),
                new DateTimeImmutable('2023-06-22 18:00:00'),
                new DateTimeImmutable('2023-06-23 02:00:00'),
                RecurrenceTypeId::fromString('Daily'),
                new DateTimeImmutable('2023-06-30 23:59:59'),
            ),
            new RecurringEventFake(
                new EventIdFake('8'),
                new DateTimeImmutable('2023-06-30 23:00:00'),
                new DateTimeImmutable('2023-07-01 00:30:00'),
                RecurrenceTypeId::fromString('Daily'),
            ),
        );

        /**
         * Valid Recurring Event Scenario
         *
         * The original recurring event end date falls within the given date range.
         */
        $recurringEventRepository->add(
            new RecurringEventFake(
                new EventIdFake('9'),
                new DateTimeImmutable('2023-05-31 23:00:00'),
                new DateTimeImmutable('2023-06-01 00:00:00'),
                RecurrenceTypeId::fromString('Daily'),
            ),
        );

        /**
         * Valid Recurring Event Scenario
         *
         * The original recurring event has a start date before the given date range
         * and a recurs until date within the date range.
         */
        $recurringEventRepository->add(
            new RecurringEventFake(
                new EventIdFake('10'),
                new DateTimeImmutable('2023-05-01 10:00:00'),
                new DateTimeImmutable('2023-05-01 12:00:00'),
                RecurrenceTypeId::fromString('Daily'),
                new DateTimeImmutable('2023-06-15 23:59:59'),
            ),
        );

        /**
         * Valid Recurring Event Scenario
         *
         * The original recurring event has a start date before the given date range
         * and a recurs until date after the date range.
         */
        $recurringEventRepository->add(
            new RecurringEventFake(
                new EventIdFake('11'),
                new DateTimeImmutable('2023-05-01 10:00:00'),
                new DateTimeImmutable('2023-05-01 12:00:00'),
                RecurrenceTypeId::fromString('Daily'),
                new DateTimeImmutable('2023-07-31 23:59:59'),
            ),
        );

        /**
         * Invalid Recurring Events
         */
        $recurringEventRepository->add(
            new RecurringEventFake(
                new EventIdFake('12'),
                new DateTimeImmutable('2023-05-08 08:00:00'),
                new DateTimeImmutable('2023-05-10 20:00:00'),
                RecurrenceTypeId::fromString('Weekly'),
                new DateTimeImmutable('2023-05-27 23:59:59'),
            ),
            new RecurringEventFake(
                new EventIdFake('13'),
                new DateTimeImmutable('2023-07-01 00:00:00'),
                new DateTimeImmutable('2023-07-01 06:00:00'),
                RecurrenceTypeId::fromString('Daily'),
            ),
            new RecurringEventFake(
                new EventIdFake('14'),
                new DateTimeImmutable('2023-07-05 11:00:00'),
                new DateTimeImmutable('2023-07-05 11:30:00'),
                RecurrenceTypeId::fromString('Weekly'),
                new DateTimeImmutable('2023-07-31 23:59:59'),
            ),
        );

        $fromDate = new DateTimeImmutable('2023-06-01 00:00:00');
        $toDate = new DateTimeImmutable('2023-06-30 23:59:59');

        $recurringEvents = $recurringEventRepository->forDateRange(
            $fromDate,
            $toDate,
        );

        $this->assertCount(
            11,
            $recurringEvents,
        );

        $this->assertCount(
            11,
            $recurringEvents->filter(
                function (RecurringEventInterface $recurringEvent) {
                    return in_array(
                        $recurringEvent->id()->id(),
                        [
                            '1',
                            '2',
                            '3',
                            '4',
                            '5',
                            '6',
                            '7',
                            '8',
                            '9',
                            '10',
                            '11'
                        ],
                    );
                }
            ),
        );
    }
}

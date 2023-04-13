<?php

declare(strict_types=1);

namespace Plummer\Calendarful\Event\Recurrence\RecurringEvent;

use DateTimeImmutable;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Plummer\Calendarful\Event\Recurrence\RecurrenceType\RecurrenceTypeId;
use Plummer\Calendarful\TestUtilities\EventIdFake;
use Plummer\Calendarful\TestUtilities\RecurringEventFake;

class RecurringEventCollectionTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_allow_creation_from_an_array_of_recurring_events(): void
    {
        $recurringEvent1 = new RecurringEventFake(
            new EventIdFake('1'),
            new DateTimeImmutable('2023-06-06 10:00:00'),
            new DateTimeImmutable('2023-06-06 12:00:00'),
            RecurrenceTypeId::fromString('Daily'),
            new DateTimeImmutable('2023-06-30 23:59:59'),
        );

        $recurringEvent2 = new RecurringEventFake(
            new EventIdFake('2'),
            new DateTimeImmutable('2023-06-15 17:00:00'),
            new DateTimeImmutable('2023-06-15 17:30:00'),
            RecurrenceTypeId::fromString('Weekly'),
            new DateTimeImmutable('2023-12-31 23:59:59'),
        );

        $recurringEvent3 = new RecurringEventFake(
            new EventIdFake('3'),
            new DateTimeImmutable('2023-06-23 23:00:00'),
            new DateTimeImmutable('2023-06-24 03:00:00'),
            RecurrenceTypeId::fromString('MonthlyDate'),
        );

        $recurringEvents = [
            0 => $recurringEvent1,
            'one' => $recurringEvent2,
            $recurringEvent3,
        ];

        /**
         * @psalm-suppress InvalidArgument
         * @phpstan-ignore-next-line
         */
        $recurringEventCollection = RecurringEventCollection::fromArray($recurringEvents);

        $this->assertCount(
            3,
            $recurringEventCollection
        );

        $this->assertEquals(
            [
                0 => $recurringEvent1,
                1 => $recurringEvent2,
                2 => $recurringEvent3,
            ],
            iterator_to_array($recurringEventCollection),
        );
    }

    /**
     * @test
     */
    public function it_should_allow_creation_from_an_empty_array(): void
    {
        $recurringEventCollection = RecurringEventCollection::fromArray(
            [],
        );

        $this->assertCount(
            0,
            $recurringEventCollection,
        );

        $this->assertEquals(
            [],
            iterator_to_array($recurringEventCollection),
        );
    }

    /**
     * @test
     */
    // phpcs:ignore Generic.Files.LineLength.TooLong
    public function it_should_throw_an_exception_if_creation_is_attempted_with_an_array_containing_any_non_recurring_events(): void
    {
        $this->expectException(InvalidArgumentException::class);

        /**
         * @psalm-suppress InvalidArgument
         * @phpstan-ignore-next-line
         */
        RecurringEventCollection::fromArray([
            new RecurringEventFake(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-06-06 10:00:00'),
                new DateTimeImmutable('2023-06-06 12:00:00'),
                RecurrenceTypeId::fromString('Daily'),
                new DateTimeImmutable('2023-06-30 23:59:59'),
            ),
            'Not A Recurring Event',
            123,
        ]);
    }

    /**
     * @test
     */
    public function it_should_return_a_count_of_the_contained_events(): void
    {
        $recurringEventCollection = RecurringEventCollection::fromArray([
            new RecurringEventFake(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-06-06 10:00:00'),
                new DateTimeImmutable('2023-06-06 12:00:00'),
                RecurrenceTypeId::fromString('Daily'),
                new DateTimeImmutable('2023-06-30 23:59:59'),
            ),
            new RecurringEventFake(
                new EventIdFake('2'),
                new DateTimeImmutable('2023-06-15 17:00:00'),
                new DateTimeImmutable('2023-06-15 17:30:00'),
                RecurrenceTypeId::fromString('Weekly'),
                new DateTimeImmutable('2023-12-31 23:59:59'),
            ),
            new RecurringEventFake(
                new EventIdFake('3'),
                new DateTimeImmutable('2023-06-23 23:00:00'),
                new DateTimeImmutable('2023-06-24 03:00:00'),
                RecurrenceTypeId::fromString('MonthlyDate'),
            ),
        ]);

        $this->assertEquals(
            3,
            $recurringEventCollection->count(),
        );
    }

    /**
     * @test
     */
    public function it_should_be_iterable(): void
    {
        $recurringEvents = [
            new RecurringEventFake(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-06-06 10:00:00'),
                new DateTimeImmutable('2023-06-06 12:00:00'),
                RecurrenceTypeId::fromString('Daily'),
                new DateTimeImmutable('2023-06-30 23:59:59'),
            ),
            new RecurringEventFake(
                new EventIdFake('2'),
                new DateTimeImmutable('2023-06-15 17:00:00'),
                new DateTimeImmutable('2023-06-15 17:30:00'),
                RecurrenceTypeId::fromString('Weekly'),
                new DateTimeImmutable('2023-12-31 23:59:59'),
            ),
            new RecurringEventFake(
                new EventIdFake('3'),
                new DateTimeImmutable('2023-06-23 23:00:00'),
                new DateTimeImmutable('2023-06-24 03:00:00'),
                RecurrenceTypeId::fromString('MonthlyDate'),
            ),
        ];

        $recurringEventCollection = RecurringEventCollection::fromArray(
            $recurringEvents,
        );

        foreach ($recurringEventCollection as $key => $recurringEvent) {
            $this->assertEquals(
                $recurringEvents[$key],
                $recurringEvent,
            );
        }
    }

    /**
     * @test
     */
    public function it_should_be_filterable(): void
    {
        $recurringEvent1 = new RecurringEventFake(
            new EventIdFake('1'),
            new DateTimeImmutable('2023-06-06 10:00:00'),
            new DateTimeImmutable('2023-06-06 12:00:00'),
            RecurrenceTypeId::fromString('Daily'),
            new DateTimeImmutable('2023-06-30 23:59:59'),
        );

        $recurringEvent2 = new RecurringEventFake(
            new EventIdFake('2'),
            new DateTimeImmutable('2023-06-15 17:00:00'),
            new DateTimeImmutable('2023-06-15 17:30:00'),
            RecurrenceTypeId::fromString('Weekly'),
            new DateTimeImmutable('2023-12-31 23:59:59'),
        );

        $recurringEvent3 = new RecurringEventFake(
            new EventIdFake('3'),
            new DateTimeImmutable('2023-06-23 23:00:00'),
            new DateTimeImmutable('2023-06-24 03:00:00'),
            RecurrenceTypeId::fromString('MonthlyDate'),
        );

        $recurringEventCollection = RecurringEventCollection::fromArray(
            [
                $recurringEvent1,
                $recurringEvent2,
                $recurringEvent3,
            ]
        );

        $this->assertCount(
            3,
            $recurringEventCollection
        );

        $filteredRecurringEventCollection = $recurringEventCollection->filter(
            function (RecurringEventInterface $recurringEvent): bool {
                return $recurringEvent->id()->equals(
                    new EventIdFake('1')
                );
            },
        );

        $this->assertCount(
            1,
            $filteredRecurringEventCollection,
        );

        $this->assertEquals(
            [
                $recurringEvent1,
            ],
            iterator_to_array(
                $filteredRecurringEventCollection,
            ),
        );
    }
}

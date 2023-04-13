<?php

declare(strict_types=1);

namespace Plummer\Calendarful\Event\Recurrence\RecurrenceType\Type;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Plummer\Calendarful\Event\Recurrence\EventOccurrence\EventOccurrence;
use Plummer\Calendarful\Event\Recurrence\EventOccurrence\EventOccurrenceInterface;
use Plummer\Calendarful\Event\Recurrence\RecurringEvent\RecurringEventCollection;
use Plummer\Calendarful\TestUtilities\EventIdFake;
use Plummer\Calendarful\TestUtilities\RecurringEventFake;

class DailyTest extends TestCase
{
    /**
     * @test
     */
    // phpcs:ignore Generic.Files.LineLength.TooLong
    public function it_should_generate_daily_occurrences_for_a_one_day_event_that_starts_within_the_date_range(): void
    {
        $dailyRecurrenceType = new Daily();

        $recurringEvents = RecurringEventCollection::fromArray([
            new RecurringEventFake(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-06-01 00:00:00'),
                new DateTimeImmutable('2023-06-01 02:00:00'),
                $dailyRecurrenceType->id()
            ),
        ]);

        $fromDate = new \DateTimeImmutable('2023-06-01 00:00:00');
        $toDate = new \DateTimeImmutable('2023-06-30 23:59:59');

        $generatedDailyOccurrences = $dailyRecurrenceType->occurrencesFor(
            $recurringEvents,
            $fromDate,
            $toDate,
        );

        $this->assertCount(
            30,
            $generatedDailyOccurrences,
        );

        $this->assertContainsEquals(
            EventOccurrence::create(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-06-01 00:00:00'),
                new DateTimeImmutable('2023-06-01 02:00:00'),
            ),
            $generatedDailyOccurrences,
        );

        $this->assertContainsEquals(
            EventOccurrence::create(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-06-30 00:00:00'),
                new DateTimeImmutable('2023-06-30 02:00:00'),
            ),
            $generatedDailyOccurrences,
        );
    }

    /**
     * @test
     */
    // phpcs:ignore Generic.Files.LineLength.TooLong
    public function it_should_generate_daily_occurrences_for_a_one_day_event_that_starts_within_the_date_range_and_has_a_recurs_until_date(): void
    {
        $dailyRecurrenceType = new Daily();

        $recurringEvents = RecurringEventCollection::fromArray([
            new RecurringEventFake(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-06-01 00:00:00'),
                new DateTimeImmutable('2023-06-01 02:00:00'),
                $dailyRecurrenceType->id(),
                new DateTimeImmutable('2023-06-15 23:59:59'),
            ),
        ]);

        $fromDate = new \DateTimeImmutable('2023-06-01 00:00:00');
        $toDate = new \DateTimeImmutable('2023-06-30 23:59:59');

        $generatedDailyOccurrences = $dailyRecurrenceType->occurrencesFor(
            $recurringEvents,
            $fromDate,
            $toDate,
        );

        $this->assertCount(
            15,
            $generatedDailyOccurrences,
        );

        $this->assertContainsEquals(
            EventOccurrence::create(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-06-01 00:00:00'),
                new DateTimeImmutable('2023-06-01 02:00:00'),
            ),
            $generatedDailyOccurrences,
        );

        $this->assertContainsEquals(
            EventOccurrence::create(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-06-15 00:00:00'),
                new DateTimeImmutable('2023-06-15 02:00:00'),
            ),
            $generatedDailyOccurrences,
        );
    }

    /**
     * @test
     */
    // phpcs:ignore Generic.Files.LineLength.TooLong
    public function it_should_generate_daily_occurrences_for_a_multiple_day_event_that_starts_within_the_date_range(): void
    {
        $dailyRecurrenceType = new Daily();

        $recurringEvents = RecurringEventCollection::fromArray([
            new RecurringEventFake(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-06-01 00:00:00'),
                new DateTimeImmutable('2023-06-03 02:00:00'),
                $dailyRecurrenceType->id(),
            ),
        ]);

        $fromDate = new \DateTimeImmutable('2023-06-01 00:00:00');
        $toDate = new \DateTimeImmutable('2023-06-30 23:59:59');

        $generatedDailyOccurrences = $dailyRecurrenceType->occurrencesFor(
            $recurringEvents,
            $fromDate,
            $toDate,
        );

        $this->assertCount(
            30,
            $generatedDailyOccurrences,
        );

        $this->assertContainsEquals(
            EventOccurrence::create(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-06-01 00:00:00'),
                new DateTimeImmutable('2023-06-03 02:00:00'),
            ),
            $generatedDailyOccurrences,
        );

        $this->assertContainsEquals(
            EventOccurrence::create(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-06-30 00:00:00'),
                new DateTimeImmutable('2023-07-02 02:00:00'),
            ),
            $generatedDailyOccurrences,
        );
    }

    /**
     * @test
     */
    // phpcs:ignore Generic.Files.LineLength.TooLong
    public function it_should_generate_daily_occurrences_for_a_multiple_day_event_that_starts_within_the_date_range_and_has_a_recurs_until_date(): void
    {
        $dailyRecurrenceType = new Daily();

        $recurringEvents = RecurringEventCollection::fromArray([
            new RecurringEventFake(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-06-01 00:00:00'),
                new DateTimeImmutable('2023-06-03 02:00:00'),
                $dailyRecurrenceType->id(),
                new DateTimeImmutable('2023-06-15 23:59:59'),
            ),
        ]);

        $fromDate = new \DateTimeImmutable('2023-06-01 00:00:00');
        $toDate = new \DateTimeImmutable('2023-06-30 23:59:59');

        $generatedDailyOccurrences = $dailyRecurrenceType->occurrencesFor(
            $recurringEvents,
            $fromDate,
            $toDate,
        );

        $this->assertCount(
            15,
            $generatedDailyOccurrences,
        );

        $this->assertContainsEquals(
            EventOccurrence::create(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-06-01 00:00:00'),
                new DateTimeImmutable('2023-06-03 02:00:00'),
            ),
            $generatedDailyOccurrences,
        );

        $this->assertContainsEquals(
            EventOccurrence::create(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-06-15 00:00:00'),
                new DateTimeImmutable('2023-06-17 02:00:00'),
            ),
            $generatedDailyOccurrences,
        );
    }

    /**
     * @test
     */
    public function it_should_generate_daily_occurrences_for_a_one_day_event_that_starts_before_the_date_range(): void
    {
        $dailyRecurrenceType = new Daily();

        $recurringEvents = RecurringEventCollection::fromArray([
            new RecurringEventFake(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-06-01 00:00:00'),
                new DateTimeImmutable('2023-06-01 02:00:00'),
                $dailyRecurrenceType->id(),
            ),
        ]);

        $fromDate = new \DateTimeImmutable('2023-06-10 00:00:00');
        $toDate = new \DateTimeImmutable('2023-06-30 23:59:59');

        $generatedDailyOccurrences = $dailyRecurrenceType->occurrencesFor(
            $recurringEvents,
            $fromDate,
            $toDate,
        );

        $this->assertCount(
            21,
            $generatedDailyOccurrences,
        );

        $this->assertContainsEquals(
            EventOccurrence::create(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-06-10 00:00:00'),
                new DateTimeImmutable('2023-06-10 02:00:00'),
            ),
            $generatedDailyOccurrences,
        );

        $this->assertContainsEquals(
            EventOccurrence::create(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-06-30 00:00:00'),
                new DateTimeImmutable('2023-06-30 02:00:00'),
            ),
            $generatedDailyOccurrences,
        );
    }

    /**
     * @test
     */
    // phpcs:ignore Generic.Files.LineLength.TooLong
    public function it_should_generate_daily_occurrences_for_a_one_day_event_that_starts_before_the_date_range_and_has_a_recurs_until_date(): void
    {
        $dailyRecurrenceType = new Daily();

        $recurringEvents = RecurringEventCollection::fromArray([
            new RecurringEventFake(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-06-01 00:00:00'),
                new DateTimeImmutable('2023-06-01 02:00:00'),
                $dailyRecurrenceType->id(),
                new DateTimeImmutable('2023-06-20 23:59:59'),
            ),
        ]);

        $fromDate = new \DateTimeImmutable('2023-06-10 00:00:00');
        $toDate = new \DateTimeImmutable('2023-06-30 23:59:59');

        $generatedDailyOccurrences = $dailyRecurrenceType->occurrencesFor(
            $recurringEvents,
            $fromDate,
            $toDate,
        );

        $this->assertCount(
            11,
            $generatedDailyOccurrences,
        );

        $this->assertContainsEquals(
            EventOccurrence::create(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-06-10 00:00:00'),
                new DateTimeImmutable('2023-06-10 02:00:00'),
            ),
            $generatedDailyOccurrences,
        );

        $this->assertContainsEquals(
            EventOccurrence::create(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-06-20 00:00:00'),
                new DateTimeImmutable('2023-06-20 02:00:00'),
            ),
            $generatedDailyOccurrences,
        );
    }

    /**
     * @test
     */
    // phpcs:ignore Generic.Files.LineLength.TooLong
    public function it_should_generate_daily_occurrences_for_a_multiple_day_event_that_starts_before_the_date_range(): void
    {
        $dailyRecurrenceType = new Daily();

        $recurringEvents = RecurringEventCollection::fromArray([
            new RecurringEventFake(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-06-01 00:00:00'),
                new DateTimeImmutable('2023-06-03 02:00:00'),
                $dailyRecurrenceType->id(),
            ),
        ]);

        $fromDate = new \DateTimeImmutable('2023-06-10 00:00:00');
        $toDate = new \DateTimeImmutable('2023-06-30 23:59:59');

        $generatedDailyOccurrences = $dailyRecurrenceType->occurrencesFor(
            $recurringEvents,
            $fromDate,
            $toDate,
        );

        $this->assertCount(
            21,
            $generatedDailyOccurrences,
        );

        $this->assertContainsEquals(
            EventOccurrence::create(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-06-10 00:00:00'),
                new DateTimeImmutable('2023-06-12 02:00:00'),
            ),
            $generatedDailyOccurrences,
        );

        $this->assertContainsEquals(
            EventOccurrence::create(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-06-30 00:00:00'),
                new DateTimeImmutable('2023-07-02 02:00:00'),
            ),
            $generatedDailyOccurrences,
        );
    }

    /**
     * @test
     */
    // phpcs:ignore Generic.Files.LineLength.TooLong
    public function it_should_generate_daily_occurrences_for_a_multiple_day_event_that_starts_before_the_date_range_and_has_a_recurs_until_date(): void
    {
        $dailyRecurrenceType = new Daily();

        $recurringEvents = RecurringEventCollection::fromArray([
            new RecurringEventFake(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-06-01 00:00:00'),
                new DateTimeImmutable('2023-06-03 02:00:00'),
                $dailyRecurrenceType->id(),
                new DateTimeImmutable('2023-06-20 23:59:59'),
            ),
        ]);

        $fromDate = new \DateTimeImmutable('2023-06-10 00:00:00');
        $toDate = new \DateTimeImmutable('2023-06-30 23:59:59');

        $generatedDailyOccurrences = $dailyRecurrenceType->occurrencesFor(
            $recurringEvents,
            $fromDate,
            $toDate,
        );

        $this->assertCount(
            11,
            $generatedDailyOccurrences,
        );

        $this->assertContainsEquals(
            EventOccurrence::create(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-06-10 00:00:00'),
                new DateTimeImmutable('2023-06-12 02:00:00'),
            ),
            $generatedDailyOccurrences,
        );

        $this->assertContainsEquals(
            EventOccurrence::create(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-06-20 00:00:00'),
                new DateTimeImmutable('2023-06-22 02:00:00'),
            ),
            $generatedDailyOccurrences,
        );
    }

    /**
     * @test
     */
    public function it_should_not_generate_occurrences_outside_of_the_date_period(): void
    {
        $dailyRecurrenceType = new Daily();

        $recurringEvents = RecurringEventCollection::fromArray([
            new RecurringEventFake(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-06-01 15:00:00'),
                new DateTimeImmutable('2023-06-01 17:00:00'),
                $dailyRecurrenceType->id(),
            ),
        ]);

        $fromDate = new DateTimeImmutable('2023-06-01 18:00:00');
        $toDate = new DateTimeImmutable('2023-06-30 12:00:00');

        $generatedDailyOccurrences = $dailyRecurrenceType->occurrencesFor(
            $recurringEvents,
            $fromDate,
            $toDate,
        );

        $this->assertCount(
            28,
            $generatedDailyOccurrences,
        );

        $this->assertNotContainsEquals(
            EventOccurrence::create(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-06-01 15:00:00'),
                new DateTimeImmutable('2023-06-01 17:00:00'),
            ),
            $generatedDailyOccurrences,
        );

        $this->assertNotContainsEquals(
            EventOccurrence::create(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-06-30 15:00:00'),
                new DateTimeImmutable('2023-06-30 17:00:00'),
            ),
            $generatedDailyOccurrences,
        );
    }

    /**
     * @test
     */
    public function it_should_not_generate_occurrences_beyond_the_recurs_until_limit(): void
    {
        $dailyRecurrenceType = new Daily();

        $recurringEvents = RecurringEventCollection::fromArray([
            new RecurringEventFake(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-06-01 00:00:00'),
                new DateTimeImmutable('2023-06-01 02:00:00'),
                $dailyRecurrenceType->id(),
            ),
        ]);

        $fromDate = new DateTimeImmutable('2023-06-01 00:00:00');
        $toDate = new DateTimeImmutable('2025-06-01 00:00:00');

        $generatedDailyOccurrences = iterator_to_array(
            $dailyRecurrenceType->occurrencesFor(
                $recurringEvents,
                $fromDate,
                $toDate,
            ),
        );

        $lastOccurrence = end($generatedDailyOccurrences);

        $this->assertEquals(
            new DateTimeImmutable('2024-06-01 00:00:00'),
            $lastOccurrence ? $lastOccurrence->startDate() : false
        );
    }

    /**
     * @test
     */
    public function it_should_not_generate_occurrences_for_recurring_events_with_a_different_recurrence_type(): void
    {
        $dailyRecurrenceType = new Daily();
        $weeklyRecurrenceType = new Weekly();
        $monthlyDateRecurrenceType = new MonthlyDate();

        $recurringEvents = RecurringEventCollection::fromArray([
            new RecurringEventFake(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-06-01 00:00:00'),
                new DateTimeImmutable('2023-06-01 02:00:00'),
                $dailyRecurrenceType->id(),
            ),
            new RecurringEventFake(
                new EventIdFake('2'),
                new DateTimeImmutable('2023-06-01 02:00:00'),
                new DateTimeImmutable('2023-06-01 04:00:00'),
                $weeklyRecurrenceType->id(),
            ),
            new RecurringEventFake(
                new EventIdFake('3'),
                new DateTimeImmutable('2023-06-01 04:00:00'),
                new DateTimeImmutable('2023-06-01 06:00:00'),
                $monthlyDateRecurrenceType->id(),
            ),
        ]);

        $fromDate = new \DateTimeImmutable('2023-06-01 00:00:00');
        $toDate = new \DateTimeImmutable('2023-06-30 23:59:59');

        $generatedOccurrences = $dailyRecurrenceType->occurrencesFor(
            $recurringEvents,
            $fromDate,
            $toDate,
        );

        $this->assertCount(
            30,
            $generatedOccurrences,
        );

        $this->assertEmpty(
            $generatedOccurrences->filter(
                function (EventOccurrenceInterface $eventOccurrence) {
                    return $eventOccurrence->startDate() === new DateTimeImmutable('2023-06-01 02:00:00');
                },
            ),
        );

        $this->assertEmpty(
            $generatedOccurrences->filter(
                function (EventOccurrenceInterface $eventOccurrence) {
                    return $eventOccurrence->startDate() === new DateTimeImmutable('2023-06-01 04:00:00');
                },
            ),
        );
    }
}

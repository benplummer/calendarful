<?php

declare(strict_types=1);

namespace Plummer\Calendarful\Event\Recurrence\RecurrenceType\Type;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Plummer\Calendarful\Event\Recurrence\EventOccurrence;
use Plummer\Calendarful\Event\Recurrence\EventOccurrenceInterface;
use Plummer\Calendarful\Event\Recurrence\RecurringEventCollection;
use Plummer\Calendarful\TestUtilities\EventIdFake;
use Plummer\Calendarful\TestUtilities\RecurringEventFake;

class WeeklyTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_generate_weekly_occurrences_for_a_one_day_event_that_starts_within_the_date_range(): void
    {
        $weeklyRecurrenceType = new Weekly();

        $recurringEvents = RecurringEventCollection::fromArray([
            new RecurringEventFake(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-06-01 00:00:00'),
                new DateTimeImmutable('2023-06-01 02:00:00'),
                $weeklyRecurrenceType->id(),
            ),
        ]);

        $fromDate = new DateTimeImmutable('2023-06-01 00:00:00');
        $toDate = new DateTimeImmutable('2023-06-29 23:59:59');

        $generatedWeeklyOccurrences = $weeklyRecurrenceType->occurrencesFor(
            $recurringEvents,
            $fromDate,
            $toDate,
        );

        $this->assertCount(
            5,
            $generatedWeeklyOccurrences,
        );

        $this->assertContainsEquals(
            EventOccurrence::create(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-06-01 00:00:00'),
                new DateTimeImmutable('2023-06-01 02:00:00'),
            ),
            $generatedWeeklyOccurrences,
        );

        $this->assertContainsEquals(
            EventOccurrence::create(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-06-29 00:00:00'),
                new DateTimeImmutable('2023-06-29 02:00:00'),
            ),
            $generatedWeeklyOccurrences,
        );
    }

    /**
     * @test
     */
    // phpcs:ignore Generic.Files.LineLength.TooLong
    public function it_should_generate_weekly_occurrences_for_a_one_day_event_that_starts_within_the_date_range_and_has_a_recurs_until_date(): void
    {
        $weeklyRecurrenceType = new Weekly();

        $recurringEvents = RecurringEventCollection::fromArray([
            new RecurringEventFake(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-06-01 00:00:00'),
                new DateTimeImmutable('2023-06-01 02:00:00'),
                $weeklyRecurrenceType->id(),
                new DateTimeImmutable('2023-06-15 23:59:59'),
            ),
        ]);

        $fromDate = new DateTimeImmutable('2023-06-01 00:00:00');
        $toDate = new DateTimeImmutable('2023-06-29 23:59:59');

        $generatedWeeklyOccurrences = $weeklyRecurrenceType->occurrencesFor(
            $recurringEvents,
            $fromDate,
            $toDate,
        );

        $this->assertCount(
            3,
            $generatedWeeklyOccurrences,
        );

        $this->assertContainsEquals(
            EventOccurrence::create(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-06-01 00:00:00'),
                new DateTimeImmutable('2023-06-01 02:00:00'),
            ),
            $generatedWeeklyOccurrences,
        );

        $this->assertContainsEquals(
            EventOccurrence::create(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-06-15 00:00:00'),
                new DateTimeImmutable('2023-06-15 02:00:00'),
            ),
            $generatedWeeklyOccurrences,
        );
    }

    /**
     * @test
     */
    // phpcs:ignore Generic.Files.LineLength.TooLong
    public function it_should_generate_weekly_occurrences_for_a_multiple_day_event_that_starts_within_the_date_range(): void
    {
        $weeklyRecurrenceType = new Weekly();

        $recurringEvents = RecurringEventCollection::fromArray([
            new RecurringEventFake(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-06-01 00:00:00'),
                new DateTimeImmutable('2023-06-03 02:00:00'),
                $weeklyRecurrenceType->id(),
            ),
        ]);

        $fromDate = new DateTimeImmutable('2023-06-01 00:00:00');
        $toDate = new DateTimeImmutable('2023-06-29 23:59:59');

        $generatedWeeklyOccurrences = $weeklyRecurrenceType->occurrencesFor(
            $recurringEvents,
            $fromDate,
            $toDate,
        );

        $this->assertCount(
            5,
            $generatedWeeklyOccurrences,
        );

        $this->assertContainsEquals(
            EventOccurrence::create(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-06-01 00:00:00'),
                new DateTimeImmutable('2023-06-03 02:00:00'),
            ),
            $generatedWeeklyOccurrences,
        );

        $this->assertContainsEquals(
            EventOccurrence::create(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-06-29 00:00:00'),
                new DateTimeImmutable('2023-07-01 02:00:00'),
            ),
            $generatedWeeklyOccurrences,
        );
    }

    /**
     * @test
     */
    // phpcs:ignore Generic.Files.LineLength.TooLong
    public function it_should_generate_weekly_occurrences_for_a_multiple_day_event_that_starts_within_the_date_range_and_has_a_recurs_until_date(): void
    {
        $weeklyRecurrenceType = new Weekly();

        $recurringEvents = RecurringEventCollection::fromArray([
            new RecurringEventFake(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-06-01 00:00:00'),
                new DateTimeImmutable('2023-06-03 02:00:00'),
                $weeklyRecurrenceType->id(),
                new DateTimeImmutable('2023-06-15 23:59:59'),
            ),
        ]);

        $fromDate = new DateTimeImmutable('2023-06-01 00:00:00');
        $toDate = new DateTimeImmutable('2023-06-29 23:59:59');

        $generatedWeeklyOccurrences = $weeklyRecurrenceType->occurrencesFor(
            $recurringEvents,
            $fromDate,
            $toDate,
        );

        $this->assertCount(
            3,
            $generatedWeeklyOccurrences,
        );

        $this->assertContainsEquals(
            EventOccurrence::create(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-06-01 00:00:00'),
                new DateTimeImmutable('2023-06-03 02:00:00'),
            ),
            $generatedWeeklyOccurrences,
        );

        $this->assertContainsEquals(
            EventOccurrence::create(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-06-15 00:00:00'),
                new DateTimeImmutable('2023-06-17 02:00:00'),
            ),
            $generatedWeeklyOccurrences,
        );
    }

    /**
     * @test
     */
    public function it_should_generate_weekly_occurrences_for_a_one_day_event_that_starts_before_the_date_range(): void
    {
        $weeklyRecurrenceType = new Weekly();

        $recurringEvents = RecurringEventCollection::fromArray([
            new RecurringEventFake(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-06-01 00:00:00'),
                new DateTimeImmutable('2023-06-01 02:00:00'),
                $weeklyRecurrenceType->id(),
            ),
        ]);

        $fromDate = new DateTimeImmutable('2023-06-08 00:00:00');
        $toDate = new DateTimeImmutable('2023-06-29 23:59:59');

        $generatedWeeklyOccurrences = $weeklyRecurrenceType->occurrencesFor(
            $recurringEvents,
            $fromDate,
            $toDate,
        );

        $this->assertCount(
            4,
            $generatedWeeklyOccurrences,
        );

        $this->assertContainsEquals(
            EventOccurrence::create(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-06-08 00:00:00'),
                new DateTimeImmutable('2023-06-08 02:00:00'),
            ),
            $generatedWeeklyOccurrences,
        );

        $this->assertContainsEquals(
            EventOccurrence::create(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-06-29 00:00:00'),
                new DateTimeImmutable('2023-06-29 02:00:00'),
            ),
            $generatedWeeklyOccurrences,
        );
    }

    /**
     * @test
     */
    // phpcs:ignore Generic.Files.LineLength.TooLong
    public function it_should_generate_weekly_occurrences_for_a_one_day_event_that_starts_before_the_date_range_and_has_a_recurs_until_date(): void
    {
        $weeklyRecurrenceType = new Weekly();

        $recurringEvents = RecurringEventCollection::fromArray([
            new RecurringEventFake(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-06-01 00:00:00'),
                new DateTimeImmutable('2023-06-01 02:00:00'),
                $weeklyRecurrenceType->id(),
                new DateTimeImmutable('2023-06-15 23:59:59'),
            ),
        ]);

        $fromDate = new DateTimeImmutable('2023-06-08 00:00:00');
        $toDate = new DateTimeImmutable('2023-06-29 23:59:59');

        $generatedWeeklyOccurrences = $weeklyRecurrenceType->occurrencesFor(
            $recurringEvents,
            $fromDate,
            $toDate,
        );

        $this->assertCount(
            2,
            $generatedWeeklyOccurrences,
        );

        $this->assertContainsEquals(
            EventOccurrence::create(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-06-08 00:00:00'),
                new DateTimeImmutable('2023-06-08 02:00:00'),
            ),
            $generatedWeeklyOccurrences,
        );

        $this->assertContainsEquals(
            EventOccurrence::create(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-06-15 00:00:00'),
                new DateTimeImmutable('2023-06-15 02:00:00'),
            ),
            $generatedWeeklyOccurrences,
        );
    }

    /**
     * @test
     */
    // phpcs:ignore Generic.Files.LineLength.TooLong
    public function it_should_generate_weekly_occurrences_for_a_multiple_day_event_that_starts_before_the_date_range(): void
    {
        $weeklyRecurrenceType = new Weekly();

        $recurringEvents = RecurringEventCollection::fromArray([
            new RecurringEventFake(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-06-01 00:00:00'),
                new DateTimeImmutable('2023-06-03 02:00:00'),
                $weeklyRecurrenceType->id(),
            ),
        ]);

        $fromDate = new DateTimeImmutable('2023-06-08 00:00:00');
        $toDate = new DateTimeImmutable('2023-06-29 23:59:59');

        $generatedWeeklyOccurrences = $weeklyRecurrenceType->occurrencesFor(
            $recurringEvents,
            $fromDate,
            $toDate,
        );

        $this->assertCount(
            4,
            $generatedWeeklyOccurrences,
        );

        $this->assertContainsEquals(
            EventOccurrence::create(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-06-08 00:00:00'),
                new DateTimeImmutable('2023-06-10 02:00:00'),
            ),
            $generatedWeeklyOccurrences,
        );

        $this->assertContainsEquals(
            EventOccurrence::create(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-06-29 00:00:00'),
                new DateTimeImmutable('2023-07-01 02:00:00'),
            ),
            $generatedWeeklyOccurrences,
        );
    }

    /**
     * @test
     */
    // phpcs:ignore Generic.Files.LineLength.TooLong
    public function it_should_generate_weekly_occurrences_for_a_multiple_day_event_that_starts_before_the_date_range_and_has_a_recurs_until_date(): void
    {
        $weeklyRecurrenceType = new Weekly();

        $recurringEvents = RecurringEventCollection::fromArray([
            new RecurringEventFake(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-06-01 00:00:00'),
                new DateTimeImmutable('2023-06-03 02:00:00'),
                $weeklyRecurrenceType->id(),
                new DateTimeImmutable('2023-06-15 23:59:59'),
            ),
        ]);

        $fromDate = new DateTimeImmutable('2023-06-08 00:00:00');
        $toDate = new DateTimeImmutable('2023-06-29 23:59:59');

        $generatedWeeklyOccurrences = $weeklyRecurrenceType->occurrencesFor(
            $recurringEvents,
            $fromDate,
            $toDate,
        );

        $this->assertCount(
            2,
            $generatedWeeklyOccurrences,
        );

        $this->assertContainsEquals(
            EventOccurrence::create(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-06-08 00:00:00'),
                new DateTimeImmutable('2023-06-10 02:00:00'),
            ),
            $generatedWeeklyOccurrences,
        );

        $this->assertContainsEquals(
            EventOccurrence::create(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-06-15 00:00:00'),
                new DateTimeImmutable('2023-06-17 02:00:00'),
            ),
            $generatedWeeklyOccurrences,
        );
    }

    /**
     * @test
     */
    public function it_should_not_generate_occurrences_outside_of_the_date_period(): void
    {
        $weeklyRecurrenceType = new Weekly();

        $recurringEvents = RecurringEventCollection::fromArray([
            new RecurringEventFake(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-06-01 15:00:00'),
                new DateTimeImmutable('2023-06-01 17:00:00'),
                $weeklyRecurrenceType->id(),
            ),
        ]);

        $fromDate = new DateTimeImmutable('2023-06-01 18:00:00');
        $toDate = new DateTimeImmutable('2023-06-29 12:00:00');

        $generatedWeeklyOccurrences = $weeklyRecurrenceType->occurrencesFor(
            $recurringEvents,
            $fromDate,
            $toDate,
        );

        $this->assertCount(
            3,
            $generatedWeeklyOccurrences,
        );

        $this->assertNotContainsEquals(
            EventOccurrence::create(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-06-01 15:00:00'),
                new DateTimeImmutable('2023-06-01 17:00:00'),
            ),
            $generatedWeeklyOccurrences,
        );

        $this->assertNotContainsEquals(
            EventOccurrence::create(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-06-29 15:00:00'),
                new DateTimeImmutable('2023-06-29 17:00:00'),
            ),
            $generatedWeeklyOccurrences,
        );
    }

    /**
     * @test
     */
    public function it_should_not_generate_occurrences_beyond_the_recurs_until_limit(): void
    {
        $weeklyRecurrenceType = new Weekly();

        $recurringEvents = RecurringEventCollection::fromArray([
            new RecurringEventFake(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-06-01 00:00:00'),
                new DateTimeImmutable('2023-06-01 02:00:00'),
                $weeklyRecurrenceType->id(),
            ),
        ]);

        $fromDate = new DateTimeImmutable('2023-06-01 00:00:00');
        $toDate = new DateTimeImmutable('2029-06-01 23:59:59');

        $generatedWeeklyOccurrences = iterator_to_array(
            $weeklyRecurrenceType->occurrencesFor(
                $recurringEvents,
                $fromDate,
                $toDate,
            )
        );

        $lastOccurrence = end($generatedWeeklyOccurrences);

        $this->assertEquals(
            new DateTimeImmutable('2028-06-01 00:00:00'),
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
                    return $eventOccurrence->startDate() === new DateTimeImmutable('2023-06-01 00:00:00');
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

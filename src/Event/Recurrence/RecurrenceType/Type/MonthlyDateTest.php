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

class MonthlyDateTest extends TestCase
{
    /**
     * @test
     */
    // phpcs:ignore Generic.Files.LineLength.TooLong
    public function it_should_generate_monthly_date_occurrences_for_a_one_day_event_that_starts_within_the_date_range(): void
    {
        $monthlyDateRecurrenceType = new MonthlyDate();

        $recurringEvents = RecurringEventCollection::fromArray([
            new RecurringEventFake(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-01-31 00:00:00'),
                new DateTimeImmutable('2023-01-31 02:00:00'),
                $monthlyDateRecurrenceType->id(),
            ),
        ]);

        $fromDate = new DateTimeImmutable('2023-01-01 00:00:00');
        $toDate = new DateTimeImmutable('2023-12-31 23:59:59');

        $generatedMonthlyDateOccurrences = $monthlyDateRecurrenceType->occurrencesFor(
            $recurringEvents,
            $fromDate,
            $toDate,
        );

        $this->assertCount(
            7,
            $generatedMonthlyDateOccurrences,
        );

        $this->assertContainsEquals(
            new EventOccurrence(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-01-31 00:00:00'),
                new DateTimeImmutable('2023-01-31 02:00:00'),
            ),
            $generatedMonthlyDateOccurrences,
        );

        $this->assertContainsEquals(
            new EventOccurrence(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-12-31 00:00:00'),
                new DateTimeImmutable('2023-12-31 02:00:00'),
            ),
            $generatedMonthlyDateOccurrences,
        );
    }

    /**
     * @test
     */
    // phpcs:ignore Generic.Files.LineLength.TooLong
    public function it_should_generate_monthly_date_occurrences_for_a_one_day_event_that_starts_within_the_date_range_and_has_a_recurs_until_date(): void
    {
        $monthlyDateRecurrenceType = new MonthlyDate();

        $recurringEvents = RecurringEventCollection::fromArray([
            new RecurringEventFake(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-01-31 00:00:00'),
                new DateTimeImmutable('2023-01-31 02:00:00'),
                $monthlyDateRecurrenceType->id(),
                new DateTimeImmutable('2023-08-31 23:59:59'),
            ),
        ]);

        $fromDate = new DateTimeImmutable('2023-01-01 00:00:00');
        $toDate = new DateTimeImmutable('2023-12-31 23:59:59');

        $generatedMonthlyDateOccurrences = $monthlyDateRecurrenceType->occurrencesFor(
            $recurringEvents,
            $fromDate,
            $toDate,
        );

        $this->assertCount(
            5,
            $generatedMonthlyDateOccurrences,
        );

        $this->assertContainsEquals(
            new EventOccurrence(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-01-31 00:00:00'),
                new DateTimeImmutable('2023-01-31 02:00:00'),
            ),
            $generatedMonthlyDateOccurrences,
        );

        $this->assertContainsEquals(
            new EventOccurrence(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-08-31 00:00:00'),
                new DateTimeImmutable('2023-08-31 02:00:00'),
            ),
            $generatedMonthlyDateOccurrences,
        );
    }

    /**
     * @test
     */
    // phpcs:ignore Generic.Files.LineLength.TooLong
    public function it_should_generate_monthly_date_occurrences_for_a_multiple_day_event_that_starts_within_the_date_range(): void
    {
        $monthlyDateRecurrenceType = new MonthlyDate();

        $recurringEvents = RecurringEventCollection::fromArray([
            new RecurringEventFake(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-01-31 00:00:00'),
                new DateTimeImmutable('2023-02-02 02:00:00'),
                $monthlyDateRecurrenceType->id(),
            ),
        ]);

        $fromDate = new DateTimeImmutable('2023-01-01 00:00:00');
        $toDate = new DateTimeImmutable('2023-12-31 23:59:59');

        $generatedMonthlyDateOccurrences = $monthlyDateRecurrenceType->occurrencesFor(
            $recurringEvents,
            $fromDate,
            $toDate,
        );

        $this->assertCount(
            7,
            $generatedMonthlyDateOccurrences,
        );

        $this->assertContainsEquals(
            new EventOccurrence(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-01-31 00:00:00'),
                new DateTimeImmutable('2023-02-02 02:00:00'),
            ),
            $generatedMonthlyDateOccurrences,
        );

        $this->assertContainsEquals(
            new EventOccurrence(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-12-31 00:00:00'),
                new DateTimeImmutable('2024-01-02 02:00:00'),
            ),
            $generatedMonthlyDateOccurrences,
        );
    }

    /**
     * @test
     */
    // phpcs:ignore Generic.Files.LineLength.TooLong
    public function it_should_generate_monthly_date_occurrences_for_a_multiple_day_event_that_starts_within_the_date_range_and_has_a_recurs_until_date(): void
    {
        $monthlyDateRecurrenceType = new MonthlyDate();

        $recurringEvents = RecurringEventCollection::fromArray([
            new RecurringEventFake(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-01-31 00:00:00'),
                new DateTimeImmutable('2023-02-02 02:00:00'),
                $monthlyDateRecurrenceType->id(),
                new DateTimeImmutable('2023-08-31 23:59:59'),
            ),
        ]);

        $fromDate = new DateTimeImmutable('2023-01-01 00:00:00');
        $toDate = new DateTimeImmutable('2023-12-31 23:59:59');

        $generatedMonthlyDateOccurrences = $monthlyDateRecurrenceType->occurrencesFor(
            $recurringEvents,
            $fromDate,
            $toDate,
        );

        $this->assertCount(
            5,
            $generatedMonthlyDateOccurrences,
        );

        $this->assertContainsEquals(
            new EventOccurrence(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-01-31 00:00:00'),
                new DateTimeImmutable('2023-02-02 02:00:00'),
            ),
            $generatedMonthlyDateOccurrences,
        );

        $this->assertContainsEquals(
            new EventOccurrence(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-08-31 00:00:00'),
                new DateTimeImmutable('2023-09-02 02:00:00'),
            ),
            $generatedMonthlyDateOccurrences,
        );
    }

    /**
     * @test
     */
    // phpcs:ignore Generic.Files.LineLength.TooLong
    public function it_should_generate_monthly_date_occurrences_for_a_one_day_event_that_starts_before_the_date_range(): void
    {
        $monthlyDateRecurrenceType = new MonthlyDate();

        $recurringEvents = RecurringEventCollection::fromArray([
            new RecurringEventFake(
                new EventIdFake('1'),
                new DateTimeImmutable('2013-07-31 00:00:00'),
                new DateTimeImmutable('2013-07-31 02:00:00'),
                $monthlyDateRecurrenceType->id(),
            ),
        ]);

        $fromDate = new DateTimeImmutable('2023-01-01 00:00:00');
        $toDate = new DateTimeImmutable('2023-12-31 23:59:59');

        $generatedMonthlyDateOccurrences = $monthlyDateRecurrenceType->occurrencesFor(
            $recurringEvents,
            $fromDate,
            $toDate,
        );

        $this->assertCount(
            7,
            $generatedMonthlyDateOccurrences,
        );

        $this->assertContainsEquals(
            new EventOccurrence(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-01-31 00:00:00'),
                new DateTimeImmutable('2023-01-31 02:00:00'),
            ),
            $generatedMonthlyDateOccurrences,
        );

        $this->assertContainsEquals(
            new EventOccurrence(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-12-31 00:00:00'),
                new DateTimeImmutable('2023-12-31 02:00:00'),
            ),
            $generatedMonthlyDateOccurrences,
        );
    }

    /**
     * @test
     */
    // phpcs:ignore Generic.Files.LineLength.TooLong
    public function it_should_generate_monthly_date_occurrences_for_a_one_day_event_that_starts_before_the_date_range_and_has_a_recurs_until_date(): void
    {
        $monthlyDateRecurrenceType = new MonthlyDate();

        $recurringEvents = RecurringEventCollection::fromArray([
            new RecurringEventFake(
                new EventIdFake('1'),
                new DateTimeImmutable('2013-07-31 00:00:00'),
                new DateTimeImmutable('2013-07-31 02:00:00'),
                $monthlyDateRecurrenceType->id(),
                new DateTimeImmutable('2023-08-31 23:59:59'),
            ),
        ]);

        $fromDate = new DateTimeImmutable('2023-01-01 00:00:00');
        $toDate = new DateTimeImmutable('2023-12-31 23:59:59');

        $generatedMonthlyDateOccurrences = $monthlyDateRecurrenceType->occurrencesFor(
            $recurringEvents,
            $fromDate,
            $toDate,
        );

        $this->assertCount(
            5,
            $generatedMonthlyDateOccurrences,
        );

        $this->assertContainsEquals(
            new EventOccurrence(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-01-31 00:00:00'),
                new DateTimeImmutable('2023-01-31 02:00:00'),
            ),
            $generatedMonthlyDateOccurrences,
        );

        $this->assertContainsEquals(
            new EventOccurrence(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-08-31 00:00:00'),
                new DateTimeImmutable('2023-08-31 02:00:00'),
            ),
            $generatedMonthlyDateOccurrences,
        );
    }

    /**
     * @test
     */
    // phpcs:ignore Generic.Files.LineLength.TooLong
    public function it_should_generate_monthly_date_occurrences_for_a_multiple_day_event_that_starts_before_the_date_range(): void
    {
        $monthlyDateRecurrenceType = new MonthlyDate();

        $recurringEvents = RecurringEventCollection::fromArray([
            new RecurringEventFake(
                new EventIdFake('1'),
                new DateTimeImmutable('2013-07-31 00:00:00'),
                new DateTimeImmutable('2013-08-02 02:00:00'),
                $monthlyDateRecurrenceType->id(),
            ),
        ]);

        $fromDate = new DateTimeImmutable('2023-01-01 00:00:00');
        $toDate = new DateTimeImmutable('2023-12-31 23:59:59');

        $generatedMonthlyDateOccurrences = $monthlyDateRecurrenceType->occurrencesFor(
            $recurringEvents,
            $fromDate,
            $toDate,
        );

        $this->assertCount(
            7,
            $generatedMonthlyDateOccurrences,
        );

        $this->assertContainsEquals(
            new EventOccurrence(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-01-31 00:00:00'),
                new DateTimeImmutable('2023-02-02 02:00:00'),
            ),
            $generatedMonthlyDateOccurrences,
        );

        $this->assertContainsEquals(
            new EventOccurrence(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-12-31 00:00:00'),
                new DateTimeImmutable('2024-01-02 02:00:00'),
            ),
            $generatedMonthlyDateOccurrences,
        );
    }

    /**
     * @test
     */
    // phpcs:ignore Generic.Files.LineLength.TooLong
    public function it_should_generate_monthly_date_occurrences_for_a_multiple_day_event_that_starts_before_the_date_range_and_has_a_recurs_until_date(): void
    {
        $monthlyDateRecurrenceType = new MonthlyDate();

        $recurringEvents = RecurringEventCollection::fromArray([
            new RecurringEventFake(
                new EventIdFake('1'),
                new DateTimeImmutable('2013-07-31 00:00:00'),
                new DateTimeImmutable('2013-08-02 02:00:00'),
                $monthlyDateRecurrenceType->id(),
                new DateTimeImmutable('2023-08-31 23:59:59'),
            ),
        ]);

        $fromDate = new DateTimeImmutable('2023-01-01 00:00:00');
        $toDate = new DateTimeImmutable('2023-12-31 23:59:59');

        $generatedMonthlyDateOccurrences = $monthlyDateRecurrenceType->occurrencesFor(
            $recurringEvents,
            $fromDate,
            $toDate,
        );

        $this->assertCount(
            5,
            $generatedMonthlyDateOccurrences,
        );

        $this->assertContainsEquals(
            new EventOccurrence(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-01-31 00:00:00'),
                new DateTimeImmutable('2023-02-02 02:00:00'),
            ),
            $generatedMonthlyDateOccurrences,
        );

        $this->assertContainsEquals(
            new EventOccurrence(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-08-31 00:00:00'),
                new DateTimeImmutable('2023-09-02 02:00:00'),
            ),
            $generatedMonthlyDateOccurrences,
        );
    }

    /**
     * @test
     */
    // phpcs:ignore Generic.Files.LineLength.TooLong
    public function it_should_not_generate_occurrences_outside_of_the_date_period(): void
    {
        $monthlyDateRecurrenceType = new MonthlyDate();

        $recurringEvents = RecurringEventCollection::fromArray([
            new RecurringEventFake(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-01-01 15:00:00'),
                new DateTimeImmutable('2023-01-01 17:00:00'),
                $monthlyDateRecurrenceType->id(),
            ),
        ]);

        $fromDate = new DateTimeImmutable('2023-01-01 18:00:00');
        $toDate = new DateTimeImmutable('2023-12-01 12:00:00');

        $generatedMonthlyDateOccurrences = $monthlyDateRecurrenceType->occurrencesFor(
            $recurringEvents,
            $fromDate,
            $toDate,
        );

        $this->assertCount(
            10,
            $generatedMonthlyDateOccurrences,
        );

        $this->assertNotContainsEquals(
            new EventOccurrence(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-01-01 15:00:00'),
                new DateTimeImmutable('2023-01-01 17:00:00'),
            ),
            $generatedMonthlyDateOccurrences,
        );

        $this->assertNotContainsEquals(
            new EventOccurrence(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-12-01 15:00:00'),
                new DateTimeImmutable('2023-12-01 17:00:00'),
            ),
            $generatedMonthlyDateOccurrences,
        );
    }

    /**
     * @test
     */
    // phpcs:ignore Generic.Files.LineLength.TooLong
    public function it_should_not_generate_occurrences_beyond_the_recurs_until_limit(): void
    {
        $monthlyDateRecurrenceType = new MonthlyDate();

        $recurringEvents = RecurringEventCollection::fromArray([
            new RecurringEventFake(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-06-01 00:00:00'),
                new DateTimeImmutable('2023-06-01 01:00:00'),
                $monthlyDateRecurrenceType->id(),
            ),
        ]);

        $fromDate = new DateTimeImmutable('2023-06-01 00:00:00');
        $toDate = new DateTimeImmutable('2049-06-01 00:00:00');

        $generatedMonthlyDateOccurrences = iterator_to_array($monthlyDateRecurrenceType->occurrencesFor(
            $recurringEvents,
            $fromDate,
            $toDate,
        ));

        $lastOccurrence = end($generatedMonthlyDateOccurrences);

        $this->assertEquals(
            new DateTimeImmutable('2048-06-01 00:00:00'),
            $lastOccurrence ? $lastOccurrence->startDate() : false
        );
    }

    /**
     * @test
     */
    // phpcs:ignore Generic.Files.LineLength.TooLong
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
                    return $eventOccurrence->startDate() === new DateTimeImmutable('2023-06-01 02:00:00');
                },
            ),
        );
    }
}

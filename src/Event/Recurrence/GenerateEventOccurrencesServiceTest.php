<?php

declare(strict_types=1);

namespace Plummer\Calendarful\Event\Recurrence;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Plummer\Calendarful\Event\Recurrence\RecurrenceType\InMemory\InMemoryRecurrenceTypeRepository;
use Plummer\Calendarful\Event\Recurrence\RecurrenceType\Type\Daily;
use Plummer\Calendarful\Event\Recurrence\RecurrenceType\Type\MonthlyDate;
use Plummer\Calendarful\Event\Recurrence\RecurrenceType\Type\Weekly;
use Plummer\Calendarful\TestUtilities\EventIdFake;
use Plummer\Calendarful\TestUtilities\RecurringEventFake;

class GenerateEventOccurrencesServiceTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_generate_occurrences_for_the_given_recurring_events(): void
    {
        $dailyRecurrenceType = new Daily();
        $weeklyRecurrenceType = new Weekly();
        $monthlyDateRecurrenceType = new MonthlyDate();

        $recurrenceTypeRepository = new InMemoryRecurrenceTypeRepository();
        $recurrenceTypeRepository->add(
            $dailyRecurrenceType,
            $weeklyRecurrenceType,
            $monthlyDateRecurrenceType,
        );

        $dailyRecurringEventId1 = new EventIdFake('1');
        $dailyRecurringEventId2 = new EventIdFake('2');
        $weeklyRecurringEventId1 = new EventIdFake('3');
        $weeklyRecurringEventId2 = new EventIdFake('4');
        $monthlyDateRecurringEventId1 = new EventIdFake('5');
        $monthlyDateRecurringEventId2 = new EventIdFake('6');

        $recurringEvents = RecurringEventCollection::fromArray([
            new RecurringEventFake(
                $dailyRecurringEventId1,
                new DateTimeImmutable('2023-01-01 00:00:00'),
                new DateTimeImmutable('2023-01-01 02:00:00'),
                $dailyRecurrenceType->id(),
            ),
            new RecurringEventFake(
                $dailyRecurringEventId2,
                new DateTimeImmutable('2023-01-01 10:00:00'),
                new DateTimeImmutable('2023-01-01 12:00:00'),
                $dailyRecurrenceType->id(),
                new DateTimeImmutable('2023-01-15 23:59:59'),
            ),
            new RecurringEventFake(
                $weeklyRecurringEventId1,
                new DateTimeImmutable('2023-01-01 00:00:00'),
                new DateTimeImmutable('2023-01-01 02:00:00'),
                $weeklyRecurrenceType->id(),
            ),
            new RecurringEventFake(
                $weeklyRecurringEventId2,
                new DateTimeImmutable('2023-01-01 12:00:00'),
                new DateTimeImmutable('2023-01-01 14:00:00'),
                $weeklyRecurrenceType->id(),
                new DateTimeImmutable('2023-01-31 23:59:59'),
            ),
            new RecurringEventFake(
                $monthlyDateRecurringEventId1,
                new DateTimeImmutable('2023-01-30 17:30:00'),
                new DateTimeImmutable('2023-01-30 20:30:00'),
                $monthlyDateRecurrenceType->id(),
            ),
            new RecurringEventFake(
                $monthlyDateRecurringEventId2,
                new DateTimeImmutable('2023-01-30 20:15:00'),
                new DateTimeImmutable('2023-01-30 21:00:00'),
                $monthlyDateRecurrenceType->id(),
                new DateTimeImmutable('2023-02-15 23:59:59'),
            ),
        ]);

        $fromDate = new \DateTimeImmutable('2023-01-01 00:00:00');
        $toDate = new \DateTimeImmutable('2023-03-31 23:59:59');

        $generateEventOccurrencesService = new GenerateEventOccurrencesService(
            $recurrenceTypeRepository,
        );

        $generatedOccurrences = $generateEventOccurrencesService->execute(
            $recurringEvents,
            $fromDate,
            $toDate,
        );

        $this->assertCount(
            126,
            $generatedOccurrences,
        );

        $this->assertCount(90, $generatedOccurrences->filter(
            function (EventOccurrenceInterface $eventOccurrence) use ($dailyRecurringEventId1) {
                return $eventOccurrence->id()->equals($dailyRecurringEventId1);
            },
        ));

        $this->assertCount(15, $generatedOccurrences->filter(
            function (EventOccurrenceInterface $eventOccurrence) use ($dailyRecurringEventId2) {
                return $eventOccurrence->id()->equals($dailyRecurringEventId2);
            },
        ));

        $this->assertCount(13, $generatedOccurrences->filter(
            function (EventOccurrenceInterface $eventOccurrence) use ($weeklyRecurringEventId1) {
                return $eventOccurrence->id()->equals($weeklyRecurringEventId1);
            },
        ));

        $this->assertCount(5, $generatedOccurrences->filter(
            function (EventOccurrenceInterface $eventOccurrence) use ($weeklyRecurringEventId2) {
                return $eventOccurrence->id()->equals($weeklyRecurringEventId2);
            },
        ));

        $this->assertCount(2, $generatedOccurrences->filter(
            function (EventOccurrenceInterface $eventOccurrence) use ($monthlyDateRecurringEventId1) {
                return $eventOccurrence->id()->equals($monthlyDateRecurringEventId1);
            },
        ));

        $this->assertCount(1, $generatedOccurrences->filter(
            function (EventOccurrenceInterface $eventOccurrence) use ($monthlyDateRecurringEventId2) {
                return $eventOccurrence->id()->equals($monthlyDateRecurringEventId2);
            },
        ));
    }
}

<?php

declare(strict_types=1);

namespace Plummer\Calendarful\Event\Recurrence\EventOccurrence;

use DateInterval;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Plummer\Calendarful\TestUtilities\EventIdFake;

class EventOccurrenceTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_allow_its_properties_to_be_accessed(): void
    {
        $eventId = new EventIdFake('1');
        $startDate = new DateTimeImmutable('2023-01-01 09:00:00');
        $endDate = new DateTimeImmutable('2023-01-01 11:30:00');

        $eventOccurrence = EventOccurrence::create(
            $eventId,
            $startDate,
            $endDate,
        );

        $this->assertEquals(
            $eventId,
            $eventOccurrence->id(),
        );

        $this->assertEquals(
            $startDate,
            $eventOccurrence->startDate(),
        );

        $this->assertEquals(
            $endDate,
            $eventOccurrence->endDate(),
        );
    }

    /**
     * @test
     */
    public function it_should_calculate_the_duration_correctly(): void
    {
        $eventOccurrence = EventOccurrence::create(
            new EventIdFake('1'),
            new DateTimeImmutable('2023-01-01 09:00:00'),
            new DateTimeImmutable('2023-01-01 11:30:00'),
        );

        $duration = DateInterval::createFromDateString('2 hours + 30 minutes');

        $this->assertEquals(
            $duration->format('d'),
            $eventOccurrence->duration()->format('d'),
        );

        $this->assertEquals(
            $duration->format('H'),
            $eventOccurrence->duration()->format('H'),
        );

        $this->assertEquals(
            $duration->format('i'),
            $eventOccurrence->duration()->format('i'),
        );
    }
}

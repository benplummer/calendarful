<?php

declare(strict_types=1);

namespace Plummer\Calendarful\Event\Recurrence\EventOccurrence;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Plummer\Calendarful\TestUtilities\EventIdFake;

class EventOccurrenceFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_create_an_event_occurrence(): void
    {
        $eventId = new EventIdFake('1');
        $startDate = new DateTimeImmutable('2023-06-15 10:00:00');
        $endDate = new DateTimeImmutable('2022-06-15 12:00:00');

        $eventOccurrenceFactory = new EventOccurrenceFactory();

        $this->assertEquals(
            new EventOccurrence(
                $eventId,
                $startDate,
                $endDate,
            ),
            $eventOccurrenceFactory->createEventOccurrence(
                $eventId,
                $startDate,
                $endDate,
            ),
        );
    }
}

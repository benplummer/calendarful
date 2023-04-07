<?php

declare(strict_types=1);

namespace Plummer\Calendarful\Event;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Plummer\Calendarful\TestUtilities\EventFake;
use Plummer\Calendarful\TestUtilities\EventIdFake;

abstract class EventRepositoryTestCase extends TestCase
{
    abstract protected function createEventRepository(): EventRepositoryInterface;

    /**
     * @test
     */
    public function it_should_fetch_events_for_the_given_date_range(): void
    {
        $eventRepository = $this->createEventRepository();
        $eventRepository->add(
            new EventFake(
                new EventIdFake("1"),
                new DateTimeImmutable('2023-05-15 10:00:00'),
                new DateTimeImmutable('2023-05-15 12:00:00'),
            ),
            new EventFake(
                new EventIdFake("2"),
                new DateTimeImmutable('2023-05-31 09:00:00'),
                new DateTimeImmutable('2023-06-02 18:00:00'),
            ),
            new EventFake(
                new EventIdFake("3"),
                new DateTimeImmutable('2023-05-31 13:30:00'),
                new DateTimeImmutable('2023-05-31 17:00:00'),
            ),
            new EventFake(
                new EventIdFake("4"),
                new DateTimeImmutable('2023-06-05 19:30:00'),
                new DateTimeImmutable('2023-06-06 04:00:00'),
            ),
            new EventFake(
                new EventIdFake("5"),
                new DateTimeImmutable('2023-06-30 12:00:00'),
                new DateTimeImmutable('2023-07-01 20:00:00'),
            ),
            new EventFake(
                new EventIdFake("6"),
                new DateTimeImmutable('2023-07-01 08:30:00'),
                new DateTimeImmutable('2023-07-01 18:00:00'),
            ),
        );

        $fromDate = new DateTimeImmutable('2023-06-01 00:00:00');
        $toDate = new DateTimeImmutable('2023-06-30 23:59:59');

        $events = $eventRepository->withinDateRange(
            $fromDate,
            $toDate,
        );

        $this->assertCount(
            3,
            $events,
        );
    }
}

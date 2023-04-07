<?php

declare(strict_types=1);

namespace Plummer\Calendarful\Event;

use DateTimeImmutable;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Plummer\Calendarful\TestUtilities\EventFake;
use Plummer\Calendarful\TestUtilities\EventIdFake;

class EventCollectionTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_allow_creation_from_an_array_of_events(): void
    {
        $event1 = new EventFake(
            new EventIdFake('1'),
            new DateTimeImmutable('2023-06-15 10:00:00'),
            new DateTimeImmutable('2022-06-15 12:00:00'),
        );

        $event2 = new EventFake(
            new EventIdFake('2'),
            new DateTimeImmutable('2023-06-20 13:00:00'),
            new DateTimeImmutable('2023-06-20 17:00:00'),
        );

        $event3 = new EventFake(
            new EventIdFake('3'),
            new DateTimeImmutable('2023-07-01 07:00:00'),
            new DateTimeImmutable('2023-07-01 08:30:00'),
        );

        $events = [
            0 => $event1,
            'one' => $event2,
            $event3,
        ];

        /**
         * @psalm-suppress InvalidArgument
         * @phpstan-ignore-next-line
         */
        $eventCollection = EventCollection::fromArray($events);

        $this->assertCount(
            3,
            $eventCollection,
        );

        $this->assertEquals(
            [
                0 => $event1,
                1 => $event2,
                2 => $event3,
            ],
            iterator_to_array($eventCollection),
        );
    }

    /**
     * @test
     */
    public function it_should_allow_creation_from_an_empty_array(): void
    {
        $eventCollection = EventCollection::fromArray(
            [],
        );

        $this->assertCount(
            0,
            $eventCollection,
        );

        $this->assertEquals(
            [],
            iterator_to_array($eventCollection),
        );
    }

    /**
     * @test
     */
    // phpcs:ignore Generic.Files.LineLength.TooLong
    public function it_should_throw_an_exception_if_creation_is_attempted_with_an_array_containing_any_non_events(): void
    {
        $this->expectException(InvalidArgumentException::class);

        /**
         * @psalm-suppress InvalidArgument
         * @phpstan-ignore-next-line
         */
        EventCollection::fromArray([
            new EventFake(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-06-15 10:00:00'),
                new DateTimeImmutable('2022-06-15 12:00:00'),
            ),
            "Not An Event",
            123,
        ]);
    }

    /**
     * @test
     */
    public function it_should_return_a_count_of_the_contained_events(): void
    {
        $eventCollection = EventCollection::fromArray([
            new EventFake(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-06-15 10:00:00'),
                new DateTimeImmutable('2022-06-15 12:00:00'),
            ),
            new EventFake(
                new EventIdFake('2'),
                new DateTimeImmutable('2023-06-20 13:00:00'),
                new DateTimeImmutable('2023-06-20 17:00:00'),
            ),
            new EventFake(
                new EventIdFake('3'),
                new DateTimeImmutable('2023-07-01 07:00:00'),
                new DateTimeImmutable('2023-07-01 08:30:00'),
            ),
        ]);

        $this->assertEquals(
            3,
            $eventCollection->count(),
        );
    }

    /**
     * @test
     */
    public function it_should_be_iterable(): void
    {
        $events = [
            new EventFake(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-06-15 10:00:00'),
                new DateTimeImmutable('2022-06-15 12:00:00'),
            ),
            new EventFake(
                new EventIdFake('2'),
                new DateTimeImmutable('2023-06-20 13:00:00'),
                new DateTimeImmutable('2023-06-20 17:00:00'),
            ),
            new EventFake(
                new EventIdFake('3'),
                new DateTimeImmutable('2023-07-01 07:00:00'),
                new DateTimeImmutable('2023-07-01 08:30:00'),
            ),
        ];

        $eventCollection = EventCollection::fromArray(
            $events,
        );

        foreach ($eventCollection as $key => $event) {
            $this->assertEquals(
                $events[$key],
                $event,
            );
        }
    }
}

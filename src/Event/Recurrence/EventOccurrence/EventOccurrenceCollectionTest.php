<?php

declare(strict_types=1);

namespace Plummer\Calendarful\Event\Recurrence\EventOccurrence;

use DateTimeImmutable;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Plummer\Calendarful\TestUtilities\EventIdFake;

class EventOccurrenceCollectionTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_allow_creation_from_an_array_of_event_occurrences(): void
    {
        $eventOccurrence1 = new EventOccurrence(
            new EventIdFake('1'),
            new DateTimeImmutable('2023-06-15 10:00:00'),
            new DateTimeImmutable('2022-06-15 12:00:00'),
        );

        $eventOccurrence2 = new EventOccurrence(
            new EventIdFake('2'),
            new DateTimeImmutable('2023-06-20 13:00:00'),
            new DateTimeImmutable('2023-06-20 17:00:00'),
        );

        $eventOccurrence3 = new EventOccurrence(
            new EventIdFake('3'),
            new DateTimeImmutable('2023-07-01 07:00:00'),
            new DateTimeImmutable('2023-07-01 08:30:00'),
        );

        $eventOccurrences = [
            0 => $eventOccurrence1,
            'one' => $eventOccurrence2,
            $eventOccurrence3,
        ];

        /**
         * @psalm-suppress InvalidArgument
         * @phpstan-ignore-next-line
         */
        $eventOccurrenceCollection = EventOccurrenceCollection::fromArray($eventOccurrences);

        $this->assertCount(
            3,
            $eventOccurrenceCollection
        );

        $this->assertEquals(
            [
                0 => $eventOccurrence1,
                1 => $eventOccurrence2,
                2 => $eventOccurrence3,
            ],
            iterator_to_array($eventOccurrenceCollection),
        );
    }

    /**
     * @test
     */
    public function it_should_allow_creation_from_an_empty_array(): void
    {
        $eventOccurrenceCollection = EventOccurrenceCollection::fromArray(
            [],
        );

        $this->assertCount(
            0,
            $eventOccurrenceCollection,
        );

        $this->assertEquals(
            [],
            iterator_to_array($eventOccurrenceCollection),
        );
    }

    /**
     * @test
     */
    // phpcs:ignore Generic.Files.LineLength.TooLong
    public function it_should_throw_an_exception_if_creation_is_attempted_with_an_array_containing_any_non_event_occurrences(): void
    {
        $this->expectException(InvalidArgumentException::class);

        /**
         * @psalm-suppress InvalidArgument
         * @phpstan-ignore-next-line
         */
        EventOccurrenceCollection::fromArray([
            new EventOccurrence(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-06-15 10:00:00'),
                new DateTimeImmutable('2022-06-15 12:00:00'),
            ),
            "Not An Event Occurrence",
            123,
        ]);
    }

    /**
     * @test
     */
    public function it_should_return_a_count_of_the_contained_events(): void
    {
        $eventOccurrenceCollection = EventOccurrenceCollection::fromArray([
            new EventOccurrence(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-06-15 10:00:00'),
                new DateTimeImmutable('2022-06-15 12:00:00'),
            ),
            new EventOccurrence(
                new EventIdFake('2'),
                new DateTimeImmutable('2023-06-20 13:00:00'),
                new DateTimeImmutable('2023-06-20 17:00:00'),
            ),
            new EventOccurrence(
                new EventIdFake('3'),
                new DateTimeImmutable('2023-07-01 07:00:00'),
                new DateTimeImmutable('2023-07-01 08:30:00'),
            ),
        ]);

        $this->assertEquals(
            3,
            $eventOccurrenceCollection->count(),
        );
    }

    /**
     * @test
     */
    public function it_should_be_iterable(): void
    {
        $eventOccurrences = [
            new EventOccurrence(
                new EventIdFake('1'),
                new DateTimeImmutable('2023-06-15 10:00:00'),
                new DateTimeImmutable('2022-06-15 12:00:00'),
            ),
            new EventOccurrence(
                new EventIdFake('2'),
                new DateTimeImmutable('2023-06-20 13:00:00'),
                new DateTimeImmutable('2023-06-20 17:00:00'),
            ),
            new EventOccurrence(
                new EventIdFake('3'),
                new DateTimeImmutable('2023-07-01 07:00:00'),
                new DateTimeImmutable('2023-07-01 08:30:00'),
            ),
        ];

        $eventOccurrenceCollection = EventOccurrenceCollection::fromArray(
            $eventOccurrences,
        );

        foreach ($eventOccurrenceCollection as $key => $eventOccurrence) {
            $this->assertEquals(
                $eventOccurrences[$key],
                $eventOccurrence,
            );
        }
    }

    /**
     * @test
     */
    public function it_should_be_filterable(): void
    {
        $eventOccurrence1 = new EventOccurrence(
            new EventIdFake('1'),
            new DateTimeImmutable('2023-06-15 10:00:00'),
            new DateTimeImmutable('2022-06-15 12:00:00'),
        );

        $eventOccurrence2 = new EventOccurrence(
            new EventIdFake('2'),
            new DateTimeImmutable('2023-06-20 13:00:00'),
            new DateTimeImmutable('2023-06-20 17:00:00'),
        );

        $eventOccurrence3 = new EventOccurrence(
            new EventIdFake('3'),
            new DateTimeImmutable('2023-07-01 07:00:00'),
            new DateTimeImmutable('2023-07-01 08:30:00'),
        );

        $eventOccurrenceCollection = EventOccurrenceCollection::fromArray(
            [
                $eventOccurrence1,
                $eventOccurrence2,
                $eventOccurrence3,
            ],
        );

        $this->assertCount(
            3,
            $eventOccurrenceCollection,
        );

        $filteredEventOccurrenceCollection = $eventOccurrenceCollection->filter(
            function (EventOccurrenceInterface $eventOccurrence): bool {
                return $eventOccurrence->id()->equals(
                    new EventIdFake('1')
                );
            },
        );

        $this->assertCount(
            1,
            $filteredEventOccurrenceCollection,
        );

        $this->assertEquals(
            [
                $eventOccurrence1,
            ],
            iterator_to_array(
                $filteredEventOccurrenceCollection,
            ),
        );
    }

    /**
     * @test
     */
    public function it_should_be_able_to_be_merged_with_another_event_occurrences_collection(): void
    {
        $eventOccurrence1 = new EventOccurrence(
            new EventIdFake('1'),
            new DateTimeImmutable('2023-06-15 10:00:00'),
            new DateTimeImmutable('2022-06-15 12:00:00'),
        );

        $eventOccurrence2 = new EventOccurrence(
            new EventIdFake('2'),
            new DateTimeImmutable('2023-06-20 13:00:00'),
            new DateTimeImmutable('2023-06-20 17:00:00'),
        );

        $eventOccurrence3 = new EventOccurrence(
            new EventIdFake('3'),
            new DateTimeImmutable('2023-07-01 07:00:00'),
            new DateTimeImmutable('2023-07-01 08:30:00'),
        );

        $eventOccurrenceCollection1 = EventOccurrenceCollection::fromArray(
            [
                $eventOccurrence1,
                $eventOccurrence2,
            ],
        );

        $eventOccurrenceCollection2 = EventOccurrenceCollection::fromArray(
            [
                $eventOccurrence3,
            ],
        );

        $mergedEventOccurrenceCollection = $eventOccurrenceCollection1->merge($eventOccurrenceCollection2);

        $this->assertEquals(
            [
                $eventOccurrence1,
                $eventOccurrence2,
                $eventOccurrence3,
            ],
            iterator_to_array(
                $mergedEventOccurrenceCollection,
            ),
        );
    }
}

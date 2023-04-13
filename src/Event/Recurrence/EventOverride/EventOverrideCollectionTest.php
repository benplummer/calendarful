<?php

declare(strict_types=1);

namespace Plummer\Calendarful\Event\Recurrence\EventOverride;

use DateTimeImmutable;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Plummer\Calendarful\TestUtilities\EventIdFake;
use Plummer\Calendarful\TestUtilities\EventOverrideFake;

class EventOverrideCollectionTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_allow_creation_from_an_array_of_event_overrides(): void
    {
        $eventOverride1 = new EventOverrideFake(
            new EventIdFake('4'),
            new DateTimeImmutable('2023-06-15 10:30:00'),
            new DateTimeImmutable('2023-06-15 12:30:00'),
            new EventIdFake('1'),
            new DateTimeImmutable('2023-06-15 10:00:00'),
        );

        $eventOverride2 = new EventOverrideFake(
            new EventIdFake('5'),
            new DateTimeImmutable('2023-06-21 13:00:00'),
            new DateTimeImmutable('2023-06-21 17:00:00'),
            new EventIdFake('2'),
            new DateTimeImmutable('2023-06-20 13:00:00'),
        );

        $eventOverride3 = new EventOverrideFake(
            new EventIdFake('6'),
            new DateTimeImmutable('2023-07-01 06:00:00'),
            new DateTimeImmutable('2023-07-01 07:30:00'),
            new EventIdFake('3'),
            new DateTimeImmutable('2023-07-01 07:00:00'),
        );

        $eventOverrides = [
            0 => $eventOverride1,
            'one' => $eventOverride2,
            $eventOverride3,
        ];

        /**
         * @psalm-suppress InvalidArgument
         * @phpstan-ignore-next-line
         */
        $eventOverrideCollection = EventOverrideCollection::fromArray($eventOverrides);

        $this->assertCount(
            3,
            $eventOverrideCollection
        );

        $this->assertEquals(
            [
                0 => $eventOverride1,
                1 => $eventOverride2,
                2 => $eventOverride3,
            ],
            iterator_to_array($eventOverrideCollection),
        );
    }

    /**
     * @test
     */
    public function it_should_allow_creation_from_an_empty_array(): void
    {
        $eventOverrideCollection = EventOverrideCollection::fromArray(
            [],
        );

        $this->assertCount(
            0,
            $eventOverrideCollection,
        );

        $this->assertEquals(
            [],
            iterator_to_array($eventOverrideCollection),
        );
    }

    /**
     * @test
     */
    // phpcs:ignore Generic.Files.LineLength.TooLong
    public function it_should_throw_an_exception_if_creation_is_attempted_with_an_array_containing_any_non_event_overrides(): void
    {
        $this->expectException(InvalidArgumentException::class);

        /**
         * @psalm-suppress InvalidArgument
         * @phpstan-ignore-next-line
         */
        EventOverrideCollection::fromArray([
            new EventOverrideFake(
                new EventIdFake('4'),
                new DateTimeImmutable('2023-06-15 10:30:00'),
                new DateTimeImmutable('2023-06-15 12:30:00'),
                new EventIdFake('1'),
                new DateTimeImmutable('2023-06-15 10:00:00'),
            ),
            "Not An Event Override",
            123,
        ]);
    }

    /**
     * @test
     */
    public function it_should_return_a_count_of_the_contained_event_overrides(): void
    {
        $eventOverrideCollection = EventOverrideCollection::fromArray([
            new EventOverrideFake(
                new EventIdFake('4'),
                new DateTimeImmutable('2023-06-15 10:30:00'),
                new DateTimeImmutable('2023-06-15 12:30:00'),
                new EventIdFake('1'),
                new DateTimeImmutable('2023-06-15 10:00:00'),
            ),
            new EventOverrideFake(
                new EventIdFake('5'),
                new DateTimeImmutable('2023-06-21 13:00:00'),
                new DateTimeImmutable('2023-06-21 17:00:00'),
                new EventIdFake('2'),
                new DateTimeImmutable('2023-06-20 13:00:00'),
            ),
            new EventOverrideFake(
                new EventIdFake('6'),
                new DateTimeImmutable('2023-07-01 06:00:00'),
                new DateTimeImmutable('2023-07-01 07:30:00'),
                new EventIdFake('3'),
                new DateTimeImmutable('2023-07-01 07:00:00'),
            ),
        ]);

        $this->assertEquals(
            3,
            $eventOverrideCollection->count(),
        );
    }

    /**
     * @test
     */
    public function it_should_be_iterable(): void
    {
        $eventOverrides = [
            new EventOverrideFake(
                new EventIdFake('4'),
                new DateTimeImmutable('2023-06-15 10:30:00'),
                new DateTimeImmutable('2023-06-15 12:30:00'),
                new EventIdFake('1'),
                new DateTimeImmutable('2023-06-15 10:00:00'),
            ),
            new EventOverrideFake(
                new EventIdFake('5'),
                new DateTimeImmutable('2023-06-21 13:00:00'),
                new DateTimeImmutable('2023-06-21 17:00:00'),
                new EventIdFake('2'),
                new DateTimeImmutable('2023-06-20 13:00:00'),
            ),
            new EventOverrideFake(
                new EventIdFake('6'),
                new DateTimeImmutable('2023-07-01 06:00:00'),
                new DateTimeImmutable('2023-07-01 07:30:00'),
                new EventIdFake('3'),
                new DateTimeImmutable('2023-07-01 07:00:00'),
            ),
        ];

        $eventOverrideCollection = EventOverrideCollection::fromArray(
            $eventOverrides,
        );

        foreach ($eventOverrideCollection as $key => $eventOverride) {
            $this->assertEquals(
                $eventOverrides[$key],
                $eventOverride,
            );
        }
    }
}

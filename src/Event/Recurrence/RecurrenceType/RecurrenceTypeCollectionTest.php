<?php

declare(strict_types=1);

namespace Plummer\Calendarful\Event\Recurrence;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Plummer\Calendarful\Event\Recurrence\RecurrenceType\RecurrenceTypeCollection;
use Plummer\Calendarful\Event\Recurrence\RecurrenceType\Type\Daily;
use Plummer\Calendarful\Event\Recurrence\RecurrenceType\Type\MonthlyDate;
use Plummer\Calendarful\Event\Recurrence\RecurrenceType\Type\Weekly;

class RecurrenceTypeCollectionTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_allow_creation_from_an_array_of_recurrence_types(): void
    {
        $dailyRecurrenceType = new Daily();
        $weeklyRecurrenceType = new Weekly();
        $monthlyDateRecurrenceType = new MonthlyDate();

        $recurrenceTypes = [
            0 => $dailyRecurrenceType,
            'one' => $weeklyRecurrenceType,
            $monthlyDateRecurrenceType,
        ];

        /**
         * @psalm-suppress InvalidArgument
         * @phpstan-ignore-next-line
         */
        $recurrenceTypeCollection = RecurrenceTypeCollection::fromArray($recurrenceTypes);

        $this->assertCount(
            3,
            $recurrenceTypeCollection,
        );

        $this->assertEquals(
            [
                0 => $dailyRecurrenceType,
                1 => $weeklyRecurrenceType,
                2 => $monthlyDateRecurrenceType,
            ],
            iterator_to_array($recurrenceTypeCollection),
        );
    }

    /**
     * @test
     */
    public function it_should_allow_creation_from_an_empty_array(): void
    {
        $recurrenceTypeCollection = RecurrenceTypeCollection::fromArray(
            [],
        );

        $this->assertCount(
            0,
            $recurrenceTypeCollection,
        );

        $this->assertEquals(
            [],
            iterator_to_array($recurrenceTypeCollection),
        );
    }

    /**
     * @test
     */
    // phpcs:ignore Generic.Files.LineLength.TooLong
    public function it_should_throw_an_exception_if_creation_is_attempted_with_an_array_containing_any_non_recurrence_types(): void
    {
        $this->expectException(InvalidArgumentException::class);

        /**
         * @psalm-suppress InvalidArgument
         * @phpstan-ignore-next-line
         */
        RecurrenceTypeCollection::fromArray([
            new Daily(),
            "Not An Event Occurrence",
            123,
        ]);
    }

    /**
     * @test
     */
    public function it_should_return_a_count_of_the_contained_events(): void
    {
        $recurrenceTypeCollection = RecurrenceTypeCollection::fromArray(
            [
                new Daily(),
                new Weekly(),
                new MonthlyDate(),
            ]
        );

        $this->assertEquals(
            3,
            $recurrenceTypeCollection->count(),
        );
    }

    /**
     * @test
     */
    public function it_should_be_iterable(): void
    {
        $recurrenceTypes = [
            new Daily(),
            new Weekly(),
            new MonthlyDate(),
        ];

        $recurrenceTypeCollection = RecurrenceTypeCollection::fromArray(
            $recurrenceTypes,
        );

        foreach ($recurrenceTypeCollection as $key => $recurrenceType) {
            $this->assertEquals(
                $recurrenceTypes[$key],
                $recurrenceType,
            );
        }
    }
}

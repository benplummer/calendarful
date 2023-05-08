<?php

declare(strict_types=1);

namespace Plummer\Calendarful\Event\Recurrence\EventOverride;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Plummer\Calendarful\TestUtilities\EventIdFake;
use Plummer\Calendarful\TestUtilities\EventOverrideFake;

abstract class EventOverrideRepositoryTestCase extends TestCase
{
    abstract protected function createEventOverrideRepository(): EventOverrideRepositoryInterface;

    /**
     * @test
     */
    public function it_should_fetch_event_overrides_for_the_given_date_range(): void
    {
        $eventOverrideRepository = $this->createEventOverrideRepository();
        $eventOverrideRepository->add(
            new EventOverrideFake(
                new EventIdFake("1"),
                new DateTimeImmutable('2023-05-13 11:00:00'),
                new DateTimeImmutable('2023-05-13 14:00:00'),
                new EventIdFake("2"),
                new DateTimeImmutable('2023-05-13 11:30:00'),
            ),
            new EventOverrideFake(
                new EventIdFake("2"),
                new DateTimeImmutable('2023-05-31 09:00:00'),
                new DateTimeImmutable('2023-06-02 18:00:00'),
                new EventIdFake("1"),
                new DateTimeImmutable('2023-05-31 10:00:00'),
            ),
            new EventOverrideFake(
                new EventIdFake("3"),
                new DateTimeImmutable('2023-05-31 13:30:00'),
                new DateTimeImmutable('2023-05-31 17:00:00'),
                new EventIdFake("6"),
                new DateTimeImmutable('2023-05-31 23:00:00'),
            ),
            new EventOverrideFake(
                new EventIdFake("4"),
                new DateTimeImmutable('2023-06-05 19:30:00'),
                new DateTimeImmutable('2023-06-06 04:00:00'),
                new EventIdFake("7"),
                new DateTimeImmutable('2023-06-05 00:00:00'),
            ),
            new EventOverrideFake(
                new EventIdFake("5"),
                new DateTimeImmutable('2023-06-30 12:00:00'),
                new DateTimeImmutable('2023-07-01 20:00:00'),
                new EventIdFake("7"),
                new DateTimeImmutable('2023-06-30 00:00:00'),
            ),
            new EventOverrideFake(
                new EventIdFake("6"),
                new DateTimeImmutable('2023-07-01 08:30:00'),
                new DateTimeImmutable('2023-07-01 18:00:00'),
                new EventIdFake("9"),
                new DateTimeImmutable('2023-06-30 23:00:00'),
            ),
        );

        $fromDate = new DateTimeImmutable('2023-06-01 00:00:00');
        $toDate = new DateTimeImmutable('2023-06-30 23:59:59');

        $eventOverrides = $eventOverrideRepository->withinDateRange(
            $fromDate,
            $toDate,
        );

        $this->assertCount(
            3,
            $eventOverrides,
        );
    }
    
    /**
     * @test
     */
    public function it_should_fetch_the_next_identity_to_be_used(): void
    {
        $eventOverrideRepository = $this->createEventOverrideRepository();
        $eventOverrideRepository->add(
            new EventOverrideFake(
                new EventIdFake("1"),
                new DateTimeImmutable('2023-05-13 11:00:00'),
                new DateTimeImmutable('2023-05-13 14:00:00'),
                new EventIdFake("2"),
                new DateTimeImmutable('2023-05-13 11:30:00'),
            ),
            new EventOverrideFake(
                new EventIdFake("2"),
                new DateTimeImmutable('2023-05-31 09:00:00'),
                new DateTimeImmutable('2023-06-02 18:00:00'),
                new EventIdFake("1"),
                new DateTimeImmutable('2023-05-31 10:00:00'),
            ),
            new EventOverrideFake(
                new EventIdFake("3"),
                new DateTimeImmutable('2023-05-31 13:30:00'),
                new DateTimeImmutable('2023-05-31 17:00:00'),
                new EventIdFake("6"),
                new DateTimeImmutable('2023-05-31 23:00:00'),
            ),
        );

        $this->assertTrue(
            (new EventIdFake('4'))->equals(
                $eventOverrideRepository->nextIdentity(),
            ),
        );
    }
}

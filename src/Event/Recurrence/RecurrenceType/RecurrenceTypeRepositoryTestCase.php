<?php

declare(strict_types=1);

namespace Plummer\Calendarful\Event\Recurrence\RecurrenceType;

use PHPUnit\Framework\TestCase;
use Plummer\Calendarful\Event\Recurrence\RecurrenceType\RecurrenceTypeRepositoryInterface;
use Plummer\Calendarful\Event\Recurrence\RecurrenceType\Type\Daily;
use Plummer\Calendarful\Event\Recurrence\RecurrenceType\Type\MonthlyDate;
use Plummer\Calendarful\Event\Recurrence\RecurrenceType\Type\Weekly;

abstract class RecurrenceTypeRepositoryTestCase extends TestCase
{
    abstract protected function createRecurrenceTypeRepository(): RecurrenceTypeRepositoryInterface;

    /**
     * @test
     */
    public function it_should_fetch_all_recurrence_types(): void
    {
        $recurrenceTypeRepository = $this->createRecurrenceTypeRepository();
        $dailyRecurrenceType = new Daily();
        $weeklyRecurrenceType = new Weekly();
        $monthlyDateRecurrenceType = new MonthlyDate();

        $recurrenceTypeRepository->add(
            $dailyRecurrenceType,
            $weeklyRecurrenceType,
            $monthlyDateRecurrenceType,
        );

        $recurrenceTypes = $recurrenceTypeRepository->all();

        $this->assertCount(
            3,
            $recurrenceTypes,
        );

        $this->assertContains(
            $dailyRecurrenceType,
            $recurrenceTypes,
        );

        $this->assertContains(
            $weeklyRecurrenceType,
            $recurrenceTypes,
        );

        $this->assertContains(
            $monthlyDateRecurrenceType,
            $recurrenceTypes,
        );
    }
}

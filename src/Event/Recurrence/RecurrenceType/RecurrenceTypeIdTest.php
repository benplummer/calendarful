<?php

declare(strict_types=1);

namespace Plummer\Calendarful\Event\Recurrence\RecurrenceType;

use PHPUnit\Framework\TestCase;

class RecurrenceTypeIdTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_allow_the_internal_id_to_be_accessed(): void
    {
        $id = '1';

        $recurrenceTypeId = RecurrenceTypeId::fromString(
            $id,
        );

        $this->assertEquals(
            $id,
            $recurrenceTypeId->id(),
        );
    }

    /**
     * @test
     */
    public function it_should_allow_comparison_with_another_recurrence_type_id(): void
    {
        $recurrenceTypeId = RecurrenceTypeId::fromString(
            '1',
        );

        $anotherRecurrenceTypeId = RecurrenceTypeId::fromString(
            '1',
        );

        $andAnotherRecurrenceTypeId = RecurrenceTypeId::fromString(
            '2',
        );

        $this->assertTrue(
            $recurrenceTypeId->equals(
                $anotherRecurrenceTypeId,
            ),
        );

        $this->assertFalse(
            $recurrenceTypeId->equals(
                $andAnotherRecurrenceTypeId,
            ),
        );
    }
}

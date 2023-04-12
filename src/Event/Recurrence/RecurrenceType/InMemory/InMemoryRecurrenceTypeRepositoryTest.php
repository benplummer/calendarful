<?php

declare(strict_types=1);

namespace Plummer\Calendarful\Event\Recurrence\RecurrenceType\InMemory;

use Plummer\Calendarful\Event\Recurrence\RecurrenceType\RecurrenceTypeRepositoryInterface;
use Plummer\Calendarful\Event\Recurrence\RecurrenceType\RecurrenceTypeRepositoryTestCase;

class InMemoryRecurrenceTypeRepositoryTest extends RecurrenceTypeRepositoryTestCase
{
    protected function createRecurrenceTypeRepository(): RecurrenceTypeRepositoryInterface
    {
        return new InMemoryRecurrenceTypeRepository();
    }
}

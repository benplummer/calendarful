<?php

declare(strict_types=1);

namespace Plummer\Calendarful\Event\Recurrence\RecurringEvent\InMemory;

use Plummer\Calendarful\Event\Recurrence\RecurringEvent\RecurringEventRepositoryInterface;
use Plummer\Calendarful\Event\Recurrence\RecurringEvent\RecurringEventRepositoryTestCase;

class InMemoryRecurringEventRepositoryTest extends RecurringEventRepositoryTestCase
{
    protected function createRecurringEventRepository(): RecurringEventRepositoryInterface
    {
        return new InMemoryRecurringEventRepository();
    }
}

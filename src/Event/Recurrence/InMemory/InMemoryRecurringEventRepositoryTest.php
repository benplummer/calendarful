<?php

declare(strict_types=1);

namespace Plummer\Calendarful\Event\Recurrence\InMemory;

use Plummer\Calendarful\Event\Recurrence\RecurringEventRepositoryInterface;
use Plummer\Calendarful\Event\Recurrence\RecurringEventRepositoryTestCase;

class InMemoryRecurringEventRepositoryTest extends RecurringEventRepositoryTestCase
{
    protected function createRecurringEventRepository(): RecurringEventRepositoryInterface
    {
        return new InMemoryRecurringEventRepository();
    }
}

<?php

declare(strict_types=1);

namespace Plummer\Calendarful\Event\Recurrence\EventOverride\InMemory;

use Plummer\Calendarful\Event\Recurrence\EventOverride\EventOverrideRepositoryInterface;
use Plummer\Calendarful\Event\Recurrence\EventOverride\EventOverrideRepositoryTestCase;

class InMemoryEventOverrideRepositoryTest extends EventOverrideRepositoryTestCase
{
    protected function createEventOverrideRepository(): EventOverrideRepositoryInterface
    {
        return new InMemoryEventOverrideRepository();
    }
}

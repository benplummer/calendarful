<?php

declare(strict_types=1);

namespace Plummer\Calendarful\Event\InMemory;

use Plummer\Calendarful\Event\EventRepositoryInterface;
use Plummer\Calendarful\Event\EventRepositoryTestCase;

class InMemoryEventRepositoryTest extends EventRepositoryTestCase
{
    protected function createEventRepository(): EventRepositoryInterface
    {
        return new InMemoryEventRepository();
    }
}

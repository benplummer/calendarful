<?php

declare(strict_types=1);

namespace Plummer\Calendarful\Event\Recurrence;

use DateTimeImmutable;
use Plummer\Calendarful\Event\Recurrence\EventOccurrence\EventOccurrenceCollection;
use Plummer\Calendarful\Event\Recurrence\RecurrenceType\RecurrenceTypeRepositoryInterface;
use Plummer\Calendarful\Event\Recurrence\RecurringEvent\RecurringEventCollection;

class GenerateEventOccurrencesService
{
    public function __construct(
        protected RecurrenceTypeRepositoryInterface $recurrenceTypeRepository,
    ) {
    }

    public function execute(
        RecurringEventCollection $recurringEvents,
        DateTimeImmutable $fromDate,
        DateTimeImmutable $toDate,
    ): EventOccurrenceCollection {
        $eventOccurrences = EventOccurrenceCollection::fromArray(
            [],
        );

        foreach ($this->recurrenceTypeRepository->all() as $recurrenceType) {
            $generatedOccurrences = $recurrenceType->occurrencesFor(
                $recurringEvents,
                $fromDate,
                $toDate,
            );

            $eventOccurrences = $eventOccurrences->merge(
                $generatedOccurrences,
            );
        }

        return $eventOccurrences;
    }
}

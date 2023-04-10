<?php

declare(strict_types=1);

namespace Plummer\Calendarful\Event\Recurrence\RecurrenceType;

class RecurrenceTypeId
{
    private function __construct(
        private string $id,
    ) {
    }

    public static function fromString(
        string $id,
    ): self {
        return new self(
            $id,
        );
    }

    /**
     * Get the internal id.
     *
     * @return string
     */
    public function id(): string
    {
        return $this->id;
    }

    public function equals(
        RecurrenceTypeId $otherRecurrenceTypeId,
    ): bool {
        return $this->id() === $otherRecurrenceTypeId->id();
    }
}

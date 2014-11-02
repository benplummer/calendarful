<?php

namespace Plummer\Calendarful\Recurrence\Type;

use Plummer\Calendar\RecurrenceInterface;

class Daily implements RecurrenceInterface
{
	protected $label = 'daily';

	protected $limit = '+1 year';

	public function getLabel()
	{
		return $this->label;
	}

	public function getLimit()
	{
		return $this->limit;
	}

	public function generateEvents(Array $events, $fromDate, $toDate, $limit = null)
	{
        $return = [];

        $dailyEvents = array_filter($events, function ($event) {
            return $event->getRecurrenceType() === $this->getLabel();
        });

        foreach ($dailyEvents as $dailyEvent) {

            $startMarker = new \DateTime($fromDate) > new \DateTime($dailyEvent->getStartDateFull())
                ? new \DateTime($fromDate)
                : new \DateTime($dailyEvent->getStartFull());

            if (!$dailyEvent->getRecurrenceUntil()) {
                $endMarker = new \DateTime($toDate);
            } else {
                $endMarker = min(new \DateTime($dailyEvent->getRecurrenceUntil()), new \DateTime($toDate));
            }

            while ($startMarker->format('Y-m-d') <= $endMarker->format('Y-m-d')) {
                $newDailyEvent = clone($dailyEvent);
                $newStartDate = clone($startMarker);
                $duration = $newDailyEvent->getDuration();

                $newDailyEvent->setStartDateFull($newStartDate);
                $newStartDate->add($duration);
                $newDailyEvent->setEndDate($newStartDate);

                $return[$newDailyEvent->getId() . '.' . $startMarker->format('Y-m-d')] = $newDailyEvent;

                $startMarker->add(new \DateInterval('P1D'));
            }
        }

        return $return;
    }
}
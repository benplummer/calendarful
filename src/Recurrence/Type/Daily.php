<?php

namespace Plummer\Calendarful\Recurrence\Type;

use Plummer\Calendarful\Recurrence\RecurrenceInterface;

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

	public function generateOccurrences(Array $events, \DateTime $fromDate, \DateTime $toDate, $limit = null)
	{
        $return = [];

        $dailyEvents = array_filter($events, function ($event) {
            return $event->getRecurrenceType() === $this->getLabel();
        });

        foreach ($dailyEvents as $dailyEvent) {

            $startMarker = $fromDate > new \DateTime($dailyEvent->getStartDateFull())
                ? $fromDate
                : new \DateTime($dailyEvent->getStartFull());

            $endMarker = $dailyEvent->getRecurrenceUntil()
                ? min(new \DateTime($dailyEvent->getRecurrenceUntil()), $toDate)
                : $toDate;

            $dateInterval = new \DateInterval('P1D');
            $datePeriod = new \DatePeriod($startMarker, $dateInterval, $endMarker);

            foreach($datePeriod as $date) {
                $newDailyEvent = clone($dailyEvent);
                $newStartDate = $date;
                $duration = $newDailyEvent->getDuration();

                $newDailyEvent->setStartDateFull($newStartDate);
                $newStartDate->add($duration);
                $newDailyEvent->setEndDate($newStartDate);
                $newDailyEvent->setRecurrenceType();

                $return[] = $newDailyEvent;
            }
        }

        return $return;
    }
}
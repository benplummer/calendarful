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

            $startMarker = $fromDate > new \DateTime($dailyEvent->getStartDate())
                ? $fromDate
                : new \DateTime($dailyEvent->getStartDate());

            $endMarker = $dailyEvent->getRecurrenceUntil()
                ? min(new \DateTime($dailyEvent->getRecurrenceUntil()), $toDate)
                : $toDate;

            // The DatePeriod class does not actually include the end date so you have to increment it first
            $endMarker->modify('+1 day');

            $dateInterval = new \DateInterval('P1D');
            $datePeriod = new \DatePeriod($startMarker, $dateInterval, $endMarker);

            $limitMarker = 0;

            foreach($datePeriod as $date) {

                if($limit and ($limit === $limitMarker)) {
                    break;
                }

                $newDailyEvent = clone($dailyEvent);
                $newStartDate = $date;
                $duration = $newDailyEvent->getDuration();

                $newDailyEvent->setStartDate($newStartDate);
                $newStartDate->add($duration);
                $newDailyEvent->setEndDate($newStartDate);
                $newDailyEvent->setRecurrenceType();

                $return[] = $newDailyEvent;

                $limit and $limitMarker++;
            }
        }

        return $return;
    }
}
<?php

namespace Plummer\Calendarful\Recurrence\Type;

use Plummer\Calendarful\Recurrence\RecurrenceInterface;

class Weekly implements RecurrenceInterface
{
	protected $label = 'weekly';

	protected $limit = '+5 year';

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

		$weeklyEvents = array_filter($events, function ($event) {
			return $event->getRecurrenceType() === $this->getLabel();
		});

		foreach ($weeklyEvents as $weeklyEvent) {

			// Retrieve the day of the week that the event takes place on
			$day = date('w', strtotime($weeklyEvent->getStartDate()));

			$startMarker = $fromDate > new \DateTime($weeklyEvent->getStartDate())
				? $fromDate
				: new \DateTime($weeklyEvent->getStartDate());

			while($startMarker->format('w') != $day) {
				$startMarker->modify('P1D');
			}

			$endMarker = $weeklyEvent->getRecurrenceUntil()
				? min(new \DateTime($weeklyEvent->getRecurrenceUntil()), $toDate)
				: $toDate;

			// The DatePeriod class does not actually include the end date so you have to increment it first
			$endMarker->modify('+1 day');

			$dateInterval = new \DateInterval('P1W');
			$datePeriod = new \DatePeriod($startMarker, $dateInterval, $endMarker);

			foreach($datePeriod as $date) {
				$newWeeklyEvent = clone($weeklyEvent);
				$newStartDate = $date;
				$duration = $newWeeklyEvent->getDuration();

				$newWeeklyEvent->setStartDate($newStartDate);
				$newStartDate->add($duration);
				$newWeeklyEvent->setEndDate($newStartDate);
				$newWeeklyEvent->setRecurrenceType();

				$return[] = $newWeeklyEvent;
			}
		}

		return $return;
	}
}
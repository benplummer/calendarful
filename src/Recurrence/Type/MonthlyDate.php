<?php

namespace Plummer\Calendarful\Recurrence\Type;

use Plummer\Calendarful\Recurrence\RecurrenceInterface;

class MonthlyDate implements RecurrenceInterface
{
	protected $label = 'monthly';

	protected $limit = '+25 year';

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

		$monthlyEvents = array_filter($events, function ($event) {
			return $event->getRecurrenceType() === $this->getLabel();
		});

		foreach($monthlyEvents as $monthlyEvent) {

			$monthlyDate = date('d', strtotime($monthlyEvent->getStartDate()));

			$start = $fromDate > new \DateTime($monthlyEvent->getStartDate())
				? clone($fromDate)
				: new \DateTime($monthlyEvent->getStartDate());

			$startMarker = clone($start);
			$startMarker->modify('first day of this month');

			$endMarker = $monthlyEvent->getRecurrenceUntil()
				? min(new \DateTime($monthlyEvent->getRecurrenceUntil()), clone($toDate))
				: clone($toDate);

			$endBoundaryCheck = clone($endMarker);

			// The DatePeriod class does not actually include the end date so you have to increment it first
			$endMarker->modify('+1 day');

			$dateInterval = new \DateInterval('P1M');
			$datePeriod = new \DatePeriod($startMarker, $dateInterval, $endMarker);

			$limitMarker = 0;

			foreach($datePeriod as $date) {

				if(($limit and ($limit === $limitMarker)) or ($date > $endBoundaryCheck)) {
					break;
				}

				if($monthlyDate > $date->format('t')) {
					continue;
				}

				$date->setDate($date->format('Y'), $date->format('m'), sprintf('%2d', $date->format('t')));

				if($date < $start) {
					continue;
				}

				$newMonthlyEvent = clone($monthlyEvent);
				$newStartDate = $date;
				$duration = $newMonthlyEvent->getDuration();

				$newMonthlyEvent->setStartDate($newStartDate);
				$newStartDate->add($duration);
				$newMonthlyEvent->setEndDate($newStartDate);
				$newMonthlyEvent->setRecurrenceType();

				$return[] = $newMonthlyEvent;

				$limit and $limitMarker++;
			}
		}

		return $return;
	}
}
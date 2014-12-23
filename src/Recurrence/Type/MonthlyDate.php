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
		$return = array();

		$monthlyEvents = array_filter($events, function ($event) {
			return $event->getRecurrenceType() === $this->getLabel();
		});

		foreach($monthlyEvents as $monthlyEvent) {

			list(, $monthlyEventTime) = explode(' ', $monthlyEvent->getStartDate());

			$monthlyDate = date('d', strtotime($monthlyEvent->getStartDate()));

			$start = $fromDate > new \DateTime($monthlyEvent->getStartDate())
				? clone($fromDate)
				: new \DateTime($monthlyEvent->getStartDate());

			$startMarker = clone($start);
			$startMarker->setDate($start->format('Y'), $start->format('m'), 1);

			$maxEndMarker = clone($startMarker);
			$maxEndMarker->modify($this->limit);

			$endMarker = $monthlyEvent->getRecurrenceUntil()
				? min(new \DateTime($monthlyEvent->getRecurrenceUntil()), clone($toDate), $maxEndMarker)
				: min(clone($toDate), $maxEndMarker);

			$actualEndMarker = clone($endMarker);

			// The DatePeriod class does not actually include the end date so you have to increment it first
			$endMarker->modify('+1 day');

			$dateInterval = new \DateInterval('P1M');
			$datePeriod = new \DatePeriod($startMarker, $dateInterval, $endMarker);

			$limitMarker = 0;

			foreach($datePeriod as $date) {

				if(($limit and ($limit === $limitMarker)) or ($date > $actualEndMarker)) {
					break;
				}

				if($monthlyDate > $date->format('t')) {
					continue;
				}

				$date->setDate($date->format('Y'), $date->format('m'), sprintf('%2d', $monthlyDate));

				$newMonthlyEvent = clone($monthlyEvent);
				$newStartDate = new \DateTime($date->format('Y-m-d').' '.$monthlyEventTime);

				if($newStartDate < $startMarker) {
					continue;
				}

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
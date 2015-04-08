<?php

namespace Plummer\Calendarful\Recurrence\Type;

use Plummer\Calendarful\Recurrence\RecurrenceInterface;

/**
 * Class MonthlyDate
 *
 * The default recurrence type for generating occurrences for events that recur monthly
 * on a certain date.
 *
 * @package Plummer\Calendarful
 */
class MonthlyDate implements RecurrenceInterface
{
	/**
	 * @var string
	 */
	protected $label = 'monthly';

	/**
	 * @var string
	 */
	protected $limit = '+25 year';

	/**
	 * Get the label of the recurrence type.
	 *
	 * @return string
	 */
	public function getLabel()
	{
		return $this->label;
	}

	/**
	 * Get the limit of the recurrence type.
	 *
	 * @return string
	 */
	public function getLimit()
	{
		return $this->limit;
	}

	/**
	 * Generate the occurrences for each monthly recurring event.
	 *
	 * @param  EventInterface[]		$events
	 * @param  \DateTime			$fromDate
	 * @param  \DateTime			$toDate
	 * @param  int|null				$limit
	 * @return EventInterface[]
	 */
	public function generateOccurrences(Array $events, \DateTime $fromDate, \DateTime $toDate, $limit = null)
	{
		$return = array();
		$object = $this;

		$monthlyEvents = array_filter($events, function ($event) use ($object) {
			return $event->getRecurrenceType() === $object->getLabel();
		});

		foreach ($monthlyEvents as $monthlyEvent) {
			$monthlyEventTime = $monthlyEvent->getStartDate()->format('H:i:s');

			$monthlyDate = $monthlyEvent->getStartDate()->format('d');

			$start = $fromDate > $monthlyEvent->getStartDate()
				? clone($fromDate)
				: clone($monthlyEvent->getStartDate());

			$startMarker = clone($start);
			$startMarker->setDate($start->format('Y'), $start->format('m'), 1);

			$maxEndMarker = clone($startMarker);
			$maxEndMarker->modify($this->limit);

			$endMarker = $monthlyEvent->getRecurrenceUntil()
				? min($monthlyEvent->getRecurrenceUntil(), clone($toDate), $maxEndMarker)
				: min(clone($toDate), $maxEndMarker);

			$actualEndMarker = clone($endMarker);

			// The DatePeriod class does not actually include the end date so you have to increment it first
			$endMarker->modify('+1 day');

			$dateInterval = new \DateInterval('P1M');
			$datePeriod = new \DatePeriod($startMarker, $dateInterval, $endMarker);

			$limitMarker = 0;

			foreach ($datePeriod as $date) {
				if (($limit and ($limit === $limitMarker)) or ($date > $actualEndMarker)) {
					break;
				}

				if ($monthlyDate > $date->format('t')) {
					continue;
				}

				$date->setDate($date->format('Y'), $date->format('m'), sprintf('%2d', $monthlyDate));

				$newMonthlyEvent = clone($monthlyEvent);
				$newStartDate = new \DateTime($date->format('Y-m-d').' '.$monthlyEventTime);

				if ($newStartDate < $startMarker) {
					continue;
				}

				$duration = $newMonthlyEvent->getDuration();

				$newMonthlyEvent->setStartDate($newStartDate);

				$newEndDate = clone($newStartDate);
				$newEndDate->add($duration);
				
				$newMonthlyEvent->setEndDate($newEndDate);
				$newMonthlyEvent->setRecurrenceType();

				$return[] = $newMonthlyEvent;

				$limit and $limitMarker++;
			}
		}

		return $return;
	}
}

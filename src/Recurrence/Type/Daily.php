<?php

namespace Plummer\Calendarful\Recurrence\Type;

use Plummer\Calendarful\Recurrence\RecurrenceInterface;

/**
 * Class Daily
 *
 * The default recurrence type for generating occurrences for events that recur daily.
 *
 * @package Plummer\Calendarful
 */
class Daily implements RecurrenceInterface
{
	/**
	 * @var string
	 */
	protected $label = 'daily';

	/**
	 * @var string
	 */
	protected $limit = '+1 year';

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
	 * Generate the occurrences for each daily recurring event.
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

		$dailyEvents = array_filter($events, function ($event) use ($object) {
			return $event->getRecurrenceType() === $object->getLabel();
		});

		foreach ($dailyEvents as $dailyEvent) {
			$dailyEventTime = $dailyEvent->getStartDate()->format('H:i:s');
			
			$startMarker = $fromDate > $dailyEvent->getStartDate()
				? clone($fromDate)
				: clone($dailyEvent->getStartDate());

			$maxEndMarker = clone($startMarker);
			$maxEndMarker->modify($this->limit);

			$endMarker = $dailyEvent->getRecurrenceUntil()
				? min($dailyEvent->getRecurrenceUntil(), clone($toDate), $maxEndMarker)
				: min(clone($toDate), $maxEndMarker);

			$actualEndMarker = clone($endMarker);

			// The DatePeriod class does not actually include the end date so you have to increment it first
			$endMarker->modify('+1 day');

			$dateInterval = new \DateInterval('P1D');
			$datePeriod = new \DatePeriod($startMarker, $dateInterval, $endMarker);

			$limitMarker = 0;

			foreach ($datePeriod as $date) {
				if (($limit and ($limit === $limitMarker)) or ($date > $actualEndMarker)) {
					break;
				}

				$newDailyEvent = clone($dailyEvent);
				$newStartDate = new \DateTime($date->format('Y-m-d').' '.$dailyEventTime);

				if ($newStartDate < $startMarker) {
					continue;
				}

				$duration = $newDailyEvent->getDuration();

				$newDailyEvent->setStartDate($newStartDate);

				$newEndDate = clone($newStartDate);
				$newEndDate->add($duration);
				
				$newDailyEvent->setEndDate($newEndDate);
				$newDailyEvent->setRecurrenceType();

				$return[] = $newDailyEvent;

				$limit and $limitMarker++;
			}
		}

		return $return;
	}
}

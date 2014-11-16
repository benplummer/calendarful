<?php

namespace Plummer\Calendarful\Recurrence;

interface RecurrenceInterface
{
	public function getLabel();

	public function getLimit();

	public function generateOccurrences(Array $events, \DateTime $fromDate, \DateTime $toDate, $limit = null);
}

<?php

namespace Plummer\Calendarful;

interface RecurrenceInterface
{
	public function getLabel();

	public function getLimit();

	public function generateOccurrences(Array $events, $fromDate, $toDate, $limit = null);
}

<?php

namespace Plummer\Calendarful\Calendar;

use Plummer\Calendarful\RegistryInterface;

interface CalendarInterface
{
	public function populate(RegistryInterface $eventsRegistry, \DateTime $fromDate, \DateTime $toDate, $limit = null, Array $extraFilters = array());

	public function sort();
}

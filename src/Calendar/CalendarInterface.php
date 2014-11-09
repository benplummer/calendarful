<?php

namespace Plummer\Calendarful;

interface CalendarInterface
{
	public function getName();

	public function populate(RegistryInterface $eventsRegistry, \DateTime $fromDate, \DateTime $toDate, $limit);
}

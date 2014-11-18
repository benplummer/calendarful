<?php

namespace Plummer\Calendarful\Calendar;

use Plummer\Calendarful\Recurrence\RecurrenceFactoryInterface;
use Plummer\Calendarful\RegistryInterface;

interface CalendarInterface
{
	public function addRecurrenceFactory(RecurrenceFactoryInterface $recurrenceFactory);

	public function populate(RegistryInterface $eventsRegistry, \DateTime $fromDate, \DateTime $toDate, $limit);

	public function sort();
}

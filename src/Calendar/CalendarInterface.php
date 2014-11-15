<?php

namespace Plummer\Calendarful\Calendar;

use Plummer\Calendarful\Recurrence\RecurrenceFactoryInterface;

interface CalendarInterface
{
	public function addRecurrenceFactory(RecurrenceFactoryInterface $recurrenceFactory);

	public function populate(RegistryInterface $eventsRegistry, \DateTime $fromDate, \DateTime $toDate, $limit);
}

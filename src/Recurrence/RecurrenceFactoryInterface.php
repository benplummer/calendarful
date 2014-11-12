<?php

namespace Plummer\Calendarful\Recurrence;

interface RecurrenceFactoryInterface
{
	public function createRecurrenceType($type);

	public function getRecurrenceTypes();
}
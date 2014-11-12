<?php

namespace Plummer\Calendarful\Recurrence;

interface RecurrenceFactoryInterface
{
	public function createRecurrence($type);

	public function getRecurrences();
}
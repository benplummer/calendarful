<?php

namespace Plummer\Calendar;

class Event
{
	protected $recurrenceType;

	public function setRecurrenceType(RecurrenceInterface $recurrenceType)
	{
		$this->recurrenceType = $recurrenceType;
	}
}
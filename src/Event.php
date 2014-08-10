<?php

namespace Plummer\Calendar;

abstract class Event
{
	protected $recurrenceType;

	public function setRecurrenceType(RecurrenceInterface $recurrenceType)
	{
		$this->recurrenceType = $recurrenceType;
	}
}
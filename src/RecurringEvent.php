<?php

namespace Plummer\Calendar;

class RecurringEvent implements EventInterface
{
	protected $event;

	protected $recurrenceType;

	protected function __construct(Event $event, RecurrenceInterface $recurrenceType)
	{
		$this->event = $event;
		$this->recurrenceType = $recurrenceType;
	}

	public static function make(Event $event, RecurrenceInterface $recurrenceType)
	{
		return new static($event, $recurrenceType);
	}
}
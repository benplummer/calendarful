<?php

namespace Plummer\Calendar;

class RecurringEvent implements EventInterface
{
	protected $event;

	protected $recurrenceType;

	protected $occurrenceDateStart;

	protected function __construct(Event $event, RecurrenceInterface $recurrenceType, $occurrenceDateStart)
	{
		$this->event = $event;
		$this->recurrenceType = $recurrenceType;
		$this->occurrenceDateStart = $occurrenceDateStart;
	}

	public static function make(Event $event, RecurrenceInterface $recurrenceType, $occurrenceDateStart = null)
	{
		return new static($event, $recurrenceType, $occurrenceDateStart);
	}
}
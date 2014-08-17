<?php

namespace Plummer\Calendar;

class EventAdaptor implements EventInterface
{
	protected $event;

	protected function __construct(EventAdaptable $event)
	{
		$this->event = $event;
	}

	public static function make(EventAdaptable $event)
	{
		return new static($event);
	}
}
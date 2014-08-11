<?php

namespace Plummer\Calendar;

abstract class CalendarDecorator implements RenderInterface
{
	protected $calendar;

	public function __construct(Calendar $calendar)
	{
		$this->calendar = $calendar;
	}
}
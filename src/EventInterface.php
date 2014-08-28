<?php

namespace Plummer\Calendar;

interface EventInterface
{
	public function getName();

	public function getStartDate();

	public function getEndDate();

	public function getOccurrenceDate();

	public function getParent();

	public function setCalendar(Calendar &$calendar);
}
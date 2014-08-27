<?php

namespace Plummer\Calendar;

interface RecurrenceInterface
{
	public function getLabel();

	public function getLimit();

	public function generate();
}
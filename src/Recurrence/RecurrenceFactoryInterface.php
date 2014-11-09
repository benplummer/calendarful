<?php

namespace Plummer\Calendarful\Recurrence;

interface RecurrenceFactoryInterface
{
	public function createFactory($type);

	public function getFactories();
}
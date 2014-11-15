<?php

namespace Plummer\Calendarful\Recurrence;

class RecurrenceFactory implements RecurrenceFactoryInterface
{
	protected $recurrenceTypes = [];

	public function addRecurrenceType($type, $recurrenceType)
	{
		if(!in_array('Plummer\Calendarful\Recurrence\RecurrenceInterface', class_implements($recurrenceType))) {
			throw new \InvalidArgumentException('File or File path required.');
		}

		$this->recurrenceTypes[$type] = is_string($recurrenceType) ?
			$recurrenceType :
			get_class($recurrenceType);
	}

	public function getRecurrenceTypes()
	{
		return $this->recurrenceTypes;
	}

	public function createRecurrenceType($type)
	{
		if(!isset($this->recurrenceTypes[$type])) {
			throw new \Exception('The type passed does not exist.');
		}

		return $this->recurrenceTypes[$type];
	}
}
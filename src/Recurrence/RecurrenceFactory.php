<?php

namespace Plummer\Calendarful\Recurrence;

class RecurrenceFactory implements RecurrenceFactoryInterface
{
	protected $recurrenceTypes = [];

	public function addRecurrenceType($type, $recurrenceType)
	{
		if(is_string($recurrenceType) and !class_exists($recurrenceType)) {
			throw new \InvalidArgumentException("Class {$recurrenceType} des not exist.");
		}
		else if(!in_array('Plummer\Calendarful\Recurrence\RecurrenceInterface', class_implements($recurrenceType))) {
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
			throw new \OutOfBoundsException("A recurrence type called {$type} does not exist within the factory.");
		}

		return $this->recurrenceTypes[$type];
	}
}
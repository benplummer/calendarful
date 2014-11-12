<?php

namespace Plummer\Calendarful\Recurrence;

use \Plummer\Calendarful\RegistryInterface as RegistryInterface;

class RecurrenceFactory implements RecurrenceFactoryInterface
{
	private $recurrenceTypes = [];

	public function fromRegistry(RegistryInterface $recurrenceRegistry)
	{
		$this->recurrenceTypes = $recurrenceRegistry->get();
	}

	public function addRecurrenceType(RecurrenceInterface $recurrence)
	{
		$this->recurrenceTypes[$recurrence->getLabel()] = $recurrence;
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
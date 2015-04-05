<?php

namespace Plummer\Calendarful\Recurrence;

/**
 * Class RecurrenceFactory
 *
 * The Recurrence Factory stores different implementations of the Recurrence Interface.
 * When requested it can create specific implementations if they have been stored or return
 * all of them.
 *
 * This class would usually be used in conjunction with Calendar implementations.
 *
 * @package Plummer\Calendarful
 */
class RecurrenceFactory implements RecurrenceFactoryInterface
{
	/**
	 * @var string[]
	 */
	protected $recurrenceTypes = array();

	/**
	 * Stores recurrence type class paths when provided with a key and an instance or
	 * class path of an implementation.
	 *
	 * @param string						$type
	 * @param string|RecurrenceInterface	$recurrenceType
	 */
	public function addRecurrenceType($type, $recurrenceType)
	{
		if (is_string($recurrenceType) and !class_exists($recurrenceType)) {
			throw new \InvalidArgumentException("Class {$recurrenceType} does not exist.");
		} elseif (!in_array('Plummer\Calendarful\Recurrence\RecurrenceInterface', class_implements($recurrenceType))) {
			throw new \InvalidArgumentException('File or File path required.');
		}

		$this->recurrenceTypes[$type] = is_string($recurrenceType) ?
			$recurrenceType :
			get_class($recurrenceType);
	}

	/**
	 * Get all of the stored recurrence types.
	 *
	 * @return string[]
	 */
	public function getRecurrenceTypes()
	{
		return $this->recurrenceTypes;
	}

	/**
	 * Creates and returns an instance of a stored recurrence type.
	 *
	 * @param  string				$type
	 * @return RecurrenceInterface
	 * @throws OutOfBoundsException
	 */
	public function createRecurrenceType($type)
	{
		if (!isset($this->recurrenceTypes[$type])) {
			throw new \OutOfBoundsException("A recurrence type called {$type} does not exist within the factory.");
		}

		$recurrenceType = new $this->recurrenceTypes[$type]();

		return $recurrenceType;
	}
}

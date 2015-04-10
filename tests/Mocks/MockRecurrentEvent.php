<?php

namespace Plummer\Calendarful\Mocks;

use Plummer\Calendarful\Event\RecurrentEventInterface;

class MockRecurrentEvent extends MockEvent implements RecurrentEventInterface
{
	protected $recurrenceType;

	protected $recurrenceUntil;

	public function __construct($id, $startDate, $endDate, $parentId = null, $occurrenceDate = null, $recurrenceType = null, $recurrenceUntil = null)
	{
		$this->id = $id;
		$this->startDate = new \DateTime($startDate);
		$this->endDate = new \DateTime($endDate);
		$this->parentId = $parentId;
		$this->occurrenceDate = $occurrenceDate ? new \DateTime($occurrenceDate) : null;
		$this->recurrenceType = $recurrenceType;
		$this->recurrenceUntil = $recurrenceUntil ? new \DateTime($recurrenceUntil) : null;
	}

	public function getRecurrenceType()
	{
		return $this->recurrenceType;
	}

	public function setRecurrenceType($type = null)
	{
		if ($type === null) {
			$this->recurrenceUntil = null;
		}

		$this->recurrenceType = $type;
	}

	public function getRecurrenceUntil()
	{
		return $this->recurrenceUntil;
	}
}

<?php

namespace Mocks;

use Plummer\Calendarful\Event\EventInterface;

class MockEvent implements EventInterface
{
	protected $id;

	protected $startDate;

	protected $endDate;

	protected $parentId;

	protected $occurrenceDate;

	protected $recurrenceType;

	protected $recurrenceUntil;

	protected function __construct()
	{
	}

	public function getId()
	{
		return $this->id;
	}

	public function getName()
	{
		return $this->name;
	}

	public function getStartDate()
	{
		return $this->startDate;
	}

	public function setStartDate(\DateTime $startDate)
	{
		$this->startDate = $startDate->format('Y-m-d H:i:s');
	}

	public function getEndDate()
	{
		return $this->endDate;
	}

	public function setEndDate(\DateTime $endDate)
	{
		$this->endDate = $endDate->format('Y-m-d H:i:s');
	}

	public function getDuration()
	{
		$start = new \DateTime($this->startDate);
		$end = new \DateTime($this->endDate);

		$interval = $start->diff($end);

		return $interval;
	}

	public function getParentId()
	{
		return $this->parentId;
	}

	public function getOccurrenceDate() {
		return $this->occurrenceDate;
	}

	public function getRecurrenceType()
	{
		return $this->recurrenceType;
	}

	public function setRecurrenceType($type = null)
	{
		if($type === null) {
			$this->recurrenceUntil = null;
		}

		$this->recurrenceType = $type;
	}

	public function getRecurrenceUntil()
	{
		return $this->recurrenceUntil;
	}
}
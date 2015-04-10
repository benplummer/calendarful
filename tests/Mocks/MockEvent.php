<?php

namespace Plummer\Calendarful\Mocks;

use Plummer\Calendarful\Event\EventInterface;

class MockEvent implements EventInterface
{
	protected $id;

	protected $startDate;

	protected $endDate;
	
	protected $parentId;

	protected $occurrenceDate;

	public function __construct($id, $startDate, $endDate, $parentId = null, $occurrenceDate = null)
	{
		$this->id = $id;
		$this->startDate = new \DateTime($startDate);
		$this->endDate = new \DateTime($endDate);
		$this->parentId = $parentId;
		$this->occurrenceDate = $occurrenceDate ? new \DateTime($occurrenceDate) : null;
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
		$this->startDate = $startDate;
	}

	public function getEndDate()
	{
		return $this->endDate;
	}

	public function setEndDate(\DateTime $endDate)
	{
		$this->endDate = $endDate;
	}

	public function getDuration()
	{
		return $this->startDate->diff($this->endDate);
	}

	public function getParentId()
	{
		return $this->parentId;
	}

	public function getOccurrenceDate()
	{
		return $this->occurrenceDate;
	}
}

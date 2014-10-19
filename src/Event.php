<?php

namespace Plummer\Calendar;

class Event implements EventInterface
{
	protected $id;

	protected $name;

	protected $startDate;

	protected $endDate;

	protected $parentDate;

	protected $calendar;

    protected $parent;

    protected $recurrenceType;

    protected $recurrenceUntil;
	
	protected function __construct($id, $name, $startDate, $endDate)
	{
		$this->id = $id;
		$this->name = $name;
		$this->startDate = $startDate;
		$this->endDate = $endDate;
	}

	public static function make($id, $name, $startDate, $endDate, $occurrenceDate = null)
	{
		return new static($id, $name, $startDate, $endDate, $occurrenceDate);
	}

	public function setCalendar(Calendar &$calendar)
	{
		$this->calendar = $calendar;
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

	public function getEndDate()
	{
		return $this->endDate;
	}

	public function getParent()
	{
		return $this->parent;
	}

	public function getParentDate()
	{
		return $this->parentDate;
    }

    public function getRecurrenceType()
    {
        return $this->recurrenceType;
    }

    public function getRecurrenceUntil()
    {
        return $this->recurrenceUntil;
    }
}

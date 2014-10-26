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

	public function getStartDateFull()
	{
		return $this->startDate;
	}

	public function setStartDateFull(\DateTime $startDateFull)
	{
		$this->startDate = $startDateFull->format('Y-m-d H:i:s');
	}

	public function getStartDate()
	{
		list($date,) = explode(' ', $this->startDate);

		return $date;
	}

	public function getStartTime()
	{
		list(, $time) = explode(' ', $this->startDate);

		return $time;
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

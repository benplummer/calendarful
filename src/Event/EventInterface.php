<?php

namespace Plummer\Calendarful\Event;

interface EventInterface
{
	public function getId();

	public function getStartDate();

	public function setStartDate(\DateTime $startDateFull);

	public function getEndDate();

	public function setEndDate(\DateTime $endDate);

	public function getDuration();

	public function getParentId();

	public function getOccurrenceDate();
    
    public function getRecurrenceType();

	public function setRecurrenceType($type = null);

    public function getRecurrenceUntil();

	public function getRecurrenceDayName();

	public function getRecurrenceDayOfMonth();

	public function getRecurrenceWeekNumber();
}

<?php

namespace Plummer\Calendarful\Event;

interface EventInterface
{
	public function getId();

	public function getStartDateFull();

	public function setStartDateFull(\DateTime $startDateFull);

	public function getStartDate();

	public function getStartTime();

	public function getEndDate();

	public function setEndDate(\DateTime $endDate);

	public function getDuration();

	public function getParentId();

	public function getOccurrenceDate();
    
    public function getRecurrenceType();

	public function setRecurrenceType($type = null);

    public function getRecurrenceUntil();
}

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

	public function getParent();

	public function getParentDate();
    
    public function getRecurrenceType();

	public function setRecurrenceType($type = null);

    public function getRecurrenceUntil();
}

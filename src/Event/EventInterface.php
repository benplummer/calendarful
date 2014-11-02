<?php

namespace Plummer\Calendarful;

interface EventInterface
{
	public function getId();

	public function getName();

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

    public function getRecurrenceUntil();

	public function setCalendar(Calendar &$calendar);
}

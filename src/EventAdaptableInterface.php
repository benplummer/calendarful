<?php

namespace Calendar;

interface EventAdaptable
{
	public function getUniqueId();

	public function getName();

	public function getStartDate();

	public function getEndDate();

	public function getOccurrenceDate();

	public function getParent();
}
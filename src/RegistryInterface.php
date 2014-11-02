<?php

namespace Plummer\Calendarful;

interface RegistryInterface
{
	public function get(Array $filters = []);
}
<?php

namespace Plummer\Calendar;

abstract class RegistryAbstract implements RegistryInterface
{
	abstract public function set($key, $value);

	abstract public function get($key);

	abstract public function getAll();

	abstract public function getFiltered(Array $filters);
}
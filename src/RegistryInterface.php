<?php

namespace Plummer\Calendar;

interface RegistryInterface
{
	/**
	 * sets a value
	 *
	 * @param string $key
	 * @param mixed $value
	 *
	 * @static
	 * @return void
	 */
	public function set($key, $value);

	/**
	 * gets a value from the registry
	 *
	 * @param string $key
	 *
	 * @static
	 * @return mixed
	 */
	public function get($key);

	public function getAll();

	public function getFiltered(Array $filters);
}
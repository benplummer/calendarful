<?php

namespace Plummer\Calendarful;

/**
 * Interface RegistryInterface
 *
 * Provides an interface for registry implementations that allow the
 * persistence of data.
 *
 * @package Plummer\Calendarful
 * @abstract
 */
interface RegistryInterface
{
	/**
	 * Gets data and allows the passing of filters if desired.
	 *
	 * @param  mixed[] $filters
	 * @return mixed[]
	 */
	public function get(Array $filters = array());
}

<?php

namespace Plummer\Calendarful\Event;

/**
 * Interface EventRegistryInterface
 *
 * Provides an interface for registry implementations that allow the
 * persistence of data.
 *
 * @package Plummer\Calendarful
 * @abstract
 */
interface EventRegistryInterface
{
	/**
	 * Gets data and allows the passing of filters if desired.
	 *
	 * @param	array $filters
	 * @return	EventInterface[]
	 */
	public function getEvents(array $filters = array());

	/**
	 * Gets data and allows the passing of filters if desired.
	 *
	 * @param	array $filters
	 * @return	EventInterface[]
	 */
	public function getRecurrentEvents(array $filters = array());
}

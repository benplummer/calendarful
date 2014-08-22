<?php

namespace Plummer\Calendar;

class CachingRegistry implements RegistryInterface
{
	protected $registry;

	protected $values = [];

	public function __construct(RegistryInterface $registry)
	{
		$this->registry = $registry;
	}

	public function set($key, $value)
	{
		$this->registry->set($key, $value);
		$this->values[$key] = $value;

		return $this;
	}

	public function get($key)
	{
		if(isset($this->values[$key])) {
			return $this->values[$key];
		}

		$value = $this->registry->get($key);


		$this->values[$key] = $value;

		return $value;
	}

	public function getAll()
	{
		$this->values += $this->registry->getAll();

		return $this->values;
	}

	public function getIterator()
	{
		return $this->registry->getIterator();
	}
}
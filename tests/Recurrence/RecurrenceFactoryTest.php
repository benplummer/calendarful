<?php

namespace Plummer\Calendarful\Recurrence;

use Mockery as m;
use Plummer\Calendarful\Recurrence\Type\Daily;

class RecurrenceFactoryTest extends \PHPUnit_Framework_TestCase
{
	public function tearDown()
	{
		m::close();
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testRecurrenceTypeClassDoesNotExist()
	{
		$recurrenceFactory = new RecurrenceFactory();

		$recurrenceFactory->addRecurrenceType('test', 'ThisIsNotAValidFileOrFilePath');
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testRecurrenceTypeClassPathNotRecurrenceInterfaceImplementation()
	{
		$recurrenceFactory = new RecurrenceFactory();

		$recurrenceFactory->addRecurrenceType('test', 'Plummer\Calendarful\Mocks\MockEvent');
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testRecurrenceTypeClassNotRecurrenceInterfaceImplementation()
	{
		$recurrenceFactory = new RecurrenceFactory();

		$recurrenceFactory->addRecurrenceType('test', new \stdClass());
	}

	public function testValidRecurrenceTypeClassPath()
	{
		$recurrenceFactory = new RecurrenceFactory();

		$recurrenceFactory->addRecurrenceType('test', 'Plummer\Calendarful\Recurrence\Type\Daily');

		$this->assertEquals(1, count($recurrenceFactory->getRecurrenceTypes()));
	}

	public function testValidRecurrenceTypeClass()
	{
		$recurrenceFactory = new RecurrenceFactory();

		$recurrenceFactory->addRecurrenceType('test', new Daily());

		$this->assertEquals(1, count($recurrenceFactory->getRecurrenceTypes()));
	}

	/**
	 * @expectedException OutOfBoundsException
	 */
	public function testNonExistentRecurrenceTypeClassRetrieval()
	{
		$recurrenceFactory = new RecurrenceFactory();

		$recurrence = $recurrenceFactory->createRecurrenceType('test');
	}

	public function testValidRecurrenceTypeClassRetrieval()
	{
		$recurrenceFactory = new RecurrenceFactory();

		$recurrenceFactory->addRecurrenceType('daily', new Daily());

		$this->assertInstanceOf('Plummer\Calendarful\Recurrence\RecurrenceInterface', $recurrenceFactory->createRecurrenceType('daily'));
	}
}

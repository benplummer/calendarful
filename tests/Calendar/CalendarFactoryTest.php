<?php

namespace Plummer\Calendarful\Calendar;

use Mockery as m;

class CalendarFactoryTest extends \PHPUnit_Framework_TestCase
{
	public function tearDown()
	{
		m::close();
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testCalendarTypeClassDoesNotExist()
	{
		$calendarFactory = new CalendarFactory();

		$calendarFactory->addCalendarType('test', 'ThisIsNotAValidFileOrFilePath');
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testCalendarTypeClassPathNotCalendarInterfaceImplementation()
	{
		$calendarFactory = new CalendarFactory();

		$calendarFactory->addCalendarType('test', 'Plummer\Calendarful\Mocks\MockEvent');
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testCalendarTypeClassNotCalendarInterfaceImplementation()
	{
		$calendarFactory = new CalendarFactory();

		$calendarFactory->addCalendarType('test', new \stdClass());
	}

	public function testValidCalendarTypeClassPath()
	{
		$calendarFactory = new CalendarFactory();

		$calendarFactory->addCalendarType('test', 'Plummer\Calendarful\Calendar\Calendar');

		$this->assertEquals(1, count($calendarFactory->getCalendarTypes()));
	}

	public function testValidCalendarTypeClass()
	{
		$calendarFactory = new CalendarFactory();

		$calendarFactory->addCalendarType('test', new Calendar());

		$this->assertEquals(1, count($calendarFactory->getCalendarTypes()));
	}

	/**
	 * @expectedException OutOfBoundsException
	 */
	public function testNonExistentCalendarTypeClassRetrieval()
	{
		$calendarFactory = new CalendarFactory();

		$calendar = $calendarFactory->createCalendar('test');
	}

	public function testValidCalendarTypeClassRetrieval()
	{
		$calendarFactory = new CalendarFactory();

		$calendarFactory->addCalendarType('test', new Calendar());

		$this->assertInstanceOf('Plummer\Calendarful\Calendar\CalendarInterface', $calendarFactory->createCalendar('test'));
	}
}

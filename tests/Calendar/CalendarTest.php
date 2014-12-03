<?php

namespace Plummer\Calendarful\Calendar;

use \Mockery as m;
use Plummer\Calendarful\Mocks\MockEvent;

class CalendarTest extends \PHPUnit_Framework_TestCase
{
	public function tearDown()
	{
		m::close();
	}

	public function testCalendarEventWithinDateRange()
	{
		$calendar = new Calendar();

		$eventRegistry = m::mock('\Plummer\Calendarful\RegistryInterface');
		$eventRegistry->shouldReceive('get')
			->once()
			->andReturn(
				[
					new MockEvent(
						1,
						'2014-12-02 00:00:00',
						'2014-12-02 23:59:59'
					)
				]
			);

		$calendar->populate($eventRegistry, new \DateTime('2014-12-01 00:00:00'), new \DateTime('2014-12-03 00:00:00'));

		$this->assertEquals(1, $calendar->count());

		foreach($calendar as $event) {
			$this->assertEquals('2014-12-02 00:00:00', $event->getStartDate());
		}
	}

	public function testCalendarEventOutOfDateRange()
	{
		$calendar = new Calendar();

		$eventRegistry = m::mock('\Plummer\Calendarful\RegistryInterface');
		$eventRegistry->shouldReceive('get')
			->once()
			->andReturn(
				[
					new MockEvent(
						1,
						'2014-12-05 00:00:00',
						'2014-12-05 23:59:59'
					)
				]
			);

		$calendar->populate($eventRegistry, new \DateTime('2014-12-01 00:00:00'), new \DateTime('2014-12-03 00:00:00'));

		$this->assertEquals(0, $calendar->count());
	}

	public function testCalendarEventStartDateWithinDateRange()
	{
		$calendar = new Calendar();

		$eventRegistry = m::mock('\Plummer\Calendarful\RegistryInterface');
		$eventRegistry->shouldReceive('get')
			->once()
			->andReturn(
				[
					new MockEvent(
						1,
						'2014-12-02 00:00:00',
						'2014-12-04 23:59:59'
					)
				]
			);

		$calendar->populate($eventRegistry, new \DateTime('2014-12-01 00:00:00'), new \DateTime('2014-12-03 00:00:00'));

		$this->assertEquals(1, $calendar->count());

		foreach($calendar as $event) {
			$this->assertEquals('2014-12-02 00:00:00', $event->getStartDate());
		}
	}

	public function testCalendarEventEndDateWithinDateRange()
	{
		$calendar = new Calendar();

		$eventRegistry = m::mock('\Plummer\Calendarful\RegistryInterface');
		$eventRegistry->shouldReceive('get')
			->once()
			->andReturn(
				[
					new MockEvent(
						1,
						'2014-11-30 00:00:00',
						'2014-12-02 23:59:59'
					)
				]
			);

		$calendar->populate($eventRegistry, new \DateTime('2014-12-01 00:00:00'), new \DateTime('2014-12-03 00:00:00'));

		$this->assertEquals(1, $calendar->count());

		foreach($calendar as $event) {
			$this->assertEquals('2014-11-30 00:00:00', $event->getStartDate());
		}
	}
}
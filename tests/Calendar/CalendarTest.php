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

	public function testCalendarOneEventWithinRange()
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
						'2014-12-02 00:00:00'
					)
				]
			);

		$calendar->populate($eventRegistry, new \DateTime('2014-12-01 00:00:00'), new \DateTime('2014-12-03 00:00:00'));

		$this->assertEquals(1, $calendar->count());

		foreach($calendar as $event) {
			$this->assertEquals('2014-12-02 00:00:00', $event->getStartDate());
		}
	}
}
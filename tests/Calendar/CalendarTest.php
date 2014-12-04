<?php

namespace Plummer\Calendarful\Calendar;

use \Mockery as m;
use Plummer\Calendarful\Mocks\MockEvent;
use Plummer\Calendarful\Recurrence\RecurrenceFactory;

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
			->andReturn([new MockEvent(1, '2014-12-02 00:00:00', '2014-12-02 23:59:59')]);

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
			->andReturn([new MockEvent(1, '2014-12-05 00:00:00', '2014-12-05 23:59:59')]);

		$calendar->populate($eventRegistry, new \DateTime('2014-12-01 00:00:00'), new \DateTime('2014-12-03 00:00:00'));

		$this->assertEquals(0, $calendar->count());
	}

	public function testCalendarEventStartDateWithinDateRange()
	{
		$calendar = new Calendar();

		$eventRegistry = m::mock('\Plummer\Calendarful\RegistryInterface');
		$eventRegistry->shouldReceive('get')
			->once()
			->andReturn([new MockEvent(1, '2014-12-02 00:00:00', '2014-12-04 23:59:59')]);

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
			->andReturn([new MockEvent(1, '2014-11-30 00:00:00', '2014-12-02 23:59:59')]);

		$calendar->populate($eventRegistry, new \DateTime('2014-12-01 00:00:00'), new \DateTime('2014-12-03 00:00:00'));

		$this->assertEquals(1, $calendar->count());

		foreach($calendar as $event) {
			$this->assertEquals('2014-11-30 00:00:00', $event->getStartDate());
		}
	}

	public function testCalendarEventOverlapsDateRange()
	{
		$calendar = new Calendar();

		$eventRegistry = m::mock('\Plummer\Calendarful\RegistryInterface');
		$eventRegistry->shouldReceive('get')
			->once()
			->andReturn([new MockEvent(1, '2014-11-30 00:00:00', '2014-12-03 23:59:59')]);

		$calendar->populate($eventRegistry, new \DateTime('2014-12-01 00:00:00'), new \DateTime('2014-12-03 00:00:00'));

		$this->assertEquals(1, $calendar->count());

		foreach($calendar as $event) {
			$this->assertEquals('2014-11-30 00:00:00', $event->getStartDate());
		}
	}

	public function testCalendarEventLimit()
	{
		$calendar = new Calendar();

		$eventRegistry = m::mock('\Plummer\Calendarful\RegistryInterface');
		$eventRegistry->shouldReceive('get')
			->once()
			->andReturn([
				new MockEvent(1, '2014-12-02 00:00:00', '2014-12-02 23:59:59'),
				new MockEvent(2, '2014-12-01 00:00:00', '2014-12-01 23:59:59')
			]);

		$calendar->populate($eventRegistry, new \DateTime('2014-12-01 00:00:00'), new \DateTime('2014-12-03 00:00:00'), 1);

		$this->assertEquals(1, $calendar->count());
	}

	public function testCalendarEventSorting()
	{
		$calendar = new Calendar();

		$eventRegistry = m::mock('\Plummer\Calendarful\RegistryInterface');
		$eventRegistry->shouldReceive('get')
			->once()
			->andReturn([
				new MockEvent(1, '2014-12-02 00:00:00', '2014-12-02 23:59:59'),
				new MockEvent(2, '2014-12-01 00:00:00', '2014-12-01 23:59:59'),
				new MockEvent(3, '2014-12-04 00:00:00', '2014-12-04 23:59:59'),
				new MockEvent(4, '2014-12-03 00:00:00', '2014-12-03 23:59:59')
			]);

		$calendar->populate($eventRegistry, new \DateTime('2014-12-01 00:00:00'), new \DateTime('2014-12-08 00:00:00'));

		$previous = null;

		foreach($calendar as $event) {
			if(!$previous) {
				$previous = $event->getStartDate();
				continue;
			}

			$this->assertGreaterThanOrEqual($previous, $event->getStartDate());

			$previous = $event->getStartDate();
		}
	}

	public function testCalendarSameDayEventSorting()
	{
		$calendar = new Calendar();

		$eventRegistry = m::mock('\Plummer\Calendarful\RegistryInterface');
		$eventRegistry->shouldReceive('get')
			->once()
			->andReturn([
				new MockEvent(1, '2014-12-02 12:00:00', '2014-12-02 13:00:00'),
				new MockEvent(2, '2014-12-02 06:00:00', '2014-12-02 07:00:00'),
				new MockEvent(3, '2014-12-02 00:00:00', '2014-12-02 01:00:00'),
				new MockEvent(4, '2014-12-02 18:00:00', '2014-12-02 19:00:00')
			]);

		$calendar->populate($eventRegistry, new \DateTime('2014-12-01 00:00:00'), new \DateTime('2014-12-03 00:00:00'));

		$previous = null;

		foreach($calendar as $event) {
			if(!$previous) {
				$previous = $event->getStartDate();
				continue;
			}

			$this->assertGreaterThanOrEqual($previous, $event->getStartDate());

			$previous = $event->getStartDate();
		}
	}

	public function testCalendarOverriddenEventRemoval()
	{
		$recurrenceFactory = new RecurrenceFactory();
		$recurrenceFactory->addRecurrenceType('daily', 'Plummer\Calendarful\Recurrence\Type\Daily');

		$eventRegistry = m::mock('\Plummer\Calendarful\RegistryInterface');
		$eventRegistry->shouldReceive('get')
			->once()
			->andReturn([
				new MockEvent(1, '2014-12-01 12:00:00', '2014-12-01 13:00:00', 'daily'),
				new MockEvent(2, '2014-12-02 18:00:00', '2014-12-02 19:00:00', null, null, 1, '2014-12-02 18:00:00'),
			]);

		$calendar = Calendar::create($recurrenceFactory)
			->populate($eventRegistry, new \DateTime('2014-12-01 00:00:00'), new \DateTime('2014-12-05 00:00:00'));

		$this->assertEquals(4, $calendar->count());
	}
}
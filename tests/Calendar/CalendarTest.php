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

	public function testSameDayEventWithinDateRange()
	{
		$calendar = new Calendar();

		$eventRegistry = m::mock('\Plummer\Calendarful\RegistryInterface');
		$eventRegistry->shouldReceive('get')
			->once()
			->andReturn([new MockEvent(1, '2014-06-01 12:00:00', '2014-06-01 18:00:00')]);

		$calendar->populate($eventRegistry, new \DateTime('2014-06-01 12:00:00'), new \DateTime('2014-06-30 23:59:59'));

		$this->assertEquals(1, $calendar->count());

		foreach($calendar as $event) {
			$this->assertEquals('2014-06-01 12:00:00', $event->getStartDate());
		}
	}

	public function testMultipleDayEventWithinDateRange()
	{
		$calendar = new Calendar();

		$eventRegistry = m::mock('\Plummer\Calendarful\RegistryInterface');
		$eventRegistry->shouldReceive('get')
			->once()
			->andReturn([new MockEvent(1, '2014-06-01 12:00:00', '2014-06-03 18:00:00')]);

		$calendar->populate($eventRegistry, new \DateTime('2014-06-01 12:00:00'), new \DateTime('2014-06-30 23:59:59'));

		$this->assertEquals(1, $calendar->count());

		foreach($calendar as $event) {
			$this->assertEquals('2014-06-01 12:00:00', $event->getStartDate());
		}
	}

	public function testSameDayEventStartDateOutsideDateRange()
	{
		$calendar = new Calendar();

		$eventRegistry = m::mock('\Plummer\Calendarful\RegistryInterface');
		$eventRegistry->shouldReceive('get')
			->once()
			->andReturn([new MockEvent(1, '2014-06-01 10:00:00', '2014-06-01 18:00:00')]);

		$calendar->populate($eventRegistry, new \DateTime('2014-06-01 12:00:00'), new \DateTime('2014-06-30 23:59:59'));

		$this->assertEquals(1, $calendar->count());

		foreach($calendar as $event) {
			$this->assertEquals('2014-06-01 10:00:00', $event->getStartDate());
		}
	}

	public function testMultipleDayEventStartDateOutsideDateRange()
	{
		$calendar = new Calendar();

		$eventRegistry = m::mock('\Plummer\Calendarful\RegistryInterface');
		$eventRegistry->shouldReceive('get')
			->once()
			->andReturn([new MockEvent(1, '2014-06-01 10:00:00', '2014-06-03 18:00:00')]);

		$calendar->populate($eventRegistry, new \DateTime('2014-06-01 12:00:00'), new \DateTime('2014-06-30 23:59:59'));

		$this->assertEquals(1, $calendar->count());

		foreach($calendar as $event) {
			$this->assertEquals('2014-06-01 10:00:00', $event->getStartDate());
		}
	}

	public function testSameDayEventEndDateOutsideDateRange()
	{
		$calendar = new Calendar();

		$eventRegistry = m::mock('\Plummer\Calendarful\RegistryInterface');
		$eventRegistry->shouldReceive('get')
			->once()
			->andReturn([new MockEvent(1, '2014-06-30 19:00:00', '2014-06-30 21:00:00')]);

		$calendar->populate($eventRegistry, new \DateTime('2014-06-01 12:00:00'), new \DateTime('2014-06-30 20:00:00'));

		$this->assertEquals(1, $calendar->count());

		foreach($calendar as $event) {
			$this->assertEquals('2014-06-30 19:00:00', $event->getStartDate());
		}
	}

	public function testMultipleDayEventEndDateOutsideDateRange()
	{
		$calendar = new Calendar();

		$eventRegistry = m::mock('\Plummer\Calendarful\RegistryInterface');
		$eventRegistry->shouldReceive('get')
			->once()
			->andReturn([new MockEvent(1, '2014-06-30 19:00:00', '2014-07-02 21:00:00')]);

		$calendar->populate($eventRegistry, new \DateTime('2014-06-01 12:00:00'), new \DateTime('2014-06-30 20:00:00'));

		$this->assertEquals(1, $calendar->count());

		foreach($calendar as $event) {
			$this->assertEquals('2014-06-30 19:00:00', $event->getStartDate());
		}
	}

	public function testEventOverlapsDateRange()
	{
		$calendar = new Calendar();

		$eventRegistry = m::mock('\Plummer\Calendarful\RegistryInterface');
		$eventRegistry->shouldReceive('get')
			->once()
			->andReturn([new MockEvent(1, '2014-06-01 00:00:00', '2014-06-30 23:59:59')]);

		$calendar->populate($eventRegistry, new \DateTime('2014-06-01 12:00:00'), new \DateTime('2014-06-30 23:00:00'));

		$this->assertEquals(1, $calendar->count());

		foreach($calendar as $event) {
			$this->assertEquals('2014-06-01 00:00:00', $event->getStartDate());
		}
	}

	public function testEventBeforeDateRange()
	{
		$calendar = new Calendar();

		$eventRegistry = m::mock('\Plummer\Calendarful\RegistryInterface');
		$eventRegistry->shouldReceive('get')
			->once()
			->andReturn([new MockEvent(1, '2014-05-31 22:00:00', '2014-06-01 03:00:00')]);

		$calendar->populate($eventRegistry, new \DateTime('2014-06-01 12:00:00'), new \DateTime('2014-06-30 20:00:00'));

		$this->assertEquals(0, $calendar->count());
	}

	public function testEventAfterDateRange()
	{
		$calendar = new Calendar();

		$eventRegistry = m::mock('\Plummer\Calendarful\RegistryInterface');
		$eventRegistry->shouldReceive('get')
			->once()
			->andReturn([new MockEvent(1, '2014-06-30 21:00:00', '2014-07-01 03:00:00')]);

		$calendar->populate($eventRegistry, new \DateTime('2014-06-01 12:00:00'), new \DateTime('2014-06-30 20:00:00'));

		$this->assertEquals(0, $calendar->count());
	}

	public function testEventLimit()
	{
		$calendar = new Calendar();

		$eventRegistry = m::mock('\Plummer\Calendarful\RegistryInterface');
		$eventRegistry->shouldReceive('get')
			->once()
			->andReturn([
				new MockEvent(1, '2014-06-01 12:00:00', '2014-06-01 18:00:00'),
				new MockEvent(2, '2014-06-01 12:00:00', '2014-06-01 18:00:00')
			]);

		$calendar->populate($eventRegistry, new \DateTime('2014-06-01 12:00:00'), new \DateTime('2014-06-30 20:00:00'), 1);

		$this->assertEquals(1, $calendar->count());
	}

	public function testEventSorting()
	{
		$calendar = new Calendar();

		$eventRegistry = m::mock('\Plummer\Calendarful\RegistryInterface');
		$eventRegistry->shouldReceive('get')
			->once()
			->andReturn([
				new MockEvent(1, '2014-06-02 10:00:00', '2014-06-02 14:00:00'),
				new MockEvent(2, '2014-06-01 15:00:00', '2014-06-01 16:00:00'),
				new MockEvent(3, '2014-06-01 23:00:00', '2014-06-02 03:30:00'),
				new MockEvent(4, '2014-06-01 12:00:00', '2014-06-01 18:00:00'),
				new MockEvent(5, '2014-06-02 10:00:00', '2014-06-02 13:30:00')
			]);

		$calendar->populate($eventRegistry, new \DateTime('2014-06-01 12:00:00'), new \DateTime('2014-06-30 20:00:00'), 1);

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

	public function testOverriddenEventRemoval()
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
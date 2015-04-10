<?php

namespace Plummer\Calendarful\Calendar;

use Mockery as m;
use Plummer\Calendarful\Mocks\MockEvent;
use Plummer\Calendarful\Mocks\MockRecurrentEvent;
use Plummer\Calendarful\Recurrence\RecurrenceFactory;

class CalendarTest extends \PHPUnit_Framework_TestCase
{
	public function tearDown()
	{
		m::close();
	}

	public function testSameFromAndToDate()
	{
		$calendar = new Calendar();

		$eventRegistry = m::mock('\Plummer\Calendarful\Event\EventRegistryInterface');
		$eventRegistry->shouldReceive('getEvents')
			->once()
			->andReturn(array(new MockEvent(1, '2014-06-01 12:00:00', '2014-06-01 18:00:00')));

		$eventRegistry->shouldReceive('getRecurrentEvents')
			->once()
			->andReturn(array());

		$calendar->populate($eventRegistry, new \DateTime('2014-06-01 12:00:00'), new \DateTime('2014-06-01 12:00:00'));

		$this->assertEquals(1, $calendar->count());

		foreach ($calendar as $event) {
			$this->assertEquals(new \DateTime('2014-06-01 12:00:00'), $event->getStartDate());
		}
	}

	/**
	 * @expectedException RangeException
	 */
	public function testFromDateLaterThanToDate()
	{
		$calendar = new Calendar();

		$eventRegistry = m::mock('\Plummer\Calendarful\Event\EventRegistryInterface');

		$calendar->populate($eventRegistry, new \DateTime('2014-06-01 12:00:00'), new \DateTime('2014-06-01 11:59:59'));
	}

	public function testSameDayEventWithinDateRange()
	{
		$calendar = new Calendar();

		$eventRegistry = m::mock('\Plummer\Calendarful\Event\EventRegistryInterface');
		$eventRegistry->shouldReceive('getEvents')
			->once()
			->andReturn(array(new MockEvent(1, '2014-06-01 12:00:00', '2014-06-01 18:00:00')));
		
		$eventRegistry->shouldReceive('getRecurrentEvents')
			->once()
			->andReturn(array());

		$calendar->populate($eventRegistry, new \DateTime('2014-06-01 12:00:00'), new \DateTime('2014-06-30 23:59:59'));

		$this->assertEquals(1, $calendar->count());

		foreach ($calendar as $event) {
			$this->assertEquals(new \DateTime('2014-06-01 12:00:00'), $event->getStartDate());
		}
	}

	public function testMultipleDayEventWithinDateRange()
	{
		$calendar = new Calendar();

		$eventRegistry = m::mock('\Plummer\Calendarful\Event\EventRegistryInterface');
		$eventRegistry->shouldReceive('getEvents')
			->once()
			->andReturn(array(new MockEvent(1, '2014-06-01 12:00:00', '2014-06-03 18:00:00')));
		
		$eventRegistry->shouldReceive('getRecurrentEvents')
			->once()
			->andReturn(array());

		$calendar->populate($eventRegistry, new \DateTime('2014-06-01 12:00:00'), new \DateTime('2014-06-30 23:59:59'));

		$this->assertEquals(1, $calendar->count());

		foreach ($calendar as $event) {
			$this->assertEquals(new \DateTime('2014-06-01 12:00:00'), $event->getStartDate());
		}
	}

	public function testSameDayEventStartDateOutsideDateRange()
	{
		$calendar = new Calendar();

		$eventRegistry = m::mock('\Plummer\Calendarful\Event\EventRegistryInterface');
		$eventRegistry->shouldReceive('getEvents')
			->once()
			->andReturn(array(new MockEvent(1, '2014-06-01 10:00:00', '2014-06-01 18:00:00')));
		
		$eventRegistry->shouldReceive('getRecurrentEvents')
			->once()
			->andReturn(array());

		$calendar->populate($eventRegistry, new \DateTime('2014-06-01 12:00:00'), new \DateTime('2014-06-30 23:59:59'));

		$this->assertEquals(1, $calendar->count());

		foreach ($calendar as $event) {
			$this->assertEquals(new \DateTime('2014-06-01 10:00:00'), $event->getStartDate());
		}
	}

	public function testMultipleDayEventStartDateOutsideDateRange()
	{
		$calendar = new Calendar();

		$eventRegistry = m::mock('\Plummer\Calendarful\Event\EventRegistryInterface');
		$eventRegistry->shouldReceive('getEvents')
			->once()
			->andReturn(array(new MockEvent(1, '2014-06-01 10:00:00', '2014-06-03 18:00:00')));
		
		$eventRegistry->shouldReceive('getRecurrentEvents')
			->once()
			->andReturn(array());

		$calendar->populate($eventRegistry, new \DateTime('2014-06-01 12:00:00'), new \DateTime('2014-06-30 23:59:59'));

		$this->assertEquals(1, $calendar->count());

		foreach ($calendar as $event) {
			$this->assertEquals(new \DateTime('2014-06-01 10:00:00'), $event->getStartDate());
		}
	}

	public function testSameDayEventEndDateOutsideDateRange()
	{
		$calendar = new Calendar();

		$eventRegistry = m::mock('\Plummer\Calendarful\Event\EventRegistryInterface');
		$eventRegistry->shouldReceive('getEvents')
			->once()
			->andReturn(array(new MockEvent(1, '2014-06-30 19:00:00', '2014-06-30 21:00:00')));
		
		$eventRegistry->shouldReceive('getRecurrentEvents')
			->once()
			->andReturn(array());

		$calendar->populate($eventRegistry, new \DateTime('2014-06-01 12:00:00'), new \DateTime('2014-06-30 20:00:00'));

		$this->assertEquals(1, $calendar->count());

		foreach ($calendar as $event) {
			$this->assertEquals(new \DateTime('2014-06-30 19:00:00'), $event->getStartDate());
		}
	}

	public function testMultipleDayEventEndDateOutsideDateRange()
	{
		$calendar = new Calendar();

		$eventRegistry = m::mock('\Plummer\Calendarful\Event\EventRegistryInterface');
		$eventRegistry->shouldReceive('getEvents')
			->once()
			->andReturn(array(new MockEvent(1, '2014-06-30 19:00:00', '2014-07-02 21:00:00')));
		
		$eventRegistry->shouldReceive('getRecurrentEvents')
			->once()
			->andReturn(array());

		$calendar->populate($eventRegistry, new \DateTime('2014-06-01 12:00:00'), new \DateTime('2014-06-30 20:00:00'));

		$this->assertEquals(1, $calendar->count());

		foreach ($calendar as $event) {
			$this->assertEquals(new \DateTime('2014-06-30 19:00:00'), $event->getStartDate());
		}
	}

	public function testEventOverlapsDateRange()
	{
		$calendar = new Calendar();

		$eventRegistry = m::mock('\Plummer\Calendarful\Event\EventRegistryInterface');
		$eventRegistry->shouldReceive('getEvents')
			->once()
			->andReturn(array(new MockEvent(1, '2014-06-01 00:00:00', '2014-06-30 23:59:59')));
		
		$eventRegistry->shouldReceive('getRecurrentEvents')
			->once()
			->andReturn(array());

		$calendar->populate($eventRegistry, new \DateTime('2014-06-01 12:00:00'), new \DateTime('2014-06-30 23:00:00'));

		$this->assertEquals(1, $calendar->count());

		foreach ($calendar as $event) {
			$this->assertEquals(new \DateTime('2014-06-01 00:00:00'), $event->getStartDate());
		}
	}

	public function testEventBeforeDateRange()
	{
		$calendar = new Calendar();

		$eventRegistry = m::mock('\Plummer\Calendarful\Event\EventRegistryInterface');
		$eventRegistry->shouldReceive('getEvents')
			->once()
			->andReturn(array(new MockEvent(1, '2014-05-31 22:00:00', '2014-06-01 03:00:00')));
		
		$eventRegistry->shouldReceive('getRecurrentEvents')
			->once()
			->andReturn(array());

		$calendar->populate($eventRegistry, new \DateTime('2014-06-01 12:00:00'), new \DateTime('2014-06-30 20:00:00'));

		$this->assertEquals(0, $calendar->count());
	}

	public function testEventAfterDateRange()
	{
		$calendar = new Calendar();

		$eventRegistry = m::mock('\Plummer\Calendarful\Event\EventRegistryInterface');
		$eventRegistry->shouldReceive('getEvents')
			->once()
			->andReturn(array(new MockEvent(1, '2014-06-30 21:00:00', '2014-07-01 03:00:00')));
		
		$eventRegistry->shouldReceive('getRecurrentEvents')
			->once()
			->andReturn(array());

		$calendar->populate($eventRegistry, new \DateTime('2014-06-01 12:00:00'), new \DateTime('2014-06-30 20:00:00'));

		$this->assertEquals(0, $calendar->count());
	}

	public function testEventLimit()
	{
		$calendar = new Calendar();

		$eventRegistry = m::mock('\Plummer\Calendarful\Event\EventRegistryInterface');
		$eventRegistry->shouldReceive('getEvents')
			->once()
			->andReturn(array(
				new MockEvent(1, '2014-06-01 12:00:00', '2014-06-01 18:00:00'),
				new MockEvent(2, '2014-06-01 12:00:00', '2014-06-01 18:00:00'),
			));
		
		$eventRegistry->shouldReceive('getRecurrentEvents')
			->once()
			->andReturn(array());

		$calendar->populate($eventRegistry, new \DateTime('2014-06-01 12:00:00'), new \DateTime('2014-06-30 20:00:00'), 1);

		$this->assertEquals(1, $calendar->count());
	}

	public function testEventSorting()
	{
		$calendar = new Calendar();

		$eventRegistry = m::mock('\Plummer\Calendarful\Event\EventRegistryInterface');
		$eventRegistry->shouldReceive('getEvents')
			->once()
			->andReturn(array(
				new MockEvent(1, '2014-06-02 10:00:00', '2014-06-02 14:00:00'),
				new MockEvent(2, '2014-06-01 15:00:00', '2014-06-01 16:00:00'),
				new MockEvent(3, '2014-06-01 23:00:00', '2014-06-02 03:30:00'),
				new MockEvent(4, '2014-06-01 12:00:00', '2014-06-01 18:00:00'),
				new MockEvent(5, '2014-06-02 10:00:00', '2014-06-02 13:30:00'),
			));
		
		$eventRegistry->shouldReceive('getRecurrentEvents')
			->once()
			->andReturn(array());

		$calendar->populate($eventRegistry, new \DateTime('2014-06-01 12:00:00'), new \DateTime('2014-06-30 20:00:00'), 1);

		$previous = null;

		foreach ($calendar as $event) {
			if (!$previous) {
				$previous = $event->getStartDate();
				continue;
			}

			$this->assertGreaterThanOrEqual($previous, $event->getStartDate());

			$previous = $event->getStartDate();
		}
	}

	public function testOverriddenDailyEventRemoval()
	{
		$recurrenceFactory = new RecurrenceFactory();
		$recurrenceFactory->addRecurrenceType('daily', 'Plummer\Calendarful\Recurrence\Type\Daily');

		$eventRegistry = m::mock('\Plummer\Calendarful\Event\EventRegistryInterface');
		$eventRegistry->shouldReceive('getEvents')
			->once()
			->andReturn(array(
				new MockEvent(2, '2014-06-15 18:00:00', '2014-06-15 19:00:00', 1, '2014-06-15 00:00:00')
			));
		
		$eventRegistry->shouldReceive('getRecurrentEvents')
			->once()
			->andReturn(array(
				new MockRecurrentEvent(1, '2014-06-01 00:00:00', '2014-06-01 01:00:00', null, null, 'daily')
			));

		$calendar = Calendar::create($recurrenceFactory)
			->populate($eventRegistry, new \DateTime('2014-06-01 12:00:00'), new \DateTime('2014-06-30 20:00:00'));

		$overrideExists = false;
		$overriddenExists = false;

		$this->assertEquals(29, $calendar->count());

		foreach ($calendar as $event) {
			if ($event->getStartDate() == new \DateTime('2014-06-15 18:00:00')) {
				$overrideExists = true;
			} elseif ($event->getStartDate() == new \DateTime('2014-06-15 00:00:00')) {
				$overriddenExists = true;
			}
		}

		$this->assertTrue($overrideExists && !$overriddenExists);
	}

	public function testOverriddenWeeklyEventRemoval()
	{
		$recurrenceFactory = new RecurrenceFactory();
		$recurrenceFactory->addRecurrenceType('weekly', 'Plummer\Calendarful\Recurrence\Type\Weekly');

		$eventRegistry = m::mock('\Plummer\Calendarful\Event\EventRegistryInterface');
		$eventRegistry->shouldReceive('getEvents')
			->once()
			->andReturn(array(
				new MockEvent(2, '2014-06-22 18:00:00', '2014-06-22 19:00:00', 1, '2014-06-22 00:00:00')
			));
		
		$eventRegistry->shouldReceive('getRecurrentEvents')
			->once()
			->andReturn(array(
				new MockRecurrentEvent(1, '2014-06-01 00:00:00', '2014-06-01 01:00:00', null, null, 'weekly')
			));

		$calendar = Calendar::create($recurrenceFactory)
			->populate($eventRegistry, new \DateTime('2014-06-01 12:00:00'), new \DateTime('2014-06-30 20:00:00'));

		$overrideExists = false;
		$overriddenExists = false;

		$this->assertEquals(4, $calendar->count());

		foreach ($calendar as $event) {
			if ($event->getStartDate() == new \DateTime('2014-06-22 18:00:00')) {
				$overrideExists = true;
			} elseif ($event->getStartDate() == new \DateTime('2014-06-22 00:00:00')) {
				$overriddenExists = true;
			}
		}

		$this->assertTrue($overrideExists && !$overriddenExists);
	}

	public function testOverriddenMonthlyDateEventRemoval()
	{
		$recurrenceFactory = new RecurrenceFactory();
		$recurrenceFactory->addRecurrenceType('monthly', 'Plummer\Calendarful\Recurrence\Type\MonthlyDate');

		$eventRegistry = m::mock('\Plummer\Calendarful\Event\EventRegistryInterface');
		$eventRegistry->shouldReceive('getEvents')
			->once()
			->andReturn(array(
				new MockEvent(2, '2014-08-31 18:00:00', '2014-08-31 19:00:00', 1, '2014-08-31 00:00:00')
			));
		
		$eventRegistry->shouldReceive('getRecurrentEvents')
			->once()
			->andReturn(array(
				new MockRecurrentEvent(1, '2014-05-31 00:00:00', '2014-05-31 01:00:00', null, null, 'monthly')
			));

		$calendar = Calendar::create($recurrenceFactory)
			->populate($eventRegistry, new \DateTime('2014-05-01 12:00:00'), new \DateTime('2014-10-31 20:00:00'));

		$overrideExists = false;
		$overriddenExists = false;

		$this->assertEquals(4, $calendar->count());

		foreach ($calendar as $event) {
			if ($event->getStartDate() == new \DateTime('2014-08-31 18:00:00')) {
				$overrideExists = true;
			} elseif ($event->getStartDate() == new \DateTime('2014-08-31 00:00:00')) {
				$overriddenExists = true;
			}
		}

		$this->assertTrue($overrideExists && !$overriddenExists);
	}
}

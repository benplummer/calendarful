<?php

namespace Plummer\Calendarful\Recurrence\Type;

use \Mockery as m;
use Plummer\Calendarful\Mocks\MockEvent;

class DailyTest extends \PHPUnit_Framework_TestCase
{
	public function tearDown()
	{
		m::close();
	}

	public function testOnlyDailyEventsUsed()
	{
		$dailyRecurrenceType = new Daily();

		$mockEvents = array(
			new MockEvent(1, '2014-12-01 12:00:00', '2014-12-01 13:00:00', 'daily'),
			new MockEvent(2, '2014-12-02 18:00:00', '2014-12-02 19:00:00')
		);

		$fromDate = new \DateTime('2014-12-01 00:00:00');
		$toDate = new \DateTime('2014-12-03 23:59:59');

		$generatedDailyOccurrences = $dailyRecurrenceType->generateOccurrences($mockEvents, $fromDate, $toDate);

		foreach($generatedDailyOccurrences as $generatedDailyOccurrence) {
			$this->assertEquals(1, $generatedDailyOccurrence->getId());
			$this->assertNotEquals(2, $generatedDailyOccurrence->getId());
		}
	}

	public function testDailyEventsLimit()
	{
		$dailyRecurrenceType = new Daily();

		$mockEvents = array(
			new MockEvent(1, '2014-12-01 12:00:00', '2014-12-01 13:00:00', 'daily'),
		);

		$fromDate = new \DateTime('2014-12-01 00:00:00');
		$toDate = new \DateTime('2014-12-05 23:59:59');

		$generatedDailyOccurrences = $dailyRecurrenceType->generateOccurrences($mockEvents, $fromDate, $toDate, 2);

		$this->assertEquals(2, count($generatedDailyOccurrences));
	}

	public function testDailyEventsWithUntilDate()
	{
		$dailyRecurrenceType = new Daily();

		$mockEvents = array(
			new MockEvent(1, '2014-12-01 12:00:00', '2014-12-01 13:00:00', 'daily', '2014-12-03 23:59:59'),
		);

		$fromDate = new \DateTime('2014-12-01 00:00:00');
		$toDate = new \DateTime('2014-12-05 23:59:59');

		$generatedDailyOccurrences = $dailyRecurrenceType->generateOccurrences($mockEvents, $fromDate, $toDate);

		$this->assertEquals(3, count($generatedDailyOccurrences));

		foreach($generatedDailyOccurrences as $generatedDailyOccurrence) {
			$this->assertLessThanOrEqual('2014-12-03 23:59:59', $generatedDailyOccurrence->getStartDate());
		}
	}

	public function testDailyEventOccurrenceDates()
	{
		$dailyRecurrenceType = new Daily();

		$mockEvents = array(
			new MockEvent(1, '2014-12-01 12:00:00', '2014-12-01 13:00:00', 'daily'),
		);

		$fromDate = new \DateTime('2014-12-01 00:00:00');
		$toDate = new \DateTime('2014-12-05 23:59:59');

		$generatedDailyOccurrences = $dailyRecurrenceType->generateOccurrences($mockEvents, $fromDate, $toDate);

		$previousDate = null;
		$previousTime = null;

		foreach($generatedDailyOccurrences as $generatedDailyOccurrence) {
			if(!($previousDate || $previousTime)) {
				list($previousDate, $previousTime) = explode(' ', $generatedDailyOccurrence->getStartDate());
				continue;
			}

			list($currentDate, $currentTime) = explode(' ', $generatedDailyOccurrence->getStartDate());

			$this->assertGreaterThan($previousDate, $currentDate);
			$this->assertEquals($previousTime, $currentTime);

			$previousDate = $currentDate;
			$previousTime = $currentTime;
		}

		$this->assertCount(5, $generatedDailyOccurrences);
	}
}
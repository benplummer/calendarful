<?php

namespace Plummer\Calendarful\Recurrence\Type;

use \Mockery as m;
use Plummer\Calendarful\Mocks\MockEvent;

class WeeklyTest extends \PHPUnit_Framework_TestCase
{
	public function tearDown()
	{
		m::close();
	}

	public function testOnlyWeeklyEventsUsed()
	{
		$weeklyRecurrenceType = new Weekly();

		$mockEvents = array(
			new MockEvent(1, '2014-12-01 12:00:00', '2014-12-01 13:00:00', 'weekly'),
			new MockEvent(2, '2014-12-02 18:00:00', '2014-12-02 19:00:00')
		);

		$fromDate = new \DateTime('2014-12-01 00:00:00');
		$toDate = new \DateTime('2014-12-31 23:59:59');

		$generatedWeeklyOccurrences = $weeklyRecurrenceType->generateOccurrences($mockEvents, $fromDate, $toDate);

		foreach($generatedWeeklyOccurrences as $generatedWeeklyOccurrence) {
			$this->assertEquals(1, $generatedWeeklyOccurrence->getId());
			$this->assertNotEquals(2, $generatedWeeklyOccurrence->getId());
		}
	}

	public function testWeeklyEventsLimit()
	{
		$weeklyRecurrenceType = new Weekly();

		$mockEvents = array(
			new MockEvent(1, '2014-12-01 12:00:00', '2014-12-01 13:00:00', 'weekly'),
		);

		$fromDate = new \DateTime('2014-12-01 00:00:00');
		$toDate = new \DateTime('2014-12-31 23:59:59');

		$generatedWeeklyOccurrences = $weeklyRecurrenceType->generateOccurrences($mockEvents, $fromDate, $toDate, 2);

		$this->assertCount(2, $generatedWeeklyOccurrences);
	}

	public function testWeeklyEventsMaxEndDate()
	{
		$weeklyRecurrenceType = new Weekly();

		$mockEvents = array(
			new MockEvent(1, '2014-12-01 12:00:00', '2014-12-01 13:00:00', 'weekly'),
		);

		$fromDate = new \DateTime('2014-12-01 00:00:00');
		$toDate = new \DateTime('2020-12-01 00:00:00');

		$generatedWeeklyOccurrences = $weeklyRecurrenceType->generateOccurrences($mockEvents, $fromDate, $toDate);

		$lastOccurrence = end($generatedWeeklyOccurrences);

		$this->assertEquals('2019-11-25 12:00:00', $lastOccurrence->getStartDate());
	}

	public function testWeeklyEventsWithUntilDate()
	{
		$weeklyRecurrenceType = new Weekly();

		$mockEvents = array(
			new MockEvent(1, '2014-12-01 12:00:00', '2014-12-01 13:00:00', 'weekly', '2014-12-31 23:59:59'),
		);

		$fromDate = new \DateTime('2014-12-01 00:00:00');
		$toDate = new \DateTime('2014-12-20 23:59:59');

		$generatedWeeklyOccurrences = $weeklyRecurrenceType->generateOccurrences($mockEvents, $fromDate, $toDate);

		$this->assertEquals(3, count($generatedWeeklyOccurrences));

		foreach($generatedWeeklyOccurrences as $generatedWeeklyOccurrence) {
			$this->assertLessThanOrEqual('2014-12-20 23:59:59', $generatedWeeklyOccurrence->getStartDate());
		}
	}

	public function testWeeklyEventOccurrenceDates()
	{
		$weeklyRecurrenceType = new Weekly();

		$mockEvents = array(
			new MockEvent(1, '2014-12-01 12:00:00', '2014-12-01 13:00:00', 'weekly'),
		);

		$fromDate = new \DateTime('2014-12-01 00:00:00');
		$toDate = new \DateTime('2014-12-31 23:59:59');

		$generatedWeeklyOccurrences = $weeklyRecurrenceType->generateOccurrences($mockEvents, $fromDate, $toDate);

		$previousDate = null;
		$previousTime = null;

		foreach($generatedWeeklyOccurrences as $generatedWeeklyOccurrence) {
			if(!($previousDate || $previousTime)) {
				list($previousDate, $previousTime) = explode(' ', $generatedWeeklyOccurrence->getStartDate());
				continue;
			}

			list($currentDate, $currentTime) = explode(' ', $generatedWeeklyOccurrence->getStartDate());

			$this->assertGreaterThan($previousDate, $currentDate);
			$this->assertEquals($previousTime, $currentTime);

			$previousDate = $currentDate;
			$previousTime = $currentTime;
		}

		$this->assertCount(5, $generatedWeeklyOccurrences);
	}
}
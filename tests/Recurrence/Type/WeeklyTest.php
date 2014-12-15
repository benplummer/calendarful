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
			new MockEvent(1, '2014-06-01 00:00:00', '2014-06-01 01:00:00', 'weekly'),
			new MockEvent(2, '2014-06-02 18:00:00', '2014-06-02 19:00:00')
		);

		$fromDate = new \DateTime('2014-06-01 00:00:00');
		$toDate = new \DateTime('2014-06-30 23:59:59');

		$generatedWeeklyOccurrences = $weeklyRecurrenceType->generateOccurrences($mockEvents, $fromDate, $toDate);

		foreach($generatedWeeklyOccurrences as $generatedWeeklyOccurrence) {
			$this->assertEquals(1, $generatedWeeklyOccurrence->getId());
			$this->assertNotEquals(2, $generatedWeeklyOccurrence->getId());
		}
	}

	public function testOneDayWeeklyEventStartingWithinDateRange()
	{
		$weeklyRecurrenceType = new Weekly();

		$mockEvents = array(
			new MockEvent(1, '2014-06-01 00:00:00', '2014-06-01 02:00:00', 'weekly')
		);

		$fromDate = new \DateTime('2014-06-01 00:00:00');
		$toDate = new \DateTime('2014-06-30 23:59:59');

		$generatedWeeklyOccurrences = $weeklyRecurrenceType->generateOccurrences($mockEvents, $fromDate, $toDate);

		$this->assertCount(5, $generatedWeeklyOccurrences);
	}

	public function testOneDayWeeklyEventStartingWithinDateRangeWithUntil()
	{
		$weeklyRecurrenceType = new Weekly();

		$mockEvents = array(
			new MockEvent(1, '2014-06-01 00:00:00', '2014-06-01 02:00:00', 'weekly', '2014-06-15 23:59:59')
		);

		$fromDate = new \DateTime('2014-06-01 00:00:00');
		$toDate = new \DateTime('2014-06-30 23:59:59');

		$generatedWeeklyOccurrences = $weeklyRecurrenceType->generateOccurrences($mockEvents, $fromDate, $toDate);

		$this->assertCount(3, $generatedWeeklyOccurrences);
	}

	public function testMultipleDayWeeklyEventStartingWithinDateRange()
	{
		$weeklyRecurrenceType = new Weekly();

		$mockEvents = array(
			new MockEvent(1, '2014-06-01 00:00:00', '2014-06-03 02:00:00', 'weekly')
		);

		$fromDate = new \DateTime('2014-06-01 00:00:00');
		$toDate = new \DateTime('2014-06-30 23:59:59');

		$generatedWeeklyOccurrences = $weeklyRecurrenceType->generateOccurrences($mockEvents, $fromDate, $toDate);

		$this->assertCount(5, $generatedWeeklyOccurrences);
	}

	public function testMultipleDayWeeklyEventStartingWithinDateRangeWithUntil()
	{
		$weeklyRecurrenceType = new Weekly();

		$mockEvents = array(
			new MockEvent(1, '2014-06-01 00:00:00', '2014-06-03 02:00:00', 'weekly', '2014-06-15 23:59:59')
		);

		$fromDate = new \DateTime('2014-06-01 00:00:00');
		$toDate = new \DateTime('2014-06-30 23:59:59');

		$generatedWeeklyOccurrences = $weeklyRecurrenceType->generateOccurrences($mockEvents, $fromDate, $toDate);

		$this->assertCount(3, $generatedWeeklyOccurrences);
	}

	public function testOneDayWeeklyEventStartingBeforeDateRange()
	{
		$weeklyRecurrenceType = new Weekly();

		$mockEvents = array(
			new MockEvent(1, '2014-06-01 00:00:00', '2014-06-01 02:00:00', 'weekly')
		);

		$fromDate = new \DateTime('2014-05-20 00:00:00');
		$toDate = new \DateTime('2014-06-30 23:59:59');

		$generatedWeeklyOccurrences = $weeklyRecurrenceType->generateOccurrences($mockEvents, $fromDate, $toDate);

		$this->assertCount(5, $generatedWeeklyOccurrences);
	}

	public function testMultipleDayWeeklyEventStartingBeforeDateRange()
	{
		$weeklyRecurrenceType = new Weekly();

		$mockEvents = array(
			new MockEvent(1, '2014-06-01 00:00:00', '2014-06-03 02:00:00', 'weekly')
		);

		$fromDate = new \DateTime('2014-05-20 00:00:00');
		$toDate = new \DateTime('2014-06-30 23:59:59');

		$generatedWeeklyOccurrences = $weeklyRecurrenceType->generateOccurrences($mockEvents, $fromDate, $toDate);

		$this->assertCount(5, $generatedWeeklyOccurrences);
	}

	public function testOneDayWeeklyEventStartingBeforeDateRangeWithUntil()
	{
		$weeklyRecurrenceType = new Weekly();

		$mockEvents = array(
			new MockEvent(1, '2014-06-01 00:00:00', '2014-06-01 02:00:00', 'weekly', '2014-06-15 23:59:59')
		);

		$fromDate = new \DateTime('2014-05-20 00:00:00');
		$toDate = new \DateTime('2014-06-30 23:59:59');

		$generatedWeeklyOccurrences = $weeklyRecurrenceType->generateOccurrences($mockEvents, $fromDate, $toDate);

		$this->assertCount(3, $generatedWeeklyOccurrences);
	}

	public function testMultipleDayWeeklyEventStartingBeforeDateRangeWithUntil()
	{
		$weeklyRecurrenceType = new Weekly();

		$mockEvents = array(
			new MockEvent(1, '2014-06-01 00:00:00', '2014-06-03 02:00:00', 'weekly', '2014-06-15 23:59:59')
		);

		$fromDate = new \DateTime('2014-05-20 00:00:00');
		$toDate = new \DateTime('2014-06-30 23:59:59');

		$generatedWeeklyOccurrences = $weeklyRecurrenceType->generateOccurrences($mockEvents, $fromDate, $toDate);

		$this->assertCount(3, $generatedWeeklyOccurrences);
	}

	public function testWeeklyEventsLimit()
	{
		$weeklyRecurrenceType = new Weekly();

		$mockEvents = array(
			new MockEvent(1, '2014-06-01 00:00:00', '2014-06-01 01:00:00', 'weekly'),
		);

		$fromDate = new \DateTime('2014-06-01 00:00:00');
		$toDate = new \DateTime('2014-06-30 23:59:59');

		$generatedWeeklyOccurrences = $weeklyRecurrenceType->generateOccurrences($mockEvents, $fromDate, $toDate, 2);

		$this->assertCount(2, $generatedWeeklyOccurrences);
	}

	public function testWeeklyEventsMaxEndDate()
	{
		$weeklyRecurrenceType = new Weekly();

		$mockEvents = array(
			new MockEvent(1, '2014-06-01 00:00:00', '2014-06-01 01:00:00', 'weekly'),
		);

		$fromDate = new \DateTime('2014-06-01 00:00:00');
		$toDate = new \DateTime('2020-06-01 00:00:00');

		$generatedWeeklyOccurrences = $weeklyRecurrenceType->generateOccurrences($mockEvents, $fromDate, $toDate);

		$lastOccurrence = end($generatedWeeklyOccurrences);

		$this->assertEquals('2019-05-26 00:00:00', $lastOccurrence->getStartDate());
	}
}
<?php

namespace Plummer\Calendarful\Recurrence\Type;

use Mockery as m;
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
			new MockEvent(1, '2014-06-01 12:00:00', '2014-06-01 13:00:00', 'daily'),
			new MockEvent(2, '2014-06-02 18:00:00', '2014-06-02 19:00:00'),
		);

		$fromDate = new \DateTime('2014-06-01 00:00:00');
		$toDate = new \DateTime('2014-06-30 23:59:59');

		$generatedDailyOccurrences = $dailyRecurrenceType->generateOccurrences($mockEvents, $fromDate, $toDate);

		foreach ($generatedDailyOccurrences as $generatedDailyOccurrence) {
			$this->assertEquals(1, $generatedDailyOccurrence->getId());
			$this->assertNotEquals(2, $generatedDailyOccurrence->getId());
		}
	}

	public function testOneDayDailyEventStartingWithinDateRange()
	{
		$dailyRecurrenceType = new Daily();

		$mockEvents = array(
			new MockEvent(1, '2014-06-01 00:00:00', '2014-06-01 02:00:00', 'daily'),
		);

		$fromDate = new \DateTime('2014-06-01 00:00:00');
		$toDate = new \DateTime('2014-06-30 23:59:59');

		$generatedDailyOccurrences = $dailyRecurrenceType->generateOccurrences($mockEvents, $fromDate, $toDate);

		$this->assertCount(30, $generatedDailyOccurrences);
	}

	public function testOneDayDailyEventStartingWithinDateRangeWithUntil()
	{
		$dailyRecurrenceType = new Daily();

		$mockEvents = array(
			new MockEvent(1, '2014-06-01 00:00:00', '2014-06-01 02:00:00', 'daily', '2014-06-15 23:59:59'),
		);

		$fromDate = new \DateTime('2014-06-01 00:00:00');
		$toDate = new \DateTime('2014-06-30 23:59:59');

		$generatedDailyOccurrences = $dailyRecurrenceType->generateOccurrences($mockEvents, $fromDate, $toDate);

		$this->assertCount(15, $generatedDailyOccurrences);
	}

	public function testMultipleDayDailyEventStartingWithinDateRange()
	{
		$dailyRecurrenceType = new Daily();

		$mockEvents = array(
			new MockEvent(1, '2014-06-01 00:00:00', '2014-06-03 02:00:00', 'daily'),
		);

		$fromDate = new \DateTime('2014-06-01 00:00:00');
		$toDate = new \DateTime('2014-06-30 23:59:59');

		$generatedDailyOccurrences = $dailyRecurrenceType->generateOccurrences($mockEvents, $fromDate, $toDate);

		$this->assertCount(30, $generatedDailyOccurrences);
	}

	public function testMultipleDayDailyEventStartingWithinDateRangeWithUntil()
	{
		$dailyRecurrenceType = new Daily();

		$mockEvents = array(
			new MockEvent(1, '2014-06-01 00:00:00', '2014-06-03 02:00:00', 'daily', '2014-06-15 23:59:59'),
		);

		$fromDate = new \DateTime('2014-06-01 00:00:00');
		$toDate = new \DateTime('2014-06-30 23:59:59');

		$generatedDailyOccurrences = $dailyRecurrenceType->generateOccurrences($mockEvents, $fromDate, $toDate);

		$this->assertCount(15, $generatedDailyOccurrences);
	}

	public function testOneDayDailyEventStartingBeforeDateRange()
	{
		$dailyRecurrenceType = new Daily();

		$mockEvents = array(
			new MockEvent(1, '2014-06-01 00:00:00', '2014-06-01 02:00:00', 'daily'),
		);

		$fromDate = new \DateTime('2014-05-20 00:00:00');
		$toDate = new \DateTime('2014-06-30 23:59:59');

		$generatedDailyOccurrences = $dailyRecurrenceType->generateOccurrences($mockEvents, $fromDate, $toDate);

		$this->assertCount(30, $generatedDailyOccurrences);
	}

	public function testMultipleDayDailyEventStartingBeforeDateRange()
	{
		$dailyRecurrenceType = new Daily();

		$mockEvents = array(
			new MockEvent(1, '2014-06-01 00:00:00', '2014-06-03 02:00:00', 'daily'),
		);

		$fromDate = new \DateTime('2014-05-20 00:00:00');
		$toDate = new \DateTime('2014-06-30 23:59:59');

		$generatedDailyOccurrences = $dailyRecurrenceType->generateOccurrences($mockEvents, $fromDate, $toDate);

		$this->assertCount(30, $generatedDailyOccurrences);
	}

	public function testOneDayDailyEventStartingBeforeDateRangeWithUntil()
	{
		$dailyRecurrenceType = new Daily();

		$mockEvents = array(
			new MockEvent(1, '2014-06-01 00:00:00', '2014-06-01 02:00:00', 'daily', '2014-06-15 23:59:59'),
		);

		$fromDate = new \DateTime('2014-05-20 00:00:00');
		$toDate = new \DateTime('2014-06-30 23:59:59');

		$generatedDailyOccurrences = $dailyRecurrenceType->generateOccurrences($mockEvents, $fromDate, $toDate);

		$this->assertCount(15, $generatedDailyOccurrences);
	}

	public function testMultipleDayDailyEventStartingBeforeDateRangeWithUntil()
	{
		$dailyRecurrenceType = new Daily();

		$mockEvents = array(
			new MockEvent(1, '2014-06-01 00:00:00', '2014-06-03 02:00:00', 'daily', '2014-06-15 23:59:59'),
		);

		$fromDate = new \DateTime('2014-05-20 00:00:00');
		$toDate = new \DateTime('2014-06-30 23:59:59');

		$generatedDailyOccurrences = $dailyRecurrenceType->generateOccurrences($mockEvents, $fromDate, $toDate);

		$this->assertCount(15, $generatedDailyOccurrences);
	}

	public function testDailyEventsLimit()
	{
		$dailyRecurrenceType = new Daily();

		$mockEvents = array(
			new MockEvent(1, '2014-06-01 00:00:00', '2014-06-01 01:00:00', 'daily'),
		);

		$fromDate = new \DateTime('2014-06-01 00:00:00');
		$toDate = new \DateTime('2014-06-30 23:59:59');

		$generatedDailyOccurrences = $dailyRecurrenceType->generateOccurrences($mockEvents, $fromDate, $toDate, 2);

		$this->assertCount(2, $generatedDailyOccurrences);
	}

	public function testDailyEventsMaxEndDate()
	{
		$dailyRecurrenceType = new Daily();

		$mockEvents = array(
			new MockEvent(1, '2014-06-01 00:00:00', '2014-06-01 01:00:00', 'daily'),
		);

		$fromDate = new \DateTime('2014-06-01 00:00:00');
		$toDate = new \DateTime('2016-06-01 00:00:00');

		$generatedDailyOccurrences = $dailyRecurrenceType->generateOccurrences($mockEvents, $fromDate, $toDate);

		$lastOccurrence = end($generatedDailyOccurrences);

		$this->assertEquals(new \DateTime('2015-06-01 00:00:00'), $lastOccurrence->getStartDate());
	}
}

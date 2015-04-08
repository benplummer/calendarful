<?php

namespace Plummer\Calendarful\Recurrence\Type;

use Mockery as m;
use Plummer\Calendarful\Mocks\MockEvent;

class MonthlyDateTest extends \PHPUnit_Framework_TestCase
{
	public function tearDown()
	{
		m::close();
	}

	public function testOnlyMonthlyDateEventsUsed()
	{
		$monthlyDateRecurrenceType = new MonthlyDate();

		$mockEvents = array(
			new MockEvent(1, '2014-06-01 00:00:00', '2014-06-01 01:00:00', 'monthly'),
			new MockEvent(2, '2014-12-02 18:00:00', '2014-12-02 19:00:00'),
		);

		$fromDate = new \DateTime('2014-06-01 00:00:00');
		$toDate = new \DateTime('2014-12-31 23:59:59');

		$generatedMonthlyDateOccurrences = $monthlyDateRecurrenceType->generateOccurrences($mockEvents, $fromDate, $toDate);

		foreach ($generatedMonthlyDateOccurrences as $generatedMonthlyDateOccurrence) {
			$this->assertEquals(1, $generatedMonthlyDateOccurrence->getId());
			$this->assertNotEquals(2, $generatedMonthlyDateOccurrence->getId());
		}
	}

	public function testOneDayMonthlyDateEventStartingWithinDateRange()
	{
		$monthlyDateRecurrenceType = new MonthlyDate();

		$mockEvents = array(
			new MockEvent(1, '2014-05-31 00:00:00', '2014-05-31 02:00:00', 'monthly'),
		);

		$fromDate = new \DateTime('2014-05-01 00:00:00');
		$toDate = new \DateTime('2014-12-31 23:59:59');

		$generatedMonthlyOccurrences = $monthlyDateRecurrenceType->generateOccurrences($mockEvents, $fromDate, $toDate);

		$this->assertCount(5, $generatedMonthlyOccurrences);
	}

	public function testOneDayMonthlyDateEventStartingWithinDateRangeWithUntil()
	{
		$monthlyDateRecurrenceType = new MonthlyDate();

		$mockEvents = array(
			new MockEvent(1, '2014-05-31 00:00:00', '2014-05-31 02:00:00', 'monthly', '2014-08-31 23:59:59'),
		);

		$fromDate = new \DateTime('2014-05-01 00:00:00');
		$toDate = new \DateTime('2014-12-31 23:59:59');

		$generatedMonthlyOccurrences = $monthlyDateRecurrenceType->generateOccurrences($mockEvents, $fromDate, $toDate);

		$this->assertCount(3, $generatedMonthlyOccurrences);
	}

	public function testMultipleDayMonthlyDateEventStartingWithinDateRange()
	{
		$monthlyDateRecurrenceType = new MonthlyDate();

		$mockEvents = array(
			new MockEvent(1, '2014-05-31 00:00:00', '2014-06-01 02:00:00', 'monthly'),
		);

		$fromDate = new \DateTime('2014-05-01 00:00:00');
		$toDate = new \DateTime('2014-12-31 23:59:59');

		$generatedMonthlyOccurrences = $monthlyDateRecurrenceType->generateOccurrences($mockEvents, $fromDate, $toDate);

		$this->assertCount(5, $generatedMonthlyOccurrences);
	}

	public function testMultipleDayMonthlyDateEventStartingWithinDateRangeWithUntil()
	{
		$monthlyDateRecurrenceType = new MonthlyDate();

		$mockEvents = array(
			new MockEvent(1, '2014-05-31 00:00:00', '2014-06-01 02:00:00', 'monthly', '2014-08-31 23:59:59'),
		);

		$fromDate = new \DateTime('2014-05-01 00:00:00');
		$toDate = new \DateTime('2014-12-31 23:59:59');

		$generatedMonthlyOccurrences = $monthlyDateRecurrenceType->generateOccurrences($mockEvents, $fromDate, $toDate);

		$this->assertCount(3, $generatedMonthlyOccurrences);
	}

	public function testOneDayMonthlyDateEventStartingBeforeDateRange()
	{
		$monthlyDateRecurrenceType = new MonthlyDate();

		$mockEvents = array(
			new MockEvent(1, '2014-03-31 00:00:00', '2014-03-31 02:00:00', 'monthly'),
		);

		$fromDate = new \DateTime('2014-05-01 00:00:00');
		$toDate = new \DateTime('2014-12-31 23:59:59');

		$generatedMonthlyOccurrences = $monthlyDateRecurrenceType->generateOccurrences($mockEvents, $fromDate, $toDate);

		$this->assertCount(5, $generatedMonthlyOccurrences);
	}

	public function testMultipleDayMonthlyDateEventStartingBeforeDateRange()
	{
		$monthlyDateRecurrenceType = new MonthlyDate();

		$mockEvents = array(
			new MockEvent(1, '2014-03-31 00:00:00', '2014-04-01 02:00:00', 'monthly'),
		);

		$fromDate = new \DateTime('2014-05-01 00:00:00');
		$toDate = new \DateTime('2014-12-31 23:59:59');

		$generatedMonthlyOccurrences = $monthlyDateRecurrenceType->generateOccurrences($mockEvents, $fromDate, $toDate);

		$this->assertCount(5, $generatedMonthlyOccurrences);
	}

	public function testOneDayMonthlyDateEventStartingBeforeDateRangeWithUntil()
	{
		$monthlyDateRecurrenceType = new MonthlyDate();

		$mockEvents = array(
			new MockEvent(1, '2014-03-31 00:00:00', '2014-03-31 02:00:00', 'monthly', '2014-08-31 23:59:59'),
		);

		$fromDate = new \DateTime('2014-05-01 00:00:00');
		$toDate = new \DateTime('2014-12-31 23:59:59');

		$generatedMonthlyOccurrences = $monthlyDateRecurrenceType->generateOccurrences($mockEvents, $fromDate, $toDate);

		$this->assertCount(3, $generatedMonthlyOccurrences);
	}

	public function testMultipleDayMonthlyDateEventStartingBeforeDateRangeWithUntil()
	{
		$monthlyDateRecurrenceType = new MonthlyDate();

		$mockEvents = array(
			new MockEvent(1, '2014-03-31 00:00:00', '2014-04-01 02:00:00', 'monthly', '2014-08-31 23:59:59'),
		);

		$fromDate = new \DateTime('2014-05-01 00:00:00');
		$toDate = new \DateTime('2014-12-31 23:59:59');

		$generatedMonthlyOccurrences = $monthlyDateRecurrenceType->generateOccurrences($mockEvents, $fromDate, $toDate);

		$this->assertCount(3, $generatedMonthlyOccurrences);
	}

	public function testMonthlyDateEventsLimit()
	{
		$monthlyDateRecurrenceType = new MonthlyDate();

		$mockEvents = array(
			new MockEvent(1, '2014-06-01 00:00:00', '2014-06-01 01:00:00', 'monthly'),
		);

		$fromDate = new \DateTime('2014-06-01 00:00:00');
		$toDate = new \DateTime('2014-12-31 23:59:59');

		$generatedMonthlyDateOccurrences = $monthlyDateRecurrenceType->generateOccurrences($mockEvents, $fromDate, $toDate, 2);

		$this->assertCount(2, $generatedMonthlyDateOccurrences);
	}

	public function testMonthlyDateEventsMaxEndDate()
	{
		$monthlyDateRecurrenceType = new MonthlyDate();

		$mockEvents = array(
			new MockEvent(1, '2014-06-01 00:00:00', '2014-06-01 01:00:00', 'monthly'),
		);

		$fromDate = new \DateTime('2014-06-01 00:00:00');
		$toDate = new \DateTime('2040-06-01 00:00:00');

		$generatedMonthlyDateOccurrences = $monthlyDateRecurrenceType->generateOccurrences($mockEvents, $fromDate, $toDate);

		$lastOccurrence = end($generatedMonthlyDateOccurrences);

		$this->assertEquals(new \DateTime('2039-06-01 00:00:00'), $lastOccurrence->getStartDate());
	}
}

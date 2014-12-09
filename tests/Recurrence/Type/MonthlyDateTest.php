<?php

namespace Plummer\Calendarful\Recurrence\Type;

use \Mockery as m;
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
			new MockEvent(1, '2014-06-01 12:00:00', '2014-06-01 13:00:00', 'monthly'),
			new MockEvent(2, '2014-12-02 18:00:00', '2014-12-02 19:00:00')
		);

		$fromDate = new \DateTime('2014-06-01 00:00:00');
		$toDate = new \DateTime('2014-12-31 23:59:59');

		$generatedMonthlyDateOccurrences = $monthlyDateRecurrenceType->generateOccurrences($mockEvents, $fromDate, $toDate);

		foreach($generatedMonthlyDateOccurrences as $generatedMonthlyDateOccurrence) {
			$this->assertEquals(1, $generatedMonthlyDateOccurrence->getId());
			$this->assertNotEquals(2, $generatedMonthlyDateOccurrence->getId());
		}
	}

	public function testMonthlyDateEventsLimit()
	{
		$monthlyDateRecurrenceType = new MonthlyDate();

		$mockEvents = array(
			new MockEvent(1, '2014-06-01 12:00:00', '2014-06-01 13:00:00', 'monthly')
		);

		$fromDate = new \DateTime('2014-06-01 00:00:00');
		$toDate = new \DateTime('2014-12-31 23:59:59');

		$generatedMonthlyDateOccurrences = $monthlyDateRecurrenceType->generateOccurrences($mockEvents, $fromDate, $toDate, 2);

		$this->assertCount(2, $generatedMonthlyDateOccurrences);
	}

	public function testMonthlyDateWithUntilDate()
	{
		$monthlyDateRecurrenceType = new MonthlyDate();

		$mockEvents = array(
			new MockEvent(1, '2014-06-01 12:00:00', '2014-06-01 13:00:00', 'monthly', '2014-09-30 23:59:59')
		);

		$fromDate = new \DateTime('2014-06-01 00:00:00');
		$toDate = new \DateTime('2014-12-31 23:59:59');

		$generatedMonthlyDateOccurrences = $monthlyDateRecurrenceType->generateOccurrences($mockEvents, $fromDate, $toDate);

		$this->assertCount(4, $generatedMonthlyDateOccurrences);

		foreach($generatedMonthlyDateOccurrences as $generatedMonthlyDateOccurrence) {
			$this->assertLessThanOrEqual('2014-09-30 23:59:59', $generatedMonthlyDateOccurrence->getStartDate());
		}
	}

	public function testMonthlyDateEventOccurrenceDates()
	{
		$monthlyDateRecurrenceType = new MonthlyDate();

		$mockEvents = array(
			new MockEvent(1, '2014-06-15 12:00:00', '2014-06-15 13:00:00', 'monthly')
		);

		$fromDate = new \DateTime('2014-06-01 00:00:00');
		$toDate = new \DateTime('2014-12-31 23:59:59');

		$generatedMonthlyDateOccurrences = $monthlyDateRecurrenceType->generateOccurrences($mockEvents, $fromDate, $toDate);

		$previousDate = null;
		$previousTime = null;

		foreach($generatedMonthlyDateOccurrences as $generatedMonthlyDateOccurrence) {
			if(!($previousDate || $previousTime)) {
				list($previousDate, $previousTime) = explode(' ', $generatedMonthlyDateOccurrence->getStartDate());
				continue;
			}

			list($currentDate, $currentTime) = explode(' ', $generatedMonthlyDateOccurrence->getStartDate());

			$this->assertGreaterThan($previousDate, $currentDate);
			$this->assertEquals($previousTime, $currentTime);

			$previousDate = $currentDate;
			$previousTime = $currentTime;
		}

		$this->assertCount(6, $generatedMonthlyDateOccurrences);
	}
}
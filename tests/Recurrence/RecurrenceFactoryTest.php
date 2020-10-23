<?php

namespace Plummer\Calendarful\Recurrence;

use InvalidArgumentException;
use Mockery as m;
use OutOfBoundsException;
use Plummer\Calendarful\Recurrence\Type\Daily;

class RecurrenceFactoryTest extends \PHPUnit\Framework\TestCase
{
	public function tearDown(): void
	{
		m::close();
	}

	public function testRecurrenceTypeClassDoesNotExist()
	{
        $this->expectException(InvalidArgumentException::class);

		$recurrenceFactory = new RecurrenceFactory();

		$recurrenceFactory->addRecurrenceType('test', 'ThisIsNotAValidFileOrFilePath');
	}

	public function testRecurrenceTypeClassPathNotRecurrenceInterfaceImplementation()
	{
        $this->expectException(InvalidArgumentException::class);

		$recurrenceFactory = new RecurrenceFactory();

		$recurrenceFactory->addRecurrenceType('test', 'Plummer\Calendarful\Mocks\MockEvent');
	}

	public function testRecurrenceTypeClassNotRecurrenceInterfaceImplementation()
	{
        $this->expectException(InvalidArgumentException::class);

		$recurrenceFactory = new RecurrenceFactory();

		$recurrenceFactory->addRecurrenceType('test', new \stdClass());
	}

	public function testValidRecurrenceTypeClassPath()
	{
		$recurrenceFactory = new RecurrenceFactory();

		$recurrenceFactory->addRecurrenceType('test', 'Plummer\Calendarful\Recurrence\Type\Daily');

		$this->assertEquals(1, count($recurrenceFactory->getRecurrenceTypes()));
	}

	public function testValidRecurrenceTypeClass()
	{
		$recurrenceFactory = new RecurrenceFactory();

		$recurrenceFactory->addRecurrenceType('test', new Daily());

		$this->assertEquals(1, count($recurrenceFactory->getRecurrenceTypes()));
	}

	public function testNonExistentRecurrenceTypeClassRetrieval()
	{
        $this->expectException(OutOfBoundsException::class);

		$recurrenceFactory = new RecurrenceFactory();

		$recurrence = $recurrenceFactory->createRecurrenceType('test');
	}

	public function testValidRecurrenceTypeClassRetrieval()
	{
		$recurrenceFactory = new RecurrenceFactory();

		$recurrenceFactory->addRecurrenceType('daily', new Daily());

		$this->assertInstanceOf('Plummer\Calendarful\Recurrence\RecurrenceInterface', $recurrenceFactory->createRecurrenceType('daily'));
	}
}

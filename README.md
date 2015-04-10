Calendarful
-------

[![Author](http://img.shields.io/badge/author-@Ben_Plummer-blue.svg?style=flat-square)](https://twitter.com/ben_plummer)
[![Build Status](https://travis-ci.org/benplummer/calendarful.svg?branch=master)](https://travis-ci.org/benplummer/calendarful)
[![Packagist](https://img.shields.io/packagist/v/plummer/calendarful.svg?style=flat)](https://packagist.org/packages/plummer/calendarful)
[![Packagist](https://img.shields.io/packagist/l/plummer/calendarful.svg?style=flat)](https://github.com/benplummer/calendarful/blob/master/LICENSE)

Calendarful is a simple and easily extendable PHP solution that allows the generation of occurrences of recurrent events, 
thus eliminating the need to store hundreds or maybe thousands of occurrences in a database or other methods of storage. 

This package ships with default implementations of interfaces for use out of the box although it is very simple to provide
your own implementations if needs be.

It is compliant with [PSR-2].

[PSR-2]: http://www.php-fig.org/psr/psr-2/

Installation
-------

This package can be installed via [Composer]:

``` bash
$ composer require plummer/calendarful
```

It requires **PHP >= 5.3.0**.

Required Set Up
-------

Before using this package, a few steps need to be taken.

#### Event Models

This package requires that you have `Event` and `RecurrentEvent` models.For the models to be compatible with this package they **must** implement the relevant package interfaces.

The `Event` model represents standard non-recurrent events and follows the `EventInterface`:
 
```php
<?php

use Plummer\Calendarful\Event\EventInterface;

class Event implements EventInterface
{
    
}
```

The `RecurrentEvent` model represents recurrent events and follows the `RecurrentEventInterface`:
 
```php
<?php

use Plummer\Calendarful\Event\RecurrentEventInterface;

class RecurrentEvent implements RecurrentEventInterface
{
    
}
```

The `RecurrentEventInterface` actually extends the `EventInterface` in order for its implementations to provide a little more functionality that is specific to recurrent events.

Ideally, there should be properties on the models that are relevant to each getter and setter method 
of the interface they implement, in order for this package to function most effectively.
For example, there should be a 'start date' property that `getStartDate()` and `setStartDate()` apply to.
 
The `MockEvent` and `MockRecurrentEvent` classes under the `tests` directory provide an example implementation of the methods and relevant properties to be used. 

Documentation of each method inside the `EventInterface` and `RecurrentEventInterface` files also provide brief explanations of the purpose of each of the properties.

If you don't like the idea of having two `Event` models, you could have just one that implements the `RecurrentEventInterface`. This would mean it would contain all of the functionality of standard non-recurrent events as well as recurrent ones, although it is not best practice as recurrent event related methods would be redundant for non-recurrent events. If you decide to take this path (not recommended), your `EventRegistry` coming up can deal with the same model in both methods but return only applicable events.

#### Event Registry

Once you have your models fully set up, you need to create an `EventRegistry` class which should implement the `EventRegistryInterface`.

This should consist of two methods; one to retrieve events that recur (that follow the `RecurrentEventInterface`) and the other to retrieve standard non-recurrent events (that follow the `EventInterface`).
Calendarful performs different operations on the two different types.

This is my example Event Registry using Laravel's Eloquent ORM:

```php
<?php

use \Plummer\Calendarful\Event\EventRegistryInterface;

class EventRegistry implements EventRegistryInterface
{
	public function getEvents(array $filters = array())
	{
		$events = [];

		$results = \TestEvent::where('startDate', '<=', $filters['toDate']->format('Y-m-d'))
					->where('endDate', '>=', $filters['fromDate']->format('Y-m-d'))
					->whereNull('type')->get();

		foreach($results as $event) {
			$events[$event->getId()] = $event;
		}

		return $events;
	}

	public function getRecurrentEvents(array $filters = array())
	{
		$recurrentEvents = [];

		$results = \TestRecurrentEvent::whereNotNull('type')
				->where(
					function ($query) use ($filters) {
						$query->whereNull('until')
							->where('until', '>=', $filters['fromDate']->format('Y-m-d'), 'or');
					})->get();

		foreach($results as $event) {
			$recurrentEvents[$event->getId()] = $event;
		}

		return $recurrentEvents;
	}
}
```

When you populate the default `Calendar` class with events, the parameters you pass will be passed to the 
`EventRegistry` as the `$filters` you can see being used above. These passed `$filters` allow the `EventRegistry` 
to do a lot of the work in returning only the relevant events.

The `EventRegistry` above uses the date filters to determine which events fall into the date range given.
If no filters are provided and all events are returned, the `Calendar` class will determine which of those events are relevant.

**The sole reason for filters being passed to the Event Registry is to optimize performance by using relevant events earlier in the process.**

Usage
-------

With the models and registry set up, you just need to instantiate the `EventRegistry` and `Calendar` and populate the `Calendar`.

The `populate` method takes in the `EventRegistry`, the date range that the `Calendar` should cover (a 'from' and 'to' date) and a limit if there is a maximum limit on the amount of events you want back.
 
```php
$eventsRegistry = new EventRegistry();

$calendar = Plummer\Calendarful\Calendar\Calendar::create()
			->populate($eventsRegistry, new \DateTime('2014-04-01'), new \DateTime('2014-04-30'));
```

As calendars implement the `CalendarInterface` which in turn extends IteratorAggregate, they are traversable.
The default `Calendar` uses an ArrayIterator which means we can access the events like so:

```php
foreach($calendar as $event) {
    // Use event as necessary... 
}
```

### Recurrent Events

To identify recurrent events and generate occurrences for them, a `RecurrenceFactory` comes into the above process.

```php
$eventsRegistry = new EventRegistry();

$recurrenceFactory = new \Plummer\Calendarful\Recurrence\RecurrenceFactory();
$recurrenceFactory->addRecurrenceType('daily', 'Plummer\Calendarful\Recurrence\Type\Daily');
$recurrenceFactory->addRecurrenceType('weekly', 'Plummer\Calendarful\Recurrence\Type\Weekly');
$recurrenceFactory->addRecurrenceType('monthly', 'Plummer\Calendarful\Recurrence\Type\MonthlyDate');

$calendar = Plummer\Calendarful\Calendar\Calendar::create($recurrenceFactory)
			->populate($eventsRegistry, new \DateTime('2014-04-01'), new \DateTime('2014-04-30'));
```

We can see that the three default package recurrence types were injected into the `RecurrenceFactory` and passed to the `Calendar`.

In order for occurrences to be generated, the `getRecurrenceType()` return value for a recurrent event should match up to the first parameter value from where Recurrence Types are added to the `RecurrenceFactory` e.g. 'daily', 'weekly' or 'monthly' above.

When occurrences are generated, they will be a clone of their parent aside from updates to their date and recurrence related properties.

#### Overriding Occurrences

If you are using this package for its recurrence functionality, it is likely you will want to override an occurrence at some point.

For instance, you may have a weekly recurring event that runs every Monday but you may want next week's occurrence to run on the Tuesday instead and continue on Mondays again thereafter. This is where occurrence overrides come in.

When you want to override an occurrence you need to create a new `Event` and save it to your storage method of choice.
For the override to be recognised by the package you need to update the values of those properties on the Event model 
returned by `getParentId()` and `getOccurrenceDate`.

Lets say your `Event` model has properties called `parentId` and `occurrenceDate` in conjunction with those `EventInterface` methods mentioned.

To override next Monday's occurrence to Tuesday you would need to set the `parentId` to the `Id` value of the parent event that recurs every Monday, and the `occurrenceDate` to the date that the occurrence would have been; the Monday. The `startDate` would also need to be updated to the Tuesday's date. Once that new event is saved, the Monday occurrence next week would be replaced by the override in the `Calendar`.

**If a parent event start date ever changes, all of the occurrence dates for the overrides of the occurrences for that event need to be altered by the same difference in time to ensure the overrides still work.**

### Adding your own Recurrence Types

To add your own Recurrence Type all you need to do is create a new class that implements the `RecurrenceInterface` and its methods.

The new Recurrence Type can then be added to the `RecurrenceFactory` in the same way as shown above.

```php
$recurrenceFactory = new \Plummer\Calendarful\Recurrence\RecurrenceFactory();
$recurrenceFactory->addRecurrenceType('ThisShouldMatchAnEventRecurrenceTypePropertyValue', 'Another\RecurrenceType\ClassPath');
```

### Different Types of Calendars

This package supports different types of calendars as long as they implement the `CalendarInterface`.

You may want to use multiple calendars at once, in which case you can use the `CalendarFactory`.
You add calendars to the factory in much the same way as the `RecurrenceFactory` works.
 
```php
$calendarFactory = new \Plummer\Calendarful\Calendar\CalendarFactory();
$calendarFactory->addCalendarType('gregorian', 'Plummer\Calendarful\Calendar\Calendar');
$calendarFactory->addCalendarType('anotherType', 'Another\CalendarType\ClassPath');
```
 
Next, you can either retrieve the calendar type you desire.

```php
$calendar = $calendarFactory->createCalendar('gregorian');
```

Or retrieve all calendar types to loop through etc.

```php
foreach($calendarFactory->getCalendarTypes() as $type => $calendar) {
    // Use calendar...
}
```

### Extending the Package

There are interfaces for every component within this package therefore if the default implementations do not do
exactly as you wish or you want them to work slightly differently it is quite simple to construct your own implementation.
This may be for one component or for all.

**If you do use your own components, I highly recommend looking at the functionality of the existing default components as
you may wish to use parts e.g. to ensure occurrence overrides etc still function.**

Testing
-------

Unit tests can be run inside the package:

``` bash
$ ./vendor/bin/phpunit
```

Contributing
-------

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

License
-------

**plummer/calendarful** is licensed under the MIT license.  See the `LICENSE` file for more details.

[Composer]: https://getcomposer.org/

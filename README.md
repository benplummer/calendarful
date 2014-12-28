# Calendarful

[![Build Status](https://travis-ci.org/benplummer/calendarful.svg?branch=master)](https://travis-ci.org/benplummer/calendarful)
[![Packagist](https://img.shields.io/packagist/l/doctrine/orm.svg?style=flat)](https://github.com/benplummer/calendarful/blob/master/LICENSE)

Calendarful is a simple and easily extendable PHP solution that allows the generation of occurrences of recurring events, thus eliminating the need to store hundreds or maybe thousands of occurrences in a database or other methods of storage. 

This package ships with default implementations of interfaces for use out of the box although it is very simple to provide your own implementations if needs be.

## Installation

This project can be installed via [Composer]:

``` bash
$ composer require plummer/calendarful
```

## Required Set Up

There are a few steps to take before using this package.

### Event Model

This package requires that you have an `Event` model. This could be an Eloquent model from Laravel or any ORM model or class.

For the model to be compatible with this package it must implement the `EventInterface`
 
```php
<?php

use Plummer\Calendarful\Event\EventInterface;

class Event implements EventInterface
{
    
}
```

Your `Event` model must then provide an implementation for each of the methods within the `EventInterface`.

Ideally, there should be a property on the model that is relevant to each of the getter and setter methods of the `EventInterface` for this package to function most effectively.
For example, there should be a start date property that `getStartDate()` and `setStartDate()` can use etc.
 
The `MockEvent` class under the `tests` directory provides an example implementation of the methods and relevant properties to be used.
Documentation of each `EventInterface` method inside the file also provides brief explanations of the purpose of each of the properties.

### Event Registry

Once you have your `Event` model fully set up, you need to create a class for your `EventRegistry` which should implement the `RegistryInterface`.

This is my example Event Registry using Laravel's Eloquent ORM:

```php
<?php

use Plummer\Calendarful\RegistryInterface;

class EventRegistry implements RegistryInterface
{
    public function get(Array $filters = array())
    {
        $events = [];

        if(!$filters) {
            foreach(\TestEvent::all() as $event) {
                $events[$event->getId()] = $event;
            }
        }
        else {
            $results = \TestEvent::where('startDate', '<=', $filters['toDate']->format('Y-m-d'))
                        ->where('endDate', '>=', $filters['fromDate']->format('Y-m-d'))
                        ->orWhere(
                            function($query) use ($filters) {
                                $query->whereNotNull('type')
                                    ->where(
                                        function ($query) use ($filters) {
                                            $query->whereNull('until')
                                                ->where('until', '>=', $filters['fromDate']->format('Y-m-d'), 'or');
                                        }
                                    );
                            }
                        )->get();


            foreach($results as $event) {
                $events[$event->getId()] = $event;
            }
        }

        return $events;
    }
}
```

When you populate the default `Calendar` class with events, the parameters you pass will be passed to the 
Event Registry as the `filters` you can see being used above. These passed `filters` allow the Event Registry 
to do a lot of the work in returning only the relevant events.

The `EventRegistry` above uses the date filters to determine which events fall into the date range given.
If no filters are provided and all events are returned, the `Calendar` class will determine which of those events are relevant.

**The sole reason for filters being passed to the Event Registry is to optimize performance by using relevant events earlier in the process.**

## License ##

**plummer/calendarful** is licensed under the MIT license.  See the `LICENSE` file for more details.

[Composer]: https://getcomposer.org/

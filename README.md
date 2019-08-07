# PHP Calendar
A PHP class that makes generating calendars as easy as possible.

You can use the addEvent() or addEvents() methods to mark events on the generated calendar.

This is fully compatible with *PHP 5 through to **PHP 7.3+***
# Installation via Composer
You can now install this class via composer.

	$ composer require benhall14/php-calendar
	
**Remember** to add the composer autoloader before using the class and use the correct namespace.

	require 'vendor/autoload.php';

	use benhall14\phpCalendar\Calendar as Calendar;

# Usage
Please make sure you have added the required classes.

You should probably also make sure you include the calendar.css stylesheet, unless you are creating your own stylesheet.
```html
<link rel="stylesheet" type="text/css" href="css/calendar.css">
```

In its simplest form, use the following to create a calendar

```php

    # create the calendar object

    $calendar = new Calendar();

    # if needed, add event

	$calendar->addEvent(
	    '2017-01-14',   # start date in Y-m-d format
	    '2017-01-14',   # end date in Y-m-d format
	    'My Birthday',  # event name text
	    true,           # should the date be masked - boolean default true
	    ['myclass', 'abc']   # (optional) additional classes in either string or array format to be included on the event days
	);

    # or for multiple events

	$events = array();

	$events[] = array(
		'start' => '2017-01-14',
		'end' => '2017-01-14',
		'summary' => 'My Birthday',
		'mask' => true,
		'classes' => ['myclass', 'abc']
	);

	$events[] = array(
		'start' => '2017-12-25',
		'end' => '2017-12-25',
		'summary' => 'Christmas',
		'mask' => true
	);

	$calendar->addEvents($events);

	# finally, to draw a calendar

	echo $calendar->draw(date('Y-m-d')); # draw this months calendar

	# this can be repeated as many times as needed with different dates passed, such as:

	echo $calendar->draw(date('Y-01-01')); # draw a calendar for January this year

	echo $calendar->draw(date('Y-02-01')); # draw a calendar for February this year

	echo $calendar->draw(date('Y-03-01')); # draw a calendar for March this year

	echo $calendar->draw(date('Y-04-01')); # draw a calendar for April this year

	echo $calendar->draw(date('Y-05-01')); # draw a calendar for May this year

	echo $calendar->draw(date('Y-06-01')); # draw a calendar for June this year

	# to use the pre-made color schemes, include the calendar.css stylesheet and pass the color choice to the draw method, such as:

	echo $calendar->draw(date('Y-m-d'));            # print a (default) turquoise calendar

	echo $calendar->draw(date('Y-m-d'), 'purple');  # print a purple calendar

	echo $calendar->draw(date('Y-m-d'), 'pink');    # print a pink calendar

	echo $calendar->draw(date('Y-m-d'), 'orange');  # print a orange calendar

	echo $calendar->draw(date('Y-m-d'), 'yellow');  # print a yellow calendar

	echo $calendar->draw(date('Y-m-d'), 'green');   # print a green calendar

	echo $calendar->draw(date('Y-m-d'), 'grey');    # print a grey calendar

	echo $calendar->draw(date('Y-m-d'), 'blue');    # print a blue calendar

```

# Requirements

**Fully tested to work with PHP 5.3, 5.5, 5.6, 7.0, 7.1, 7.2 and 7.3**

**PHP DateTime**

# License
Copyright (c) 2016-2019 Benjamin Hall, ben@conobe.co.uk 
https://conobe.co.uk

Licensed under the MIT license

# Donate?
If you find this project helpful or useful in anyway, please consider getting me a cup of coffee - It's really appreciated :)

[![Donate](https://img.shields.io/badge/Donate-PayPal-green.svg)](https://paypal.me/benhall14)

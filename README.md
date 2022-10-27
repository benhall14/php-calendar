# PHP Calendar
A PHP class that makes generating calendars as easy as possible.

You can use the addEvent() or addEvents() methods to mark events on the generated calendar.

This is fully compatible with *PHP 5 through to **PHP 8.1+***

# Installation via Composer
You can now install this class via composer.

	$ composer require benhall14/php-calendar
	
**Remember** to add the composer autoloader before using the class and use the correct namespace.

	require 'vendor/autoload.php';

	use benhall14\phpCalendar\Calendar as Calendar;

# Usage
Please make sure you have added the required classes.

#### Styling
You can apply styles in one of three ways: 

1) Using $calendar->stylesheet() after you have initialised a calendar;
```php
    $calendar = new Calendar();
    $calendar->stylesheet();
```

2) Using the calendar.css (or calendar.min.css) from the css directory;
```html
    <link rel="stylesheet" type="text/css" href="css/calendar.min.css">
```
3) Create your own stylesheet and add it to the head of your HTML document.

#### Draw a calendar

In its simplest form, use the following to create a calendar

```php

    # create the calendar object
    $calendar = new Calendar();
    
    # change the weekly start date to "Monday"
    $calendar->useMondayStartingDate();
    
    # or revert to the default "Sunday"
    $calendar->useSundayStartingDate();
    
    # (optional) - if you want to use full day names instead of initials (ie, Sunday instead of S), apply the following:
    $calendar->useFullDayNames();
    
    # to revert to initials, use:
    $calendar->useInitialDayNames();
    
    # (optional) - if you want to hide certain weekdays from the calendar, for example a calendar without weekends, you can use the following methods:
    $calendar->hideSaturdays() 		# This will hide Saturdays
    $calendar->hideSundays(); 		# This will hide Sundays
    $calendar->hideMondays(); 		# This will hide Mondays
    $calendar->hideTuesdays(); 		# This will hide Tuesdays
    $calendar->hideWednesdays();	# This will hide Wednesdays
    $calendar->hideThursdays();		# This will hide Thursdays
    $calendar->hideFridays();		# This will hide Fridays
    
    # (optional) - if you want month tables to have a specific CSS class, you can use the following:
    $calendar->setMonthCssClass('custom-class');
    
    # (optional) - Translated Calendars - currently, there is only Spanish, but see "Translations" below for adding your own strings.
    $calendar->useSpanish(); 

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
    
    # to use the pre-made color schemes, call the ->stylesheet() method and then pass the color choice to the draw method, such as:    
    echo $calendar->draw(date('Y-m-d'));            # print a (default) turquoise calendar    
    echo $calendar->draw(date('Y-m-d'), 'purple');  # print a purple calendar    
    echo $calendar->draw(date('Y-m-d'), 'pink');    # print a pink calendar    
    echo $calendar->draw(date('Y-m-d'), 'orange');  # print a orange calendar    
    echo $calendar->draw(date('Y-m-d'), 'yellow');  # print a yellow calendar    
    echo $calendar->draw(date('Y-m-d'), 'green');   # print a green calendar    
    echo $calendar->draw(date('Y-m-d'), 'grey');    # print a grey calendar    
    echo $calendar->draw(date('Y-m-d'), 'blue');    # print a blue calendar    
```

#### Monday Start Date

You can now change the weekly start date from a **Sunday** to a **Monday**. To activate this, simple use the **useMondayStartingDate()** method before you 'draw'.
```php

    $calendar = new Calendar();
    $calendar->useMondayStartingDate();
    $calendar->draw(date('Y-m-d'), 'green');
```

#### Translated Calendars

We now ship with both **English** and **Spanish** translations, with more coming soon. Alternatively, you can add your own custom string translations for both the days and months using the following:

````php
    	
    # This will set up the days - simply copy/paste the code below and replace the Spanish initials and full-day names with your own. (NB - Leave the keys in English)
    $calendar->setDays([
        'sunday' => [
        	'initials' => 'D',
        	'full' => 'Domingo'
        ],
        'monday' => [
        	'initials' => 'L',
        	'full' => 'Lunes',
        ],
        'tuesday' => [
        	'initials' => 'M',
        	'full' => 'Martes',
        ],
        'wednesday' => [
        	'initials' => 'X',
        	'full' => 'Miércoles',
        ],
        'thursday' => [
        	'initials' => 'J',
        	'full' => 'Jueves',
        ],
        'friday' => [
        	'initials' => 'V',
        	'full' => 'Viernes',
        ],
        'saturday' => [
        	'initials' => 'S',
        	'full' => 'Sábado',
        ],
    ]);
    
    # To add custom month names, simply copy/paste the code below and replace the Spanish month names with your own strings. (NB - Leave the keys in English)
    $calendar->setMonths([
        'january' => 'Enero',  
        'february' => 'Febrero',  
        'march' => 'Marzo',  
        'april' => 'Abril',  
        'may' => 'Mayo',  
        'june' => 'Junio',  
        'july' => 'Julio',  
        'august' => 'Agosto',  
        'september' => 'Septiembre',  
        'october' => 'Octubre',  
        'november' => 'Noviembre',  
        'december' => 'Diciembre'
    ]);
````

If you want to help with translations, use the code in the **useSpanish()** method as a guide, and open a pull-request.

# Requirements

**Fully tested to work with PHP 5.3, 5.5, 5.6, 7.0, 7.1, 7.2, 7.3 and 8.1**

**PHP DateTime**

# License
Copyright (c) 2016-2022 Benjamin Hall, ben@conobe.co.uk 
https://conobe.co.uk

Licensed under the MIT license

# Donate?
If you find this project helpful or useful in any way, please consider getting me a cup of coffee - It's really appreciated :)

[![Donate](https://img.shields.io/badge/Donate-PayPal-green.svg)](https://paypal.me/benhall14)

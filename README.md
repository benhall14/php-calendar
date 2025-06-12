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

    $calendar = new Calendar;
    $calendar->stylesheet();

```

2) Using the calendar.css (or calendar.min.css) from the css directory;

```html

    <link rel="stylesheet" type="text/css" href="css/calendar.min.css">

```
3) Create your own stylesheet and add it to the head of your HTML document.

#### Draw a 'Month View' calendar

In its simplest form, use the following to create a calendar for current month. This will use all defaults (English, Week starting on Sunday, including stylesheet, printing to the page).

```php

    (new Calendar)->display();

```

Or, you can break it down with full customisability:

```php

    # create the calendar object
    $calendar = new Calendar;
    
    # change the weekly start date to "Monday"
    $calendar->useMondayStartingDate();
    
    # or revert to the default "Sunday"
    $calendar->useSundayStartingDate();
    
    # (optional) - if you want to use full day names instead of initials (ie, Sunday instead of S), apply the following:
    $calendar->useFullDayNames();
    
    # to revert to initials, use:
    $calendar->useInitialDayNames();

    # use the French locale, with days and month names being translated to French.
    $calendar->setLocale('fr_FR');
    # This uses the Carbon locales - https://carbon.nesbot.com/docs/#api-localization

    # add your own table class(es)
    $calendar->addTableClasses('class-1 class-2 class-3');
    # or using an array of classes.
    $calendar->addTableClasses(['class-1', 'class-2', 'class-3']);
    
    # (optional) - if you want to hide certain weekdays from the calendar, for example a calendar without weekends, you can use the following methods:
    $calendar->hideSaturdays();		# This will hide Saturdays
    $calendar->hideSundays(); 		# This will hide Sundays
    $calendar->hideMondays(); 		# This will hide Mondays
    $calendar->hideTuesdays(); 		# This will hide Tuesdays
    $calendar->hideWednesdays();	# This will hide Wednesdays
    $calendar->hideThursdays();		# This will hide Thursdays
    $calendar->hideFridays();		# This will hide Fridays
    
    # (optional) - Translated Calendars - currently, there is only Spanish, but see "Translations" below for adding your own strings.
    $calendar->useSpanish(); 

    # if needed, add event
	$calendar->addEvent(
	    '2022-01-14',   # start date in either Y-m-d or Y-m-d H:i if you want to add a time.
	    '2022-01-14',   # end date in either Y-m-d or Y-m-d H:i if you want to add a time.
	    'My Birthday',  # event name text
	    true,           # should the date be masked - boolean default true
	    ['myclass', 'abc'],   # (optional) additional classes in either string or array format to be included on the event days
	    ['event-class', 'abc'],   # (optional) additional classes in either string or array format to be included on the event summary box
        ['data-type' => 'holiday', 'data-holiday' => 'Independence Day'] # (optional) arbitrary data attributes to be included on the event day
	);

    # or for multiple events

	$events = array();

	$events[] = array(
		'start' => '2022-01-14',
		'end' => '2022-01-14',
		'summary' => 'My Birthday',
		'mask' => true,
		'classes' => ['myclass', 'abc'],
        'event_box_classes' => ['event-box-1'], 
        'dataAttributes' => ['data-type' => 'holiday', 'data-holiday' => 'Independence Day']
	);

	$events[] = array(
		'start' => '2022-12-25',
		'end' => '2022-12-25',
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
    
    # you can also call ->display(), which handles the echo'ing and adding the stylesheet.
    echo $calendar->display(array(date('Y-m-d'))); # draw this months calendar    

```
#### Draw a 'Week View' calendar

Instead of a 'month view' calendar, you can now render as a 'week view'. To do this, simply use ->useWeekView(). Remember, when adding events to a week-view calendar, you need to include the time too (see events above).

```php

    $events = array();

    $events[] = array(
        'start' => '2022-01-14 14:40',
        'end' => '2022-01-14 15:10',
        'summary' => 'My Birthday',
        'mask' => true,
        'classes' => ['myclass', 'abc']
    );

    $events[] = array(
        'start' => '2022-11-04 14:00',
        'end' => '2022-11-04 18:30',
        'summary' => 'Event 1',
        'mask' => true
    );
    $events[] = array(
        'start' => '2022-11-04 14:00',
        'end' => '2022-11-04 18:30',
        'summary' => 'Event 2',
        'mask' => true
    );

    $calendar = new Calendar;

    $calendar->addEvents($events)->setTimeFormat('00:00', '00:00', 10)->useWeekView()->display(date('Y-m-d'), 'green');

```

You can change the start/end times of the day, along with the time interval by using the ->setTimeFormat method:

```php

    $calendar->setTimeFormat('00:00', '00:00', 10)->useWeekView()->display(date('Y-m-d'), 'green');
    # This will print a week view calendar with 10 minute slots from midnight to midnight - ie. 00:00, 00:10, 00:20 and so on.

```

#### Monday Start Date

You can now change the weekly start date from a **Sunday** to a **Monday**. To activate this, simple use the **useMondayStartingDate()** method before you 'draw'.

```php

    $calendar = new Calendar;
    $calendar->useMondayStartingDate();
    $calendar->display(date('Y-m-d'), 'green');

```

#### Translated Calendars

We now use automatically from the date and the setLocale() method. If you use ->setLocale('fr_FR'), then the days and month names will be French. For more information on the Carbon localization, see https://carbon.nesbot.com/docs/#api-localization

# Credits
-   [GeoSot](https://github.com/GeoSot)

# Requirements

**Carbon**

# License
Copyright (c) 2016-2022 Benjamin Hall, ben@conobe.co.uk 
https://conobe.co.uk

Licensed under the MIT license

# Donate?
If you find this project helpful or useful in any way, please consider getting me a cup of coffee - It's really appreciated :)

[![Donate](https://img.shields.io/badge/Donate-PayPal-green.svg)](https://paypal.me/benhall14)

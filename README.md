# PHP Calendar
A PHP class that makes generating calendars as easy as possible.

You can use the addEvent() or addEvents() methods to mark events on the generated calendar.

# Usage
Please make sure you have added the required classes.

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
            'myclass abc'   # (optional) additional classes to be included on the event days
        );
    # or for multiple events
        $events = array();
        $events[] = array('2017-01-14', '2017-01-14', 'My Birthday', true, 'myclass abc');
        $events[] = array('2017-12-25', '2017-12-25', 'Christmas', true);

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


```

# Requirements
PHP 5.3+
PHP DateTime

# License
Copyright (c) Benjamin Hall, benhall14@hotmail.com

Licensed under the MIT license

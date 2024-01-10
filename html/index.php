<?php

require('../src/phpCalendar/Calendar.php');

use benhall14\phpCalendar\Calendar;

$calendar = new Calendar();

$calendar
    ->addEvent(date('Y-01-14'), date('Y-01-14'), 'My Birthday', true)
    ->addEvent(date('Y-12-25'), date('Y-12-25'), 'Christmas', true)
    ->addEvent(date('Y-1-1 10:00'), date('Y-1-1 12:00'), 'Time Event', true);

#   or
/*
$events = array(
    array(
        'start' => date('Y-01-14'),
        'end' => date('Y-01-14'),
        'summary' => 'My Birthday',
        'mask' => true
    ), 
    array(
        'start' => date('Y-12-25'),
        'end' => date('Y-12-25'),
        'summary' => 'Christmas',
        'mask' => true
    )
);
$calendar->addEvents($events);
*/
?>
<!doctype html>

<html lang="en">

<head>

    <meta charset="utf-8">

    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <title>PHP Calendar By benhall14</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

    <link href="https://fonts.googleapis.com/css?family=Oxygen:400,700" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="css/stylesheet.css">

    <link rel="stylesheet" type="text/css" href="css/calendar.css">

</head>

<body>

    <h1>PHP Calendar <span>By benhall14</span></h1>

    <div class="container">

        <div class="summary">

            <p>A simple PHP class to generate calendars with 8 different color schemes to choose from (or use your own style sheet).</p>

            <p>You can add events using the optional API.</p>

        </div>

        <h1>Monthly View</h1>

        <hr />

        <div class="row fix">

            <div class="col-xs-12 col-sm-6 col-md-4">

                <?php echo $calendar->draw(date('Y-1-1'), ''); ?>

                <hr />

            </div>

            <div class="col-xs-12 col-sm-6 col-md-4">

                <?php echo $calendar->draw(date('Y-2-1'), 'pink'); ?>

                <hr />

            </div>

            <div class="col-xs-12 col-sm-6 col-md-4">

                <?php echo $calendar->draw(date('Y-3-1'), 'blue'); ?>

                <hr />

            </div>

            <div class="col-xs-12 col-sm-6 col-md-4">

                <?php echo $calendar->draw(date('Y-4-1'), 'orange'); ?>

                <hr />

            </div>

            <div class="col-xs-12 col-sm-6 col-md-4">

                <?php echo $calendar->draw(date('Y-5-1'), 'purple'); ?>

                <hr />

            </div>

            <div class="col-xs-12 col-sm-6 col-md-4">

                <?php echo $calendar->draw(date('Y-6-1'), 'yellow'); ?>

                <hr />

            </div>

            <div class="col-xs-12 col-sm-6 col-md-4">

                <?php echo $calendar->draw(date('Y-7-1'), 'green'); ?>

                <hr />

            </div>

            <div class="col-xs-12 col-sm-6 col-md-4">

                <?php echo $calendar->draw(date('Y-8-1'), 'grey'); ?>

                <hr />

            </div>

            <div class="col-xs-12 col-sm-6 col-md-4">

                <?php echo $calendar->draw(date('Y-9-1'), 'pink'); ?>

                <hr />

            </div>

            <div class="col-xs-12 col-sm-6 col-md-4">

                <?php echo $calendar->draw(date('Y-10-1'), 'blue'); ?>

            </div>

            <div class="col-xs-12 col-sm-6 col-md-4">

                <?php echo $calendar->draw(date('Y-11-1'), 'orange'); ?>

            </div>

            <div class="col-xs-12 col-sm-6 col-md-4">

                <?php echo $calendar->draw(date('Y-12-1'), 'purple'); ?>

                <hr />

            </div>

        </div>

        <h1>Weekly View</h1>

        <hr />

        <div class="row">

            <div class="col-xs-12">

                <?php echo $calendar->useWeekView()->draw(date('Y-1-1'), 'blue'); ?>

            </div>

        </div>

        <hr />

        <div class="row">

            <div class="col-xs-12">

                <?php echo $calendar->useWeekView()->draw(date('Y-12-25'), 'green'); ?>

            </div>

        </div>

        <div class="copyright">

            <p>&copy; Copyright Benjamin Hall :: <a href="https://github.com/benhall14/php-calendar">https://github.com/benhall14/php-calendar</a></p>

        </div>

    </div>

</body>

</html>
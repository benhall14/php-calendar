<?php

namespace benhall14\phpCalendar;

use DateTime;
use stdClass;

/**
 * Simple PHP Calendar Class.
 *
 * @copyright  Copyright (c) Benjamin Hall
 * @license https://github.com/benhall14/php-calendar
 * @package protocols
 * @version 1.2
 * @author Benjamin Hall <https://conobe.co.uk>
*/
class Calendar
{
    /**
     * The internal date pointer.
     * @var DateTime
     */
    private $date;

    /**
     * The events array.
     * @var array
     */
    private $events = array();

    /**
     * Add an event to the current calendar instantiation.
     * @param string  $start   The start date in Y-m-d format.
     * @param string  $end     The end date in Y-m-d format.
     * @param string $summary The summary string of the event.
     * @param boolean $mask    The masking class.
     * @param boolean $classes (optional) A list of classes to use for the event.
     * @return object Return this object for chain-ability.
     */
    public function addEvent($start, $end, $summary = false, $mask = false, $classes = false)
    {
        $event = new stdClass();
        
        $event->start = DateTime::createFromFormat('Y-m-d', $start);
        
        $event->end = DateTime::createFromFormat('Y-m-d', $end);
        
        $event->mask = $mask ? true : false;
        
        if ($classes) {
            if (is_array($classes)) {
                $classes = implode(' ', $classes);
            }

            $event->classes = $classes;
        } else {
            $event->classes = false;
        }
        
        $event->summary = $summary ? $summary : false;

        $this->events[] = $event;

        return $this;
    }

    /**
     * Add an array of events using $this->addEvent();
     *
     * Each array element must have the following:
     *     'start'  =>   start date in Y-m-d format.
     *     'end'    =>   end date in Y-m-d format.
     *     (optional) 'mask' => a masking class name.
     *     (optional) 'classes' => custom classes to include.
     *
     * @param array $events The events array.
     * @return object Return this object for chain-ability.
     */
    public function addEvents($events)
    {
        if (is_array($events)) {
            foreach ($events as $event) {
                if (isset($event['start']) && isset($event['end'])) {
                    $classes = isset($event['classes']) ? $event['classes'] : false;
                    $mask = isset($event['mask']) ? (bool) $event['mask'] : false;
                    $summary = isset($event['summary']) ? $event['summary'] : false;
                    $this->addEvent($event['start'], $event['end'], $summary, $mask, $classes);
                }
            }
        }

        return $this;
    }

    /**
     * Remove all events tied to this calendar
     * @return object Return this object for chain-ability.
     */
    public function clearEvents()
    {
        $this->events = array();

        return $this;
    }

    /**
     * Find an event from the internal pool
     * @param  DateTime $date The date to match an event for.
     * @return array          Either an array of events or false.
     */
    private function findEvents(DateTime $date)
    {
        $found_events = array();

        if (isset($this->events)) {
            foreach ($this->events as $event) {
                if ($date->getTimestamp() >= $event->start->getTimestamp() && $date->getTimestamp() <= $event->end->getTimestamp()) {
                    $found_events[] = $event;
                }
            }
        }

        return ($found_events) ? : false;
    }

    /**
     * Draw the calendar and echo out.
     * @param string $date    The date of this calendar.
     * @param string $format  The format of the preceding date.
     * @return string         The calendar
     */
    public function draw($date = false, $color = false)
    {
        $calendar = '';

        if ($date) {
            $date = DateTime::createFromFormat('Y-m-d', $date);
            $date->modify('first day of this month');
        } else {
            $date = new DateTime();
            $date->modify('first day of this month');
        }

        $today = new DateTime();

        $total_days_in_month = (int) $date->format('t');

        $color = $color ? : '';
        
        $calendar .= '<table class="calendar ' . $color . '">';
    
        $calendar .= '<thead>';

        $calendar .= '<tr class="calendar-title">';
    
        $calendar .= '<th colspan="7">';
                    
        $calendar .= $date->format('F Y');

        $calendar .= '</th>';

        $calendar .= '</tr>';

        $calendar .= '<tr class="calendar-header">';

        $calendar .= '<th>';
                    
        $calendar .= implode('</th><th>', array('S','M','T','W','T','F','S'));
                
        $calendar .= '</th>';

        $calendar .= '</tr>';
        
        $calendar .= '</thead>';

        $calendar .= '<tbody>';

        $calendar .= '<tr>';

        # padding before the month start date IE. if the month starts on Wednesday
        for ($x = 0; $x < $date->format('w'); $x++) {
            $calendar .= '<td class="pad"> </td>';
        }

        $running_day = clone $date;

        $running_day_count = 1;

        do {
            $events = $this->findEvents($running_day);

            $class = '';

            $event_summary = '';

            if ($events) {
                foreach ($events as $index => $event) {
                    # is the current day the start of the event
                    if ($event->start->format('Y-m-d') == $running_day->format('Y-m-d')) {
                        $class .= $event->mask ? ' mask-start' : '';
                        $class .= ($event->classes) ? ' ' . $event->classes : '';
                        $event_summary .= ($event->summary) ? : '';

                        # is the current day in between the start and end of the event
                    } elseif ($running_day->getTimestamp() > $event->start->getTimestamp()
                        && $running_day->getTimestamp() <    $event->end->getTimestamp()) {
                        $class .= $event->mask ? ' mask' : '';

                        # is the current day the start of the event
                    } elseif ($running_day->format('Y-m-d') == $event->end->format('Y-m-d')) {
                        $class .= $event->mask ? ' mask-end' : '';
                    }
                }
            }

            $today_class = ($running_day->format('Y-m-d') == $today->format('Y-m-d')) ? ' today' : '';

            $calendar .= '<td class="day' . $class . $today_class . '" title="' . htmlentities($event_summary) . '">';

            $calendar .= '<div>';
            
            $calendar .= $running_day->format('j');
            
            $calendar .= '</div>';

            $calendar .= '<div>';
            
            $calendar .= $event_summary;
            
            $calendar .= '</div>';

            $calendar .= '</td>';

            # check if this calendar-row is full and if so push to a new calendar row
            if ($running_day->format('w') == 6) {
                $calendar .= '</tr>';

                # start a new calendar row if there are still days left in the month
                if (($running_day_count + 1) <= $total_days_in_month) {
                    $calendar .= '<tr>';
                }

                # reset padding because its a new calendar row
                $day_padding_offset = 0;
            }

            $running_day->modify('+1 Day');

            $running_day_count++;
        } while ($running_day_count <= $total_days_in_month);

        $padding_at_end_of_month = 7 - $running_day->format('w');

        # padding at the end of the month
        if ($padding_at_end_of_month && $padding_at_end_of_month < 7) {
            for ($x = 1; $x <= $padding_at_end_of_month; $x++) {
                $calendar .= '<td class="pad"> </td>';
            }
        }

        $calendar .= '</tr>';

        $calendar .= '</tbody>';

        $calendar .= '</table>';

        return $calendar;
    }
}

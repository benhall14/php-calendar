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
     * The Day Format.
     *
     * @var string
     */
    private $day_format = 'initials';

    /**
     * Start day of week. Default = 6 (Sunday)
     *
     * @var integer
     */
    private $starting_day = 6;

    /**
     * The day strings. Default EN.
     *
     * @var array
     */
    private $days = [
        'sunday' => [
            'dow' => 0,
            'initials' => 'S',
            'full' => 'Sunday'
        ],
        'monday' => [
            'dow' => 1,
            'initials' => 'M',
            'full' => 'Monday',
        ], 
        'tuesday' => [
            'dow' => 2,
            'initials' => 'T',
            'full' => 'Tuesday',
        ],
        'wednesday' => [
            'dow' => 3,
            'initials' => 'W',
            'full' => 'Wednesday',
        ],
        'thursday' => [
            'dow' => 4,
            'initials' => 'T',
            'full' => 'Thursday',
        ],
        'friday' => [
            'dow' => 5,
            'initials' => 'F',
            'full' => 'Friday',
        ],
        'saturday' => [
            'dow' => 6,
            'initials' => 'S',
            'full' => 'Saturday',
        ],
    ];
    
    /**
     * The month names. Default EN.
     *
     * @var array
     */
    private $months = [
        'january' => 'January', 
        'february' => 'February', 
        'march' => 'March', 
        'april' => 'April', 
        'may' => 'May', 
        'june' => 'June',
        'july' => 'July',
        'august' => 'August',
        'september' => 'September',
        'october' => 'October',
        'november' => 'November',
        'december' => 'December'
    ];

    /**
     * Hide all 'sundays' from the calendar view.
     *
     * @var boolean
     */
    private $hide_sundays = false;
    
    /**
     * Hide all 'mondays' from the calendar view.
     *
     * @var boolean
     */
    private $hide_mondays = false;
    
    /**
     * Hide all 'tuesdays' from the calendar view.
     *
     * @var boolean
     */
    private $hide_tuesdays = false;
    
    /**
     * Hide all 'wednesdays' from the calendar view.
     *
     * @var boolean
     */
    private $hide_wednesdays = false;
    
    /**
     * Hide all 'thursdays' from the calendar view.
     *
     * @var boolean
     */
    private $hide_thursdays = false;
    
    /**
     * Hide all 'fridays' from the calendar view.
     *
     * @var boolean
     */
    private $hide_fridays = false;

    /**
     * Hide all 'saturdays' from the calendar view.
     *
     * @var boolean
     */
    private $hide_saturdays = false;

    /**
     * Sets the array of days. Useful when translating.
     *
     * @param array $initials
     *
     * @return Calendar
     */
    public function setDays($days)
    {
        if (is_array($days) && count($days) == 7) {
            foreach ($days as $day => $data) {
                if (isset($data['initials']) && isset($data['full'])) {
                    $this->days[$day]['initials'] = $data['initials'];
                    $this->days[$day]['full'] = $data['full'];
                }
            }
        }

        return $this;
    }

    public function useSpanish()
    {
        $this->setDays([
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

        $this->setMonths([
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
    }

    /**
     * Sets the array of month names. Useful when translating.
     *
     * @param array $months
     *
     * @return Calendar
     */
    public function setMonths($months)
    {
        if (is_array($months) && count($months) == 12) {
            $this->months = array_merge($this->months, $months);
        }

        return $this;
    }

    public function hideSundays()
    {
        $this->hide_sundays = true;

        return $this;
    }

    public function hideMondays()
    {
        $this->hide_mondays = true;

        return $this;
    }

    public function hideTuesdays()
    {
        $this->hide_tuesdays = true;

        return $this;
    }

    public function hideWednesdays()
    {
        $this->hide_wednesdays = true;

        return $this;
    }

    public function hideThursdays()
    {
        $this->hide_thursdays = true;

        return $this;
    }

    public function hideFridays()
    {
        $this->hide_fridays = true;

        return $this;
    }

    public function hideSaturdays()
    {
        $this->hide_saturdays = true;

        return $this;
    }

    /**
     * Sets the day format flag to return initial day names. This is the default behaviour.
     *
     * @return Calendar
     */
    public function useInitialDayNames()
    {
        $this->day_format = 'initials';

        return $this;
    }

    /**
     * Sets the day format flag to return full day names instead of initials by default.
     *
     * @return Calendar
     */
    public function useFullDayNames()
    {
        $this->day_format = 'full';

        return $this;
    }

    /**
     * Changes the weekly start date to Sunday.
     *
     * @return Calendar
     */
    public function useSundayStartingDate()
    {
        $this->starting_day = 6;

        return $this;
    }

    /**
     * Changes the weekly start date to Monday.
     *
     * @return Calendar
     */
    public function useMondayStartingDate()
    {
        $this->starting_day = 0;

        return $this;
    }

    /**
     * Returns, or prints, the default stylesheet.
     *
     * @param boolean $print
     *
     * @return string
     */
    public function stylesheet($print = true)
    {
        $styles = '<style>.calendar{background:#2ca8c2;color:#fff;width:100%;font-family:Oxygen;table-layout:fixed}.calendar.purple{background:#913ccd}.calendar.pink{background:#f15f74}.calendar.orange{background:#f76d3c}.calendar.yellow{background:#f7d842}.calendar.green{background:#98cb4a}.calendar.grey{background:#839098}.calendar.blue{background:#5481e6}.calendar-title th{font-size:22px;font-weight:700;padding:20px;text-align:center;text-transform:uppercase;background:rgba(0,0,0,.05)}.calendar-header th{padding:10px;text-align:center;background:rgba(0,0,0,.1)}.calendar tbody tr td{text-align:center;vertical-align:top;width:14.28%}.calendar tbody tr td.pad{background:rgba(255,255,255,.1)}.calendar tbody tr td.day div:first-child{padding:4px;line-height:17px;height:25px}.calendar tbody tr td.day div:last-child{font-size:10px;padding:4px;min-height:25px}.calendar tbody tr td.today{background:rgba(0,0,0,.25)}.calendar tbody tr td.mask,.calendar tbody tr td.mask-end,.calendar tbody tr td.mask-start{background:#c23b22}</style>';

        if ($print) {
            echo $styles;

            return;
        }

        return $styles;
    }

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
     * Returns an array of days to loop over.
     *
     * @return array
     */
    public function getDays()
    {
        $array = $this->days;

        if ($this->starting_day == 0) {
            $key = array_key_first($array);
            $first = array_shift($array);
            $array[$key] = $first;
        }

        return $array;
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
     * @param string  $date    The date of this calendar.
     * @param string  $format  The format of the preceding date.
     * 
     * @return string         The calendar
     */
    public function draw($date = false, $color = false)
    {
        $calendar = '';

        $colspan = 7;

        $days = array_keys($this->days);

        foreach ($days as $day) {
            if ($this->{'hide_' . $day . 's'}) {
                $colspan--;
                $calendar .= '<style>.cal-' . $day . '{display:none!important;}</style>';
            }
        }

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
    
        $calendar .= '<th colspan="' . $colspan . '">';
                    
        $calendar .= $this->months[strtolower($date->format('F'))] . ' ' . $date->format('Y');

        $calendar .= '</th>';

        $calendar .= '</tr>';

        $calendar .= '<tr class="calendar-header">';

        foreach ($this->getDays() as $index => $day) {
            
            $calendar .= '<th class="cal-th cal-' . $index . '">' . ($this->day_format == 'full' ? $day['full'] : $day['initials']) . '</th>';

        }

        $calendar .= '</tr>';
        
        $calendar .= '</thead>';

        $calendar .= '<tbody>';

        $calendar .= '<tr>';

        // account for a monday start, if set.
        $weekday = !$this->starting_day ? (($date->format('w') == 0) ? 6 : $date->format('w') - 1) : $date->format('w');

        # padding before the month start date IE. if the month starts on Wednesday
        for ($x = 0; $x < $weekday; $x++) {
            $calendar .= '<td class="pad cal-' . $days[$x] . '"> </td>';
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

            $calendar .= '<td class="day cal-' . strtolower($running_day->format('l')) . ' ' . $class . $today_class . '" title="' . htmlentities($event_summary) . '">';

            $calendar .= '<div>';
            
            $calendar .= $running_day->format('j');
            
            $calendar .= '</div>';

            $calendar .= '<div>';
            
            $calendar .= $event_summary;
            
            $calendar .= '</div>';

            $calendar .= '</td>';

            # check if this calendar-row is full and if so push to a new calendar row
            if ($running_day->format('w') == $this->starting_day) {
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

        if ($this->starting_day == 6) {
            $padding_at_end_of_month = 7 - $running_day->format('w');
        } else {
            $padding_at_end_of_month = ($running_day->format('w') == 0) ? 1 : 7 - ($running_day->format('w')-1);
        }

        # padding at the end of the month
        if ($padding_at_end_of_month && $padding_at_end_of_month < 7) {
            for ($x = 1; $x <= $padding_at_end_of_month; $x++) {
                $offset = (($x - 1) + $running_day->format('w'));
                if ($offset == 7) {
                    $offset = 0;
                }
                
                $calendar .= '<td class="pad cal-' . $days[$offset] . '"> </td>';
            }
        }

        $calendar .= '</tr>';

        $calendar .= '</tbody>';

        $calendar .= '</table>';

        return $calendar;
    }
}

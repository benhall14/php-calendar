<?php

declare(strict_types=1);

namespace benhall14\phpCalendar;

/**
 * Simple PHP Calendar Class.
 *
 * @copyright  Copyright (c) Benjamin Hall
 * @license https://github.com/benhall14/php-calendar
 *
 * @version 1.2
 *
 * @author Benjamin Hall <https://conobe.co.uk>
 */
class Calendar
{
    /**
     * Calendar Type.
     */
    private string $type = 'month';

    /**
     * Time Interval used in the week view.
     * Default is set to 30 minutes.
     */
    private int $time_interval = 30;

    /**
     * The Week View Starting Time.
     * Leave at 00:00 for a full 24-hour calendar.
     */
    private string $start_time = '00:00';

    /**
     * The Week View end time.
     * Leave at 00:00 for a full 24-hour calendar.
     */
    private string $end_time = '00:00';

    /**
     * The Day Format.
     */
    private string $day_format = 'initials';

    /**
     * Start day of week. Default = 6 (Sunday).
     */
    private int $starting_day = 6;

    /**
     * The day strings. Default EN.
     */
    private array $days = [
        'sunday' => [
            'dow' => 0,
            'initials' => 'S',
            'full' => 'Sunday',
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
     */
    private array $months = [
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
        'december' => 'December',
    ];

    /**
     * Table classes that should be injected into the table header.
     */
    private array $table_classes = [];

    /**
     * Hide all 'sundays' from the calendar view.
     */
    private bool $hide_sundays = false;

    /**
     * Hide all 'mondays' from the calendar view.
     */
    private bool $hide_mondays = false;

    /**
     * Hide all 'tuesdays' from the calendar view.
     */
    private bool $hide_tuesdays = false;

    /**
     * Hide all 'wednesdays' from the calendar view.
     */
    private bool $hide_wednesdays = false;

    /**
     * Hide all 'thursdays' from the calendar view.
     */
    private bool $hide_thursdays = false;

    /**
     * Hide all 'fridays' from the calendar view.
     */
    private bool $hide_fridays = false;

    /**
     * Hide all 'saturdays' from the calendar view.
     */
    private bool $hide_saturdays = false;

    /**
     * Sets the array of days. Useful when translating.
     */
    public function setDays(array $days): static
    {
        if (7 == count($days)) {
            foreach ($days as $day => $data) {
                if (isset($data['initials']) && isset($data['full'])) {
                    $this->days[$day]['initials'] = $data['initials'];
                    $this->days[$day]['full'] = $data['full'];
                }
            }
        }

        return $this;
    }

    /**
     * Toggles the calendar locale to Spanish.
     */
    public function useSpanish(): static
    {
        $this->setDays([
            'sunday' => [
                'initials' => 'D',
                'full' => 'Domingo',
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
            'december' => 'Diciembre',
        ]);

        return $this;
    }

    /**
     * Toggles the calendar locale to Greek. Thanks @alinakis.
     */
    public function useGreek(): static
    {
        $this->setDays([
            'sunday' => [
                'initials' => 'Κ',
                'full' => 'Κυριακή',
            ],
            'monday' => [
                'initials' => 'Δ',
                'full' => 'Δευτέρα',
            ],
            'tuesday' => [
                'initials' => 'Τ',
                'full' => 'Τρίτη',
            ],
            'wednesday' => [
                'initials' => 'Τ',
                'full' => 'Τετάρτη',
            ],
            'thursday' => [
                'initials' => 'Π',
                'full' => 'Πέμπτη',
            ],
            'friday' => [
                'initials' => 'Π',
                'full' => 'Παρασκευή',
            ],
            'saturday' => [
                'initials' => 'Σ',
                'full' => 'Σάββατο',
            ],
        ]);

        $this->setMonths([
            'january' => 'Ιανουάριος',
            'february' => 'Φεβρουάριος',
            'march' => 'Μάρτιος',
            'april' => 'Απρίλιος',
            'may' => 'Μάϊος',
            'june' => 'Ιούνιος',
            'july' => 'Ιούλιος',
            'august' => 'Αύγουστος',
            'september' => 'Σεπτέμβριος',
            'october' => 'Οκτώβριος',
            'november' => 'Νοέμβριος',
            'december' => 'Δεκέμβριος',
        ]);

        return $this;
    }

    /**
     * Sets the array of month names. Useful when translating.
     */
    public function setMonths(array $months): static
    {
        if (12 == count($months)) {
            $this->months = array_merge($this->months, $months);
        }

        return $this;
    }

    /**
     * Hide Sundays.
     */
    public function hideSundays(): static
    {
        $this->hide_sundays = true;

        return $this;
    }

    /**
     * Hide Mondays.
     */
    public function hideMondays(): static
    {
        $this->hide_mondays = true;

        return $this;
    }

    /**
     * Hide Tuesdays.
     */
    public function hideTuesdays(): static
    {
        $this->hide_tuesdays = true;

        return $this;
    }

    /**
     * Hide Wednesdays.
     */
    public function hideWednesdays(): static
    {
        $this->hide_wednesdays = true;

        return $this;
    }

    /**
     * Hide Thursdays.
     */
    public function hideThursdays(): static
    {
        $this->hide_thursdays = true;

        return $this;
    }

    /**
     * Hide Fridays.
     */
    public function hideFridays(): static
    {
        $this->hide_fridays = true;

        return $this;
    }

    /**
     * Hide Saturdays.
     */
    public function hideSaturdays(): static
    {
        $this->hide_saturdays = true;

        return $this;
    }

    /**
     * Sets the day format flag to return initial day names. This is the default behaviour.
     */
    public function useInitialDayNames(): static
    {
        $this->day_format = 'initials';

        return $this;
    }

    /**
     * Sets the day format flag to return full day names instead of initials by default.
     */
    public function useFullDayNames(): static
    {
        $this->day_format = 'full';

        return $this;
    }

    /**
     * Changes the weekly start date to Sunday.
     */
    public function useSundayStartingDate(): static
    {
        $this->starting_day = 6;

        return $this;
    }

    /**
     * Changes the weekly start date to Monday.
     */
    public function useMondayStartingDate(): static
    {
        $this->starting_day = 0;

        return $this;
    }

    /**
     * Returns, or prints, the default stylesheet.
     */
    public function stylesheet(bool $print = true): ?string
    {
        $styles = '<style>.weekly-calendar{min-width:850px;}.calendar{background:#2ca8c2;color:#fff;width:100%;font-family:Oxygen;table-layout:fixed}.calendar.purple{background:#913ccd}.calendar.pink{background:#f15f74}.calendar.orange{background:#f76d3c}.calendar.yellow{background:#f7d842}.calendar.green{background:#98cb4a}.calendar.grey{background:#839098}.calendar.blue{background:#5481e6}.calendar-title th{font-size:22px;font-weight:700;padding:20px;text-align:center;text-transform:uppercase;background:rgba(0,0,0,.05)}.calendar-header th{padding:10px;text-align:center;background:rgba(0,0,0,.1)}.calendar tbody tr td{text-align:center;vertical-align:top;width:14.28%}.calendar tbody tr td.pad{background:rgba(255,255,255,.1)}.calendar tbody tr td.day div:first-child{padding:4px;line-height:17px;height:25px}.calendar tbody tr td.day div:last-child{font-size:10px;padding:4px;min-height:25px}.calendar tbody tr td.today{background:rgba(0,0,0,.25)}.calendar tbody tr td.mask,.calendar tbody tr td.mask-end,.calendar tbody tr td.mask-start{background:#c23b22}.calendar .cal-weekview-time{padding:4px 2px 2px 4px;}.calendar .cal-weekview-time > div{background:rgba(0,0,0,0.03);padding:10px;min-height:50px;}.calendar .cal-weekview-event.mask-start,.calendar .cal-weekview-event.mask,.calendar .cal-weekview-event.mask-end{background:#C23B22;margin-bottom:3px;padding:5px;}.calendar .cal-weekview-time-th{background:rgba(0,0,0,.1);}.calendar .cal-weekview-time-th > div{padding:10px;min-height:50px;}.calendar .event-summary-row{display:block;}</style>';
        $styles .= '<style>@media screen and (max-width:768px){#weekly-calendar-container{display: block;overflow-x: scroll;overflow-y: hidden;white-space: nowrap;}}</style>';

        if ($print) {
            echo $styles;

            return null;
        }

        return $styles;
    }

    /**
     * The events array.
     */
    private array $events = [];

    /**
     * Add an event to the current calendar instantiation.
     *
     * @param string $start the start date in Y-m-d format
     * @param string $end the end date in Y-m-d format
     * @param string $summary the summary string of the event
     * @param bool $mask the masking class
     * @param mixed $classes (optional) A list of classes to use for the event
     * @param mixed $event_box_classes (optional) A list of classes to apply to the event summary box
     *
     * @return object return this object for chain-ability
     */
    public function addEvent(
        $start,
        $end,
        $summary = false,
        $mask = false,
        mixed $classes = false,
        mixed $event_box_classes = false,
    ): static {
        $event = new \stdClass();

        if (str_contains($start, ' ')) {
            $event->start = \DateTime::createFromFormat('Y-m-d H:i', $start);
        } else {
            $event->start = \DateTime::createFromFormat('Y-m-d', $start);
            $event->start->setTime(0, 0, 0);
        }

        if (str_contains($end, ' ')) {
            $event->end = \DateTime::createFromFormat('Y-m-d H:i', $end);
        } else {
            $event->end = \DateTime::createFromFormat('Y-m-d', $end);
            $event->end->setTime(23, 59, 59);
        }

        $event->mask = $mask;

        if ($classes) {
            if (is_array($classes)) {
                $classes = implode(' ', $classes);
            }

            $event->classes = $classes;
        } else {
            $event->classes = false;
        }

        if ($event_box_classes) {
            if (is_array($event_box_classes)) {
                $event_box_classes = implode(' ', $event_box_classes);
            }

            $event->event_box_classes = $event_box_classes;
        } else {
            $event->event_box_classes = false;
        }

        $event->summary = $summary ?: false;

        $this->events[] = $event;

        return $this;
    }

    /**
     * Add an array of events using $this->addEvent();.
     *
     * Each array element must have the following:
     *     'start'  =>   start date in Y-m-d format.
     *     'end'    =>   end date in Y-m-d format.
     *     (optional) 'mask' => a masking class name.
     *     (optional) 'classes' => custom classes to include.
     *
     * @param array $events the events array
     *
     * @return object return this object for chain-ability
     */
    public function addEvents(array $events): static
    {
        foreach ($events as $event) {
            if (isset($event['start']) && isset($event['end'])) {
                $classes = $event['classes'] ?? false;
                $mask = isset($event['mask']) && (bool) $event['mask'];
                $summary = $event['summary'] ?? false;
                $event_box_classes = $event['event_box_classes'] ?? false;
                $this->addEvent($event['start'], $event['end'], $summary, $mask, $classes, $event_box_classes);
            }
        }

        return $this;
    }

    /**
     * Remove all events tied to this calendar.
     *
     * @return object return this object for chain-ability
     */
    public function clearEvents(): static
    {
        $this->events = [];

        return $this;
    }

    /**
     * Use Month View.
     */
    public function useMonthView(): static
    {
        $this->type = 'month';

        return $this;
    }

    /**
     * Use Week View.
     */
    public function useWeekView(): static
    {
        $this->type = 'week';

        return $this;
    }

    /**
     * Add any custom table classes that should be injected into the calender table header.
     *
     * This can be a space separated list, or an array of classes.
     */
    public function addTableClasses(mixed $classes): static
    {
        if (!is_array($classes)) {
            $classes = explode(' ', $classes);
        }

        foreach ($classes as $class) {
            $this->table_classes[] = $class;
        }

        return $this;
    }

    /**
     * Returns an array of days to loop over.
     */
    public function getDays(): array
    {
        $array = $this->days;

        if (0 == $this->starting_day) {
            $key = array_key_first($array);
            $first = array_shift($array);
            $array[$key] = $first;
        }

        return $array;
    }


    /**
     * Find an event from the internal pool.
     *
     * @param \DateTime $date the date to match an event for
     * @param string $view The type of view - either Week or Month
     *
     * @return array either an array of events or false
     */
    private function findEvents(\DateTime $date, string $view = 'month'): array
    {
        $found_events = [];

            foreach ($this->events as $event) {
                if ('month' === $view) {
                    // Extracting and comparing only the dates (Y-m-d) to avoid time-based exclusion
                    $eventStartDate = (new \DateTime($event->start->format('Y-m-d')))->getTimestamp();
                    $eventEndDate = (new \DateTime($event->end->format('Y-m-d')))->getTimestamp();
                    $inputDate = (new \DateTime($date->format('Y-m-d')))->getTimestamp();
                    if ($inputDate >= $eventStartDate && $inputDate <= $eventEndDate) {
                        $found_events[] = $event;
                    }
                } elseif ($date->getTimestamp() >= $event->start->getTimestamp() && $date->getTimestamp() <= $event->end->getTimestamp()) {
                    $found_events[] = $event;
                }
            }

        return $found_events;
    }

    /**
     * Returns the calendar as a month view.
     */
    public function asMonthView(?string $date = null, ?string $color = null): string
    {
        $calendar = '';

        $colspan = 7;

        $days = array_keys($this->days);

        foreach ($days as $day) {
            if ($this->{'hide_'.$day.'s'}) {
                --$colspan;
                $calendar .= '<style>.cal-'.$day.'{display:none!important;}</style>';
            }
        }

        if ($date) {
            $date = \DateTime::createFromFormat('Y-m-d', $date);
            $date->modify('first day of this month');
        } else {
            $date = \Carbon\Carbon::now();
            $date->modify('first day of this month');
        }

        $today = \Carbon\Carbon::now();

        $total_days_in_month = (int) $date->format('t');

        $color = $color ?: '';

        $calendar .= '<table class="calendar '.$color.' '.implode(' ', $this->table_classes).'">';

        $calendar .= '<thead>';

        $calendar .= '<tr class="calendar-title">';

        $calendar .= '<th colspan="'.$colspan.'">';

        $calendar .= $this->months[strtolower($date->format('F'))].' '.$date->format('Y');

        $calendar .= '</th>';

        $calendar .= '</tr>';

        $calendar .= '<tr class="calendar-header">';

        foreach ($this->getDays() as $index => $day) {
            $calendar .= '<th class="cal-th cal-th-'.$index.'">'.('full' === $this->day_format ? $day['full'] : $day['initials']).'</th>';
        }

        $calendar .= '</tr>';

        $calendar .= '</thead>';

        $calendar .= '<tbody>';

        $week = 1;
        $calendar .= '<tr class="cal-week-'.$week.'">';

        // account for a monday start, if set.
        $weekday = 0 !== $this->starting_day ? ($date->format('w')) : ((0 == $date->format('w')) ? 6 : $date->format('w') - 1);

        // padding before the month start date IE. if the month starts on Wednesday
        for ($x = 0; $x < $weekday; ++$x) {
            $calendar .= '<td class="pad cal-'.$days[$x].'"> </td>';
        }

        $running_day = clone $date;

        $running_day_count = 1;

        do {
            $events = $this->findEvents($running_day, 'month');

            $class = '';

            $event_summary = '';

            foreach ($events as $event) {
                // is the current day the start of the event
                if ($event->start->format('Y-m-d') == $running_day->format('Y-m-d')) {
                    $class .= $event->mask ? ' mask-start' : '';
                    $class .= ($event->classes) ? ' '.$event->classes : '';
                    $event_summary .= ($event->summary) ? '<span class="event-summary-row '.$event->event_box_classes.'">'.$event->summary.'</span>' : '';

                // is the current day in between the start and end of the event
                } elseif (
                    $running_day->getTimestamp() > $event->start->getTimestamp()
                    && $running_day->getTimestamp() < $event->end->getTimestamp()
                ) {
                    $class .= $event->mask ? ' mask' : '';

                // is the current day the start of the event
                } elseif ($running_day->format('Y-m-d') == $event->end->format('Y-m-d')) {
                    $class .= $event->mask ? ' mask-end' : '';
                }
            }

            $today_class = ($running_day->format('Y-m-d') == $today->format('Y-m-d')) ? ' today' : '';

            $calendar .= '<td class="day cal-day cal-day-'.strtolower($running_day->format('l')).' '.$class.$today_class.'" title="'.htmlentities(strip_tags($event_summary)).'">';

            $calendar .= '<div class="cal-day-box">';

            $calendar .= $running_day->format('j');

            $calendar .= '</div>';

            $calendar .= '<div class="cal-event-box">';

            $calendar .= $event_summary;

            $calendar .= '</div>';

            $calendar .= '</td>';

            // check if this calendar-row is full and if so push to a new calendar row
            if ($running_day->format('w') == $this->starting_day) {
                $calendar .= '</tr>';

                // start a new calendar row if there are still days left in the month
                if (($running_day_count + 1) <= $total_days_in_month) {
                    ++$week;
                    $calendar .= '<tr class="cal-week-'.$week.'">';
                }

                // reset padding because its a new calendar row
                $day_padding_offset = 0;
            }

            $running_day->modify('+1 Day');

            ++$running_day_count;
        } while ($running_day_count <= $total_days_in_month);

        if (6 == $this->starting_day) {
            $padding_at_end_of_month = 7 - $running_day->format('w');
        } else {
            $padding_at_end_of_month = (0 == $running_day->format('w')) ? 1 : 7 - ($running_day->format('w') - 1);
        }

        // padding at the end of the month
        if ($padding_at_end_of_month && $padding_at_end_of_month < 7) {
            for ($x = 1; $x <= $padding_at_end_of_month; ++$x) {
                $offset = (($x - 1) + (int) $running_day->format('w'));
                if (7 == $offset) {
                    $offset = 0;
                }

                $calendar .= '<td class="pad cal-'.$days[$offset].'"> </td>';
            }
        }

        $calendar .= '</tr>';

        $calendar .= '</tbody>';

        return $calendar.'</table>';
    }

    /**
     * Sets the time formats when overriding the default week view calendar start/end time and intervals.
     */
    public function setTimeFormat(string $start_time = '00:00', string $end_time = '00:00', int $minutes = 30): static
    {
        $this->start_time = $start_time;
        $this->end_time = $end_time;
        $this->time_interval = $minutes;

        return $this;
    }

    /**
     * Get an array of time slots.
     */
    public function getTimes(): array
    {
        $start_time = \DateTime::createFromFormat('H:i', $this->start_time);
        $end_time = \DateTime::createFromFormat('H:i', $this->end_time);
        if ($start_time == $end_time) {
            $end_time->modify('+1 day');
        }

        $times = [];
        while ($start_time->format('Ymd H:i') <= $end_time->format('Ymd H:i')) {
            if (!in_array($start_time->format('H:i'), $times)) {
                $times[] = $start_time->format('H:i');
            }

            $start_time->modify('+'.$this->time_interval.' minutes');
        }

        return $times;
    }

    /**
     * Returns the calendar output as a week view.
     */
    public function asWeekView(?string $date = null, ?string $color = null): string
    {
        $calendar = '<div class="weekly-calendar-container">';

        $colspan = 7;

        $days = array_keys($this->days);

        foreach ($days as $day) {
            if ($this->{'hide_'.$day.'s'}) {
                --$colspan;
                $calendar .= '<style>.cal-'.$day.'{display:none!important;}</style>';
            }
        }

        $date = $date ? \DateTime::createFromFormat('Y-m-d', $date) : \Carbon\Carbon::now();

        if (6 == $this->starting_day) {
            $date->modify('last sunday');
        } elseif (0 == $this->starting_day) {
            $date->modify('last monday');
        }

        $dates = [];

        do {
            $dates[] = clone $date;
            $date->modify('+1 Day');
        } while (count($dates) < 7);

        $today = \Carbon\Carbon::now();

        $color = $color ?: '';

        $calendar .= '<table class="weekly-calendar calendar '.$color.' '.implode(' ', $this->table_classes).'">';

        $calendar .= '<thead>';

        $calendar .= '<tr class="calendar-header">';

        $calendar .= '<th></th>';

        $days = $this->getDays();
        foreach ($dates as $date) {
            $calendar .= '<th class="cal-th cal-th-'.strtolower($date->format('l')).'">';
            $calendar .= '<div class="cal-weekview-dow">'.$days[strtolower($date->format('l'))]['full'].'</div>';
            $calendar .= '<div class="cal-weekview-day">'.$date->format('j').'</div>';
            $calendar .= '<div class="cal-weekview-month">'.$this->months[strtolower($date->format('F'))].'</div>';
            $calendar .= '</th>';
        }

        $calendar .= '</tr>';

        $calendar .= '</thead>';

        $calendar .= '<tbody>';

        $used_events = [];

        foreach ($this->getTimes() as $time) {
            $calendar .= '<tr>';

            $start_time = $time;
            $end_time = date('H:i', strtotime($time.' + '.$this->time_interval.' minutes'));

            $calendar .= '<td class="cal-weekview-time-th"><div>'.$start_time.' - '.$end_time.'</div></td>';

            foreach ($dates as $date) {
                $datetime = $date->setTime(substr($time, 0, 2), substr($time, 3, 2));

                $events = $this->findEvents($datetime, 'week');

                $today_class = ($date->format('Y-m-d H') == $today->format('Y-m-d H')) ? ' today' : '';

                $calendar .= '<td class="cal-weekview-time '.$today_class.'">';

                $calendar .= '<div>';

                foreach ($events as $event) {
                    $class = '';

                    $event_summary = '';

                    if (in_array($event, $used_events)) {
                        $event_summary = '&nbsp;';
                    } else {
                        $event_summary = ($event->summary) ?: '';
                        $used_events[] = $event;
                    }

                    // is the current day the start of the event
                    if ($event->start->format('Y-m-d') == $date->format('Y-m-d')) {
                        $class .= $event->mask ? ' mask-start' : '';
                        $class .= ($event->classes) ? ' '.$event->classes : '';
                    // is the current day in between the start and end of the event
                    } elseif (
                        $date->getTimestamp() > $event->start->getTimestamp()
                        && $date->getTimestamp() < $event->end->getTimestamp()
                    ) {
                        $class .= $event->mask ? ' mask' : '';

                    // is the current day the start of the event
                    } elseif ($date->format('Y-m-d') == $event->end->format('Y-m-d')) {
                        $class .= $event->mask ? ' mask-end' : '';
                    }

                    $calendar .= '<div class="cal-weekview-event '.$class.'">';
                    $calendar .= $event_summary;
                    $calendar .= '</div>';
                }

                $calendar .= '</div>';

                $calendar .= '</td>';
            }

            $calendar .= '<tr/>';
        }

        $calendar .= '</tbody>';

        $calendar .= '</table>';

        return $calendar.'</div>';
    }

    /**
     * Draw the calendar and return HTML output.
     *
     * @param string $date the date of this calendar
     *
     * @return string The calendar
     */
    public function draw(?string $date = null, ?string $color = null): string
    {
        if ('week' === $this->type) {
            return $this->asWeekView($date, $color);
        }

        return $this->asMonthView($date, $color);
    }

    /**
     * Shortcut helper to print the calendar output.
     */
    public function display(?string $date = null, ?string $color = null): void
    {
        echo $this->stylesheet();
        echo $this->draw($date, $color);
    }
}

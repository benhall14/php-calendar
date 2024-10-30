<?php

declare(strict_types=1);

namespace benhall14\phpCalendar\Views;

use benhall14\phpCalendar\Event;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use DateTimeInterface;

class Month extends View
{
    /**
     * @var array{color: string, startDate: (string|CarbonInterface)}
     */
    private array $options = [
        'color' => '',
        'startDate' => '',
    ];

    protected function findEvents(CarbonInterface $start, CarbonInterface $end): array
    {
        // Extracting and comparing only the dates (Y-m-d) to avoid time-based exclusion
        $callback = fn (Event $event,
        ): bool => $start->greaterThanOrEqualTo((clone $event->start)->startOfDay()) && $start->lessThanOrEqualTo((clone $event->end)->endOfDay());

        return array_filter($this->calendar->getEvents(), $callback);
    }

    /**
     * Returns the calendar as a month view.
     *
     * @param array{color?: string, startDate?: (string|DateTimeInterface)} $options
     */
    public function render(array $options): string
    {
        $this->options = $this->initializeOptions($options);
        $calendar = $this->makeHiddenStyles();

        $startDate = $this->options['startDate'];

        $calendar .= sprintf('<table class="calendar  %s %s ">', $this->options['color'], $this->config->table_classes);

        $calendar .= $this->getHeader($startDate);

        $calendar .= '<tbody>';

        $calendar .= '<tr class="cal-week-'.$startDate->weekOfMonth.'">';
        $calendar .= $this->paddingBeforeTheMonthStartDate($startDate);

        $carbonPeriod = $startDate->locale($this->config->locale)->toPeriod($startDate->daysInMonth());
        foreach ($carbonPeriod->toArray() as $carbon) {
            $calendar .= $this->renderDay($carbon);
        }

        $calendar .= $this->paddingAfterTheMonthEndDate($carbonPeriod->last());

        $calendar .= '</tr>';

        $calendar .= '</tbody>';

        return $calendar.'</table>';
    }

    public function makeHiddenStyles(): string
    {
        $hiddenDays = $this->config->getHiddenDays();

        $style = '';
        foreach ($hiddenDays as $hiddenDay) {
            $day = strtolower($hiddenDay);
            $style .= sprintf('.cal-th-%s,.cal-day-%s{display:none!important;}', $day, $day);
        }

        return [] !== $hiddenDays ? sprintf('<style>%s</style>', $style) : '';
    }

    protected function getHeader(CarbonInterface $startDate): string
    {
        $string = '<thead>';

        $string .= '<tr class="calendar-title">';

        $colspan = 7 - count($this->config->getHiddenDays());
        $string .= '<th colspan="'.$colspan.'">';

        $string .= ucfirst($startDate->locale($this->config->locale)->monthName).' '.$startDate->year;

        $string .= '</th>';

        $string .= '</tr>';
        $string .= '<tr class="calendar-header">';

        $carbonPeriod = Carbon::now()->locale($this->config->locale)->startOfWeek($this->config->starting_day)->toPeriod(7);

        foreach ($carbonPeriod->toArray() as $carbon) {
            $string .= '<th class="cal-th cal-th-'.strtolower($carbon->englishDayOfWeek).'">'.ucfirst('full' === $this->config->day_format ? $carbon->dayName : mb_str_split($carbon->minDayName)[0]).'</th>';
        }

        $string .= '</tr>';

        return $string.'</thead>';
    }

    protected function paddingBeforeTheMonthStartDate(CarbonInterface $currentDay): string
    {
        $padding = $currentDay->getDaysFromStartOfWeek($this->config->starting_day);

        if (0 === $padding) {
            return '';
        }

        $string = '';
        foreach (array_reverse(range(1, $padding)) as $num) {
            $day = $currentDay->clone()->subDays($num);
            $string .= '<td class="pad cal-day-'.strtolower($day->englishDayOfWeek).'"> </td>';
        }

        return $string;
    }

    protected function paddingAfterTheMonthEndDate(CarbonInterface $currentDay): string
    {
        $padding = 6 - $currentDay->getDaysFromStartOfWeek($this->config->starting_day);

        if (0 === $padding) {
            return '';
        }

        $string = '';
        foreach (range(1, $padding) as $num) {
            $day = $currentDay->clone()->addDays($num);
            $string .= '<td class="pad cal-day-'.strtolower($day->englishDayOfWeek).'"> </td>';
        }

        return $string;
    }

    protected function renderDay(CarbonInterface $runningDay): string
    {
        $events = $this->findEvents((clone $runningDay)->startOfDay(), (clone $runningDay)->endOfDay());

        $classes = '';
        $event_summary = '';
        $today_class = $runningDay->isToday() ? ' today' : '';
        $string = '';
        foreach ($events as $event) {
            // is the current day the start of the event
            if ($event->start->isSameDay($runningDay)) {
                $classes .= $event->mask ? ' mask-start ' : '';
                $classes .= $event->classes;
                $event_summary .= ($event->summary) ? '<span class="event-summary-row '.$event->box_classes.'">'.$event->summary.'</span>' : '';

            // is the current day in between the start and end of the event
            } elseif ($runningDay->betweenExcluded($event->start, $event->end)) {
                $classes .= $event->mask ? ' mask ' : '';

            // is the current day the start of the event
            } elseif ($runningDay->isSameDay($event->end)) {
                $classes .= $event->mask ? ' mask-end ' : '';
            }
        }

        $dayRender = '<td class="day cal-day cal-day-'.strtolower($runningDay->englishDayOfWeek).' '.$classes.$today_class.'" title="'.htmlentities(strip_tags($event_summary)).'">';

        $dayRender .= '<div class="cal-day-box">';

        $dayRender .= $runningDay->day;

        $dayRender .= '</div>';

        $dayRender .= '<div class="cal-event-box">';

        $dayRender .= $event_summary;

        $dayRender .= '</div>';

        $dayRender .= '</td>';

        // check if this calendar-row is full and if so push to a new calendar row
        if ($runningDay->dayOfWeek === $this->config->starting_day) {
            $string .= '</tr>';
            // start a new calendar row if there are still days left in the month
            $string .= '<tr class="cal-week-'.$runningDay->weekOfMonth.'">';
        }

        return $string.$dayRender;
    }

    /**
     * @param array{color?: string, startDate?: (string|DateTimeInterface)} $options
     *
     * @return array{color: string, startDate: (string|CarbonInterface)}
     */
    public function initializeOptions(array $options): array
    {
        return [
            'color' => $options['color'] ?? '',
            'startDate' => Carbon::parse($options['startDate'] ?? null)->firstOfMonth(),
        ];
    }
}

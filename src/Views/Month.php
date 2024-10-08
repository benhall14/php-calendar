<?php

declare(strict_types=1);

namespace benhall14\phpCalendar\Views;

use DateTimeInterface;
use benhall14\phpCalendar\Event;
use Carbon\Carbon;
use Carbon\CarbonInterface;

class Month extends View
{
    protected function findEvents(CarbonInterface $start, CarbonInterface $end): array
    {
        // Extracting and comparing only the dates (Y-m-d) to avoid time-based exclusion
        $callback = fn (Event $event,
        ): bool => $start->greaterThanOrEqualTo((clone $event->start)->startOfDay()) && $start->lessThanOrEqualTo((clone $event->end)->endOfDay());

        return array_filter($this->calendar->getEvents(), $callback);
    }

    /**
     * Returns the calendar as a month view.
     */
    public function render(DateTimeInterface|string|null $startDate = null, string $color = ''): string
    {
        $calendar = '';

        $colspan = 7;

        foreach (array_intersect($this->config->hiddenDays, Carbon::getDays()) as $day) {
            --$colspan;
            $calendar .= '<style>.cal-th-'.strtolower($day).',.cal-day-'.strtolower($day).'{display:none!important;}</style>';
        }

        $startDate = Carbon::parse($startDate)->firstOfMonth();

        $total_days_in_month = $startDate->daysInMonth();

        $calendar .= sprintf('<table class="calendar  %s %s ">', $color, $this->config->table_classes);

        $calendar .= '<thead>';

        $calendar .= '<tr class="calendar-title">';

        $calendar .= '<th colspan="'.$colspan.'">';

        $calendar .= ucfirst($startDate->locale($this->config->locale)->monthName).' '.$startDate->year;

        $calendar .= '</th>';

        $calendar .= '</tr>';
        $calendar .= '<tr class="calendar-header">';

        $carbonPeriod = Carbon::now()->locale($this->config->locale)->startOfWeek($this->config->starting_day)->toPeriod(7);

        foreach ($carbonPeriod->toArray() as $day) {
            $calendar .= '<th class="cal-th cal-th-'.strtolower($day->englishDayOfWeek).'">'.ucfirst('full' === $this->config->day_format ? $day->dayName : mb_str_split($day->minDayName)[0]).'</th>';
        }

        $calendar .= '</tr>';

        $calendar .= '</thead>';

        $calendar .= '<tbody>';

        $week = 1;
        $calendar .= '<tr class="cal-week-'.$week.'">';

        // padding before the month start date IE. if the month starts on Wednesday
        for ($x = 0; $x < $startDate->dayOfWeek; ++$x) {
            $calendar .= '<td class="pad cal-'.strtolower(Carbon::now()->dayOfWeek($x)->englishDayOfWeek).'"> </td>';
        }

        $running_day = $startDate->clone();

        $running_day_count = 1;

        do {
            $events = $this->findEvents((clone $running_day)->startOfDay(), (clone $running_day)->endOfDay());

            $classes = '';

            $event_summary = '';
            $today_class = $running_day->isToday() ? ' today' : '';

            foreach ($events as $event) {
                // is the current day the start of the event
                if ($event->start->isSameDay($running_day)) {
                    $classes .= $event->mask ? ' mask-start' : '';
                    $classes .= $event->classes;
                    $event_summary .= ($event->summary) ? '<span class="event-summary-row '.$event->box_classes.'">'.$event->summary.'</span>' : '';

                // is the current day in between the start and end of the event
                } elseif ($running_day->betweenExcluded($event->start, $event->end)) {
                    $classes .= $event->mask ? ' mask' : '';

                // is the current day the start of the event
                } elseif ($running_day->isSameDay($event->end)) {
                    $classes .= $event->mask ? ' mask-end' : '';
                }
            }

            $dayRender = '<td class="day cal-day cal-day-'.strtolower($running_day->englishDayOfWeek).' '.$classes.$today_class.'" title="'.htmlentities(strip_tags($event_summary)).'">';

            $dayRender .= '<div class="cal-day-box">';

            $dayRender .= $running_day->day;

            $dayRender .= '</div>';

            $dayRender .= '<div class="cal-event-box">';

            $dayRender .= $event_summary;

            $dayRender .= '</div>';

            $dayRender .= '</td>';

            // check if this calendar-row is full and if so push to a new calendar row
            if ($running_day->dayOfWeek == $this->config->starting_day) {
                $calendar .= '</tr>';

                // start a new calendar row if there are still days left in the month
                if (($running_day_count + 1) <= $total_days_in_month) {
                    ++$week;
                    $calendar .= '<tr class="cal-week-'.$week.'">';
                }

                // reset padding because its a new calendar row
                $day_padding_offset = 0;
            }

            $calendar .= $dayRender;
            $running_day->addDay();

            ++$running_day_count;
        } while ($running_day_count <= $total_days_in_month);

        if (0 == $this->config->starting_day) {
            $padding_at_end_of_month = 7 - $running_day->dayOfWeek;
        } else {
            $padding_at_end_of_month = (0 == $running_day->dayOfWeek) ? 1 : 7 - ($running_day->dayOfWeek - 1);
        }

        // padding at the end of the month
        if ($padding_at_end_of_month && $padding_at_end_of_month < 7) {
            for ($x = 1; $x <= $padding_at_end_of_month; ++$x) {
                $offset = (($x - 1) + $running_day->dayOfWeek);
                if (7 == $offset) {
                    $offset = 0;
                }

                $calendar .= '<td class="pad cal-'.strtolower(Carbon::now()->dayOfWeek($offset)->englishDayOfWeek).'"> </td>';
            }
        }

        $calendar .= '</tr>';

        $calendar .= '</tbody>';

        return $calendar.'</table>';
    }
}

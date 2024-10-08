<?php

declare(strict_types=1);

namespace benhall14\phpCalendar\Views;

use DateTimeInterface;
use benhall14\phpCalendar\Event;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Carbon\CarbonInterval;

class Week extends View
{
    protected function findEvents(CarbonInterface $start, CarbonInterface $end): array
    {
        $callback = fn (Event $event): bool => $event->start->betweenIncluded($start, $end)
            || $event->end->betweenIncluded($start, $end)
            || $end->betweenIncluded($event->start, $event->end);

        return array_filter($this->calendar->getEvents(), $callback);
    }

    /**
     * Get an array of time slots.
     *
     * @return list<string>
     */
    public function getTimes(): array
    {
        $start_time = Carbon::createFromFormat('H:i', $this->config->start_time);
        $end_time = Carbon::createFromFormat('H:i', $this->config->end_time);
        if ($start_time->equalTo($end_time)) {
            $end_time->addDay();
        }

        $carbonPeriod = CarbonInterval::minutes($this->config->time_interval)->toPeriod($this->config->start_time, $end_time);

        $times = [];
        foreach ($carbonPeriod->toArray() as $carbon) {
            $times[] = $carbon->format('H:i');
        }

        return array_unique($times);
    }

    /**
     * Returns the calendar output as a week view.
     */
    public function render(DateTimeInterface|string|null $startDate = null, string $color = ''): string
    {
        $calendar = '<div class="weekly-calendar-container">';

        $colspan = 7;

        foreach (array_intersect($this->config->hiddenDays, Carbon::getDays()) as $day) {
            --$colspan;
            $calendar .= '<style>.cal-'.strtolower($day).',.cal-day-'.strtolower($day).'{display:none!important;}</style>';
        }

        $startDate = Carbon::parse($startDate);

        if ($this->config->starting_day !== $startDate->dayOfWeek) {
            if (0 === $this->config->starting_day) {
                $startDate->previous('sunday');
            } elseif (1 == $this->config->starting_day) {
                $startDate->previous('monday');
            }
        }

        $carbonPeriod = $startDate->locale($this->config->locale)->toPeriod(7);

        $today = Carbon::now();

        $calendar .= '<table class="weekly-calendar calendar '.$color.' '.$this->config->table_classes.'">';

        $calendar .= '<thead>';

        $calendar .= '<tr class="calendar-header">';

        $calendar .= '<th></th>';

        /* @var Carbon $date */
        foreach ($carbonPeriod->toArray() as $carbon) {
            $calendar .= '<th class="cal-th cal-th-'.strtolower($carbon->englishDayOfWeek).'">';
            $calendar .= '<div class="cal-weekview-dow">'.ucfirst($carbon->localeDayOfWeek).'</div>';
            $calendar .= '<div class="cal-weekview-day">'.$carbon->day.'</div>';
            $calendar .= '<div class="cal-weekview-month">'.ucfirst($carbon->localeMonth).'</div>';
            $calendar .= '</th>';
        }

        $calendar .= '</tr>';

        $calendar .= '</thead>';

        $calendar .= '<tbody>';

        $used_events = [];

        foreach ($this->getTimes() as $time) {
            $calendar .= '<tr>';

            $start_time = $time;
            $end_time = date('H:i', strtotime($time.' + '.$this->config->time_interval.' minutes'));

            $calendar .= '<td class="cal-weekview-time-th"><div>'.$start_time.' - '.$end_time.'</div></td>';

            foreach ($carbonPeriod->toArray() as $carbon) {
                $datetime = $carbon->setTimeFrom($time);

                $events = $this->findEvents($datetime, $datetime->clone()->addMinutes($this->config->time_interval));

                $today_class = $carbon->isSameHour($today) ? ' today' : '';

                $calendar .= '<td class="cal-weekview-time '.$today_class.'">';

                $calendar .= '<div>';

                foreach ($events as $event) {
                    $classes = '';

                    if (in_array($event, $used_events)) {
                        $event_summary = '&nbsp;';
                    } else {
                        $event_summary = $event->summary;
                        $used_events[] = $event;
                    }

                    // is the current day the start of the event
                    if ($event->start->isSameDay($carbon)) {
                        $classes .= $event->mask ? ' mask-start' : '';
                        $classes .= $event->classes;
                    // is the current day in between the start and end of the event
                    } elseif ($carbon->betweenExcluded($event->start, $event->end)) {
                        $classes .= $event->mask ? ' mask' : '';

                    // is the current day the start of the event
                    } elseif ($carbon->isSameDay($event->end)) {
                        $classes .= $event->mask ? ' mask-end' : '';
                    }

                    $calendar .= '<div class="cal-weekview-event '.$classes.'">';
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
}

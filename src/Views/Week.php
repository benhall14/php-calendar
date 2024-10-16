<?php

declare(strict_types=1);

namespace benhall14\phpCalendar\Views;

use DateTimeInterface;
use benhall14\phpCalendar\Event;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Carbon\CarbonInterval;
use Carbon\CarbonPeriod;

class Week extends View
{
    /**
     * @var list<Event>
     */
    protected array $usedEvents = [];

    protected function findEvents(CarbonInterface $start, CarbonInterface $end): array
    {
        $callback = fn (Event $event): bool => $event->start->betweenIncluded($start, $end)
            || $event->end->betweenIncluded($start, $end)
            || $end->betweenIncluded($event->start, $event->end);

        return array_filter($this->calendar->getEvents(), $callback);
    }

    /**
     * Returns the calendar output as a week view.
     */
    public function render(DateTimeInterface|string|null $startDate = null, string $color = ''): string
    {
        $startDate = $this->sanitizeStartDate(Carbon::parse($startDate));
        $carbonPeriod = $startDate->locale($this->config->locale)->toPeriod(7);
        $calendar = [
            '<div class="weekly-calendar-container">',
            '<table class="weekly-calendar calendar '.$color.' '.$this->config->table_classes.'">',
            $this->makeHeader($carbonPeriod),
            '<tbody>',
            $this->renderBlocks($carbonPeriod),
            '</tbody>',
            '</table>',
            '</div>',
        ];

        return implode('', $calendar);
    }

    /**
     * Get an array of time slots.
     *
     * @return list<string>
     */
    protected function getTimes(): array
    {
        $start_time = Carbon::createFromFormat('H:i', $this->config->start_time);
        $end_time = Carbon::createFromFormat('H:i', $this->config->end_time);
        if ($start_time->equalTo($end_time)) {
            $end_time->addDay();
        }

        $carbonPeriod = CarbonInterval::minutes($this->config->time_interval)->toPeriod($this->config->start_time,
            $end_time);

        $times = [];
        foreach ($carbonPeriod->toArray() as $carbon) {
            $times[] = $carbon->format('H:i');
        }

        return array_unique($times);
    }

    protected function sanitizeStartDate(CarbonInterface $startDate): CarbonInterface
    {
        if ($this->config->starting_day !== $startDate->dayOfWeek) {
            if (0 === $this->config->starting_day) {
                $startDate->previous('sunday');
            } elseif (1 == $this->config->starting_day) {
                $startDate->previous('monday');
            }
        }

        return $startDate;
    }

    protected function makeHeader(CarbonPeriod $carbonPeriod): string
    {
        $headerString = '<thead>';

        $headerString .= '<tr class="calendar-header">';

        $headerString .= '<th></th>';

        /* @var Carbon $date */
        foreach ($carbonPeriod->toArray() as $carbon) {
            if ($this->config->dayShouldBeHidden($carbon)) {
                continue;
            }
            $headerString .= '<th class="cal-th cal-th-'.strtolower($carbon->englishDayOfWeek).'">';
            $headerString .= '<div class="cal-weekview-dow">'.ucfirst($carbon->localeDayOfWeek).'</div>';
            $headerString .= '<div class="cal-weekview-day">'.$carbon->day.'</div>';
            $headerString .= '<div class="cal-weekview-month">'.ucfirst($carbon->localeMonth).'</div>';
            $headerString .= '</th>';
        }

        $headerString .= '</tr>';

        return $headerString.'</thead>';
    }

    protected function renderBlocks(CarbonPeriod $carbonPeriod): string
    {
        $today = Carbon::now();
        $content = '';
        foreach ($this->getTimes() as $time) {
            $content .= '<tr>';

            $start_time = $time;
            $end_time = date('H:i', strtotime($time.' + '.$this->config->time_interval.' minutes'));

            $content .= '<td class="cal-weekview-time-th"><div>'.$start_time.' - '.$end_time.'</div></td>';

            foreach ($carbonPeriod->toArray() as $carbon) {
                if ($this->config->dayShouldBeHidden($carbon)) {
                    continue;
                }

                $datetime = $carbon->setTimeFrom($time);

                $events = $this->findEvents($datetime, $datetime->clone()->addMinutes($this->config->time_interval));

                $today_class = $carbon->isSameHour($today) ? ' today' : '';

                $content .= '<td class="cal-weekview-time '.$today_class.'">';

                $content .= '<div>';

                foreach ($events as $event) {
                    $content .= $this->renderEvent($event, $carbon);
                }

                $content .= '</div>';

                $content .= '</td>';
            }

            $content .= '<tr/>';
        }

        return $content;
    }

    protected function renderEvent(Event $event, CarbonInterface $dateTime): string
    {
        $classes = '';

        if (in_array($event, $this->usedEvents)) {
            $eventSummary = '&nbsp;';
        } else {
            $eventSummary = $event->summary;
            $this->usedEvents[] = $event;
        }

        // is the current day the start of the event
        if ($event->start->isSameDay($dateTime)) {
            $classes .= $event->mask ? ' mask-start' : '';
            $classes .= $event->classes;
        // is the current day in between the start and end of the event
        } elseif ($dateTime->betweenExcluded($event->start, $event->end)) {
            $classes .= $event->mask ? ' mask' : '';

        // is the current day the start of the event
        } elseif ($dateTime->isSameDay($event->end)) {
            $classes .= $event->mask ? ' mask-end' : '';
        }

        return '<div class="cal-weekview-event '.$classes.'">'.$eventSummary.'</div>';
    }
}

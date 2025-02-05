<?php

declare(strict_types=1);

namespace benhall14\phpCalendar\Views;

use benhall14\phpCalendar\Event;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Carbon\CarbonInterval;
use Carbon\CarbonPeriod;
use DateTimeInterface;

class Week extends View
{
    /**
     * @var list<Event>
     */
    protected array $usedEvents = [];

    /**
     * @var array{color: string, startDate: (string|CarbonInterface), timeInterval: int, endTime: string, startTime: string}
     */
    private array $options = [
        'color' => '',
        'startDate' => '',
        'timeInterval' => 0,
        'startTime' => '',
        'endTime' => '',
    ];

    protected function findEvents(CarbonInterface $start, CarbonInterface $end): array
    {
        $callback = fn(Event $event): bool => $event->start->betweenIncluded($start, $end)
            || $event->end->betweenIncluded($start, $end)
            || $end->betweenIncluded($event->start, $event->end);

        return array_filter($this->calendar->getEvents(), $callback);
    }

    /**
     * Returns the calendar output as a week view.
     *
     * @param array{color?: string, startDate?: (string|DateTimeInterface), timeInterval?: int, endTime?: string, startTime?: string} $options
     */
    public function render(array $options): string
    {
        $this->options = $this->initializeOptions($options);

        $startDate = $this->sanitizeStartDate($this->options['startDate']);
        $carbonPeriod = $startDate->locale($this->config->locale)->toPeriod(7);

        $calendar = [
            '<div class="weekly-calendar-container">',
            '<table class="weekly-calendar calendar ' . $this->options['color'] . ' ' . $this->config->table_classes . '">',
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
        $start_time = Carbon::createFromFormat('H:i', $this->options['startTime']);
        $end_time = Carbon::createFromFormat('H:i', $this->options['endTime']);
        if ($start_time->equalTo($end_time)) {
            $end_time->addDay();
        }

        $carbonPeriod = CarbonInterval::minutes($this->options['timeInterval'])
            ->toPeriod($this->options['startTime'], $end_time);

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

            $headerString .= '<th class="cal-th cal-th-' . strtolower($carbon->englishDayOfWeek) . '">';
            $headerString .= '<div class="cal-weekview-dow">' . ucfirst($carbon->dayName) . '</div>';
            $headerString .= '<div class="cal-weekview-day">' . $carbon->day . '</div>';
            $headerString .= '<div class="cal-weekview-month">' . ucfirst($carbon->monthName) . '</div>';
            $headerString .= '</th>';
        }

        $headerString .= '</tr>';

        return $headerString . '</thead>';
    }

    protected function renderBlocks(CarbonPeriod $carbonPeriod): string
    {
        $today = Carbon::now();
        $content = '';
        foreach ($this->getTimes() as $time) {
            $content .= '<tr>';

            $start_time = $time;
            $end_time = date('H:i', strtotime($time . ' + ' . $this->options['timeInterval'] . ' minutes'));

            $content .= '<td class="cal-weekview-time-th"><div>' . $start_time . ' - ' . $end_time . '</div></td>';

            foreach ($carbonPeriod->toArray() as $carbon) {
                if ($this->config->dayShouldBeHidden($carbon)) {
                    continue;
                }

                $datetime = $carbon->setTimeFrom($time);

                $events = $this->findEvents($datetime, $datetime->clone()->addMinutes($this->options['timeInterval']));

                $today_class = $carbon->isSameHour($today) ? ' today' : '';

                $content .= '<td class="cal-weekview-time ' . $today_class . '">';

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
            $classes .= $event->mask ? ' mask-start ' : '';
            $classes .= $event->classes;
            // is the current day in between the start and end of the event
        } elseif ($dateTime->betweenExcluded($event->start, $event->end)) {
            $classes .= $event->mask ? ' mask ' : '';

            // is the current day the start of the event
        } elseif ($dateTime->isSameDay($event->end)) {
            $classes .= $event->mask ? ' mask-end ' : '';
        }

        return '<div class="cal-weekview-event ' . $classes . '">' . $eventSummary . '</div>';
    }

    /**
     * @param array{color?: string, startDate?: (string|DateTimeInterface), timeInterval?: int, endTime?: string, startTime?: string} $options
     *
     * @return array{color: string, startDate: (string|CarbonInterface), timeInterval: int, endTime: string, startTime: string}
     */
    public function initializeOptions(array $options): array
    {
        return [
            'color' => $options['color'] ?? '',
            'startDate' => $this->sanitizeStartDate(Carbon::parse($options['startDate'] ?? null)),
            'timeInterval' => $options['timeInterval'] ?? $this->config->time_interval,
            'startTime' => $options['startTime'] ?? $this->config->start_time,
            'endTime' => $options['endTime'] ?? $this->config->end_time,
        ];
    }
}

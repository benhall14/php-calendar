<?php

declare(strict_types=1);

namespace benhall14\phpCalendar\Views;

use benhall14\phpCalendar\Calendar;
use benhall14\phpCalendar\Config;
use benhall14\phpCalendar\Event;
use Carbon\CarbonInterface;
use DateTimeInterface;

abstract class View
{
    public function __construct(
        protected Config $config,
        protected Calendar $calendar,
    ) {
    }

    /**
     * Find an event from the internal pool.
     *
     * @return array<Event> either an array of events or false
     */
    abstract protected function findEvents(CarbonInterface $start, CarbonInterface $end): array;

    /**
     * Returns the calendar as a month view.
     *
     * @param array{color?: string, startDate?: (string|DateTimeInterface)} $options
     */
    abstract public function render(array $options): string;
}

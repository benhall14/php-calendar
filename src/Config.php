<?php

declare(strict_types=1);

namespace benhall14\phpCalendar;

use Carbon\Carbon;
use Carbon\CarbonInterface;

/**
 * Config Class
 * 
 * @author Benjamin Hall <ben@conobe.co.uk>
 */
class Config
{
    /**
     * Default Locale
     *
     * @var string
     */
    public string $locale = 'en_US';

    /**
     * Calendar Type.
     */
    public string $type = 'month';

    /**
     * The Day Format.
     */
    public string $day_format = 'initials';

    /**
     * Start day of week. Default = 0 (Sunday).
     */
    public int $starting_day = 0;

    /**
     * Start Time
     *
     * @var string
     */
    public string $start_time = '00:00';

    /**
     * End Time
     *
     * @var string
     */
    public string $end_time = '00:00';

    /**
     * Time Interval
     *
     * @var int
     */
    public int $time_interval = 30;

    /**
     * Table classes that should be injected into the table header.
     */
    public string $table_classes = '';

    /**
     * Hide all days from the calendar view.
     *
     * @var list<string>
     */
    public array $hiddenDays = [];

    /**
     * @return list<string>
     */
    public function getHiddenDays(): array
    {
        return array_intersect($this->hiddenDays, Carbon::getDays());
    }

    /**
     * Should days be hidden.
     * 
     * Returns true or false if the day should be hidden.
     *
     * @param  CarbonInterface $carbon
     *
     * @return boolean
     */
    public function dayShouldBeHidden(CarbonInterface $carbon): bool
    {
        return in_array($carbon->englishDayOfWeek, $this->hiddenDays);
    }
}

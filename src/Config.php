<?php

declare(strict_types=1);

namespace benhall14\phpCalendar;

use Carbon\Carbon;
use Carbon\CarbonInterface;

class Config
{
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

    public function dayShouldBeHidden(CarbonInterface $carbon): bool
    {
        return in_array($carbon->englishDayOfWeek, $this->hiddenDays);
    }
}

<?php

namespace App\Models;

use DateInterval;

class BirthdayInterval implements BirthdayIntervalInterface
{
    /**
     * @var int
     */
    protected $age;

    /**
     * @var DateInterval
     */
    protected $interval;

    /**
     * BirthdayInterval constructor.
     * @param int $age
     * @param DateInterval $interval
     */
    public function __construct(int $age, DateInterval $interval)
    {
       $this->age = $age;
       $this->interval = $interval;
    }

    /**
     * @inheritdoc
     */
    public function getAge(): int
    {
        return $this->age;
    }

    /**
     * @inheritdoc
     */
    public function isBirthday(): bool
    {
        return (bool) $this->interval->invert;
    }

    /**
     * @inheritdoc
     */
    public function getDays(): int
    {
        return $this->interval->d;
    }

    /**
     * @inheritdoc
     */
    public function getMonths(): int
    {
        return $this->interval->m;
    }

    /**
     * @inheritdoc
     */
    public function getHoursTotalDiff(): int
    {
        return $this->interval->days * 24
            + $this->interval->h;
    }

    /**
     * @inheritdoc
     */
    public function getIntervalData(): array
    {
        return collect((array) $this->interval)
            ->only(['y', 'm', 'd', 'h', 'i', 's', 'invert'])
            ->all();
    }
}

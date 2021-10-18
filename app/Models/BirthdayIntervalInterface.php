<?php

namespace App\Models;

interface BirthdayIntervalInterface
{
    /**
     * @return int
     */
    public function getAge(): int;

    /**
     * True if birthday is today
     *
     * @return bool
     */
    public function isBirthday(): bool;

    /**
     * @return int
     */
    public function getDays(): int;

    /**
     * @return int
     */
    public function getMonths(): int;

    /**
     * Get total difference in hours
     *
     * @return int
     */
    public function getHoursTotalDiff(): int;

    /**
     * @return array
     */
    public function getIntervalData(): array;
}

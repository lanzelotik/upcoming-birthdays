<?php

namespace App\Services\UpcomingBirthdays;

use App\Models\BirthdayInterval;
use App\Models\BirthdayIntervalInterface;
use DateTime;
use DateTimeInterface;

class CalculateService
{
    /**
     * @var DateTimeInterface
     */
    protected $currentDate;

    /**
     * @param DateTimeInterface $currentDate
     */
    public function setCurrentDate(DateTimeInterface $currentDate)
    {
        $this->currentDate = $currentDate;
    }

    /**
     * @param DateTimeInterface $birthday
     * @return BirthdayIntervalInterface
     */
    public function getInterval(DateTimeInterface $birthday): BirthdayIntervalInterface
    {
        $currentDate = clone $this->getCurrentDate();
        $currentDate->setTimezone($birthday->getTimezone());

        $age = $birthday->diff($currentDate)->y;

        // compare dates without time
        $currentDateStart = (clone $currentDate)->setTime(0, 0);
        $birthday->modify("+$age years");

        if ($birthday < $currentDateStart) {
            $birthday->modify('+1 year');
            $age++;
        } elseif ($birthday == $currentDateStart) {
            $birthday->modify('tomorrow');
            return new BirthdayInterval(
                $age,
                $birthday->diff($currentDate)
            );
        }

        return new BirthdayInterval(
            $age,
            $currentDate->diff($birthday)
        );
    }

    /**
     * @return DateTimeInterface
     */
    protected function getCurrentDate(): DateTimeInterface
    {
        if(is_null($this->currentDate)) {
            $this->currentDate = new DateTime();
        }

        return $this->currentDate;
    }
}

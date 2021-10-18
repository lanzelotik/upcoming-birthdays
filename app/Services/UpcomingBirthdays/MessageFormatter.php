<?php

namespace App\Services\UpcomingBirthdays;

use App\Models\BirthdayIntervalInterface;
use App\Models\PersonInterface;

class MessageFormatter
{
    /**
     * @param PersonInterface $person
     * @param BirthdayIntervalInterface $birthdayInterval
     * @return string
     */
    public function getMessage(PersonInterface $person, BirthdayIntervalInterface $birthdayInterval): string
    {
        if ($birthdayInterval->isBirthday()) {
            $template = 'messages.today_birthday';
            $periodData = [
                trans_choice('messages.hours', $birthdayInterval->getHoursTotalDiff()),
            ];
        } else {
            $template = 'messages.upcoming_birthday';
            $periodData = [
                trans_choice('messages.months', $birthdayInterval->getMonths()),
                trans_choice('messages.days', $birthdayInterval->getDays()),
            ];
        }

        return $this->createMessage($template, [
            'name' => $person->getName(),
            'age' => trans_choice('messages.years', $birthdayInterval->getAge()),
            'period' => implode(', ', $periodData),
            'timezone' => $person->getTimezone(),
        ]);
    }

    /**
     * @param string $template
     * @param array $params
     * @return array|string|null
     */
    protected function createMessage(string $template, array $params)
    {
        return __($template, $params);
    }
}

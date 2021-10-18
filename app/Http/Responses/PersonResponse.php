<?php

namespace App\Http\Responses;

use App\Models\BirthdayIntervalInterface;
use App\Models\PersonInterface;
use JsonSerializable;

class PersonResponse implements JsonSerializable
{
    /**
     * @var PersonInterface
     */
    protected $person;

    /**
     * @var BirthdayIntervalInterface
     */
    protected $interval;

    /**
     * @var string
     */
    protected $message;

    /**
     * PersonResponse constructor.
     * @param PersonInterface $person
     * @param BirthdayIntervalInterface $interval
     * @param string $message
     */
    public function __construct(PersonInterface $person, BirthdayIntervalInterface $interval, string $message) {
        $this->person = $person;
        $this->interval = $interval;
        $this->message = $message;
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize()
    {
        return [
            'name' => $this->person->getName(),
            'birthdate' => $this->person->getBirthdate()
                ->format('Y-m-d'),
            'timezone' => $this->person->getTimezone(),
            'isBirthday' => $this->interval->isBirthday(),
            'interval' => $this->interval->getIntervalData(),
            'message' => $this->message,
        ];
    }
}

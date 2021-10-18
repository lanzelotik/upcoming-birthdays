<?php

namespace App\Models;

use DateTime;
use DateTimeInterface;
use DateTimeZone;
use Jenssegers\Mongodb\Eloquent\Model;

/**
 * Class Person
 *
 * @property string $name
 * @property string $birthdate
 * @property string $timezone
 */
class Person extends Model implements PersonInterface
{
    /**
     * @inheritdoc
     */
    protected $collection = 'persons';

    /**
     * @inheritdoc
     */
    protected $fillable = [
        'name',
        'birthdate',
        'timezone',
    ];

    /**
     * @inheritdoc
     */
    protected $dates = [
        'birthdate',
    ];

    /**
     * @inheritdoc
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritdoc
     */
    public function getTimezone(): string
    {
        return $this->timezone;
    }

    /**
     * @inheritdoc
     */
    public function getBirthdate(): DateTimeInterface
    {
        return new DateTime($this->birthdate, new DateTimeZone($this->timezone));
    }
}

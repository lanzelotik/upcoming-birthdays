<?php

namespace App\Models;

use DateTimeInterface;

interface PersonInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string
     */
    public function getTimezone(): string;

    /**
     * @return DateTimeInterface
     */
    public function getBirthdate(): DateTimeInterface;
}

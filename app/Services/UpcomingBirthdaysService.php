<?php

namespace App\Services;

use App\Http\Responses\PersonResponse;
use App\Repositories\PersonRepositoryInterface;
use App\Services\UpcomingBirthdays\CalculateService;
use App\Services\UpcomingBirthdays\MessageFormatter;
use DateTimeInterface;

class UpcomingBirthdaysService
{
    /**
     * @var PersonRepositoryInterface
     */
    protected $repository;

    /**
     * @var CalculateService
     */
    protected $calculate;

    /**
     * @var MessageFormatter
     */
    protected $formatter;

    /**
     * UpcomingBirthdaysService constructor.
     * @param PersonRepositoryInterface $repository
     * @param CalculateService $calculate
     * @param MessageFormatter $formatter
     */
    public function __construct(
        PersonRepositoryInterface $repository,
        CalculateService $calculate,
        MessageFormatter $formatter
    )
    {
        $this->repository = $repository;
        $this->calculate = $calculate;
        $this->formatter = $formatter;
    }

    /**
     * @param DateTimeInterface|null $dateFrom current date if null
     * @return array
     */
    public function getPersonsList(DateTimeInterface $dateFrom = null): array
    {
        if ($dateFrom) {
            $this->calculate->setCurrentDate($dateFrom);
        }

        $result = [];
        foreach ($this->repository->getList() as $person) {
            $birthdayInterval = $this->calculate->getInterval($person->getBirthdate());
            $message = $this->formatter->getMessage($person, $birthdayInterval);

            $result[] = new PersonResponse($person, $birthdayInterval, $message);
        }

        return $result;
    }

}

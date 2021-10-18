<?php

use App\Models\Person;
use App\Repositories\PersonRepositoryInterface;
use App\Services\UpcomingBirthdays\CalculateService;
use App\Services\UpcomingBirthdaysService;
use App\Services\UpcomingBirthdays\MessageFormatter;

class UpcomingBirthdaysServiceTest extends \TestCase
{
    /**
     * @var PersonRepositoryInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $repository;

    /**
     * @var UpcomingBirthdaysService
     */
    protected $service;

    /**
     * @dataProvider getPersonListDataProvider
     * @throws Exception
     */
    public function testGetPersonsList(
        array $persons,
        DateTimeInterface $dateFrom,
        array $expectedResult
    )
    {
        $this->repository->expects($this->once())
            ->method('getList')
            ->willReturn($persons);

        $result = $this->service->getPersonsList($dateFrom);

        $this->assertJsonStringEqualsJsonString(json_encode($expectedResult), json_encode($result));
    }

    /**
     * @return iterable
     * @throws Exception
     */
    public function getPersonListDataProvider(): iterable
    {
        yield 'empty data' => [
            [],
            new DateTime(),
            [],
        ];

        $name = 'Ken Thompson';
        $birthdate = '1943-02-04';
        $timezone = 'America/New_York';

        $person = new Person([
            'name' => $name,
            'birthdate' => $birthdate,
            'timezone' => $timezone,
        ]);

        $timeZoneNewYork = new DateTimeZone($timezone);
        $timeZoneUTC = new DateTimeZone('UTC');

        yield '1 month 1 day before birthday' => [
            'persons' => [$person],
            'date' => new DateTime('2021-01-03', $timeZoneNewYork),
            'result' => [
                [
                    'name' => $name,
                    'birthdate' => $birthdate,
                    'timezone' => $timezone,
                    'isBirthday' => false,
                    'interval' => [
                        'y' => 0,
                        'm' => 1,
                        'd' => 1,
                        'h' => 0,
                        'i' => 0,
                        's' => 0,
                        'invert' => 0,
                    ],
                    'message' => "Ken Thompson is 78 years old in 1 month, 1 day in $timezone",
                ],
            ]
        ];

        yield 'less than 1 day before birthday' => [
            'persons' => [$person],
            'date' => new DateTime('2021-02-03 02:00', $timeZoneNewYork),
            'result' => [
                [
                    'name' => $name,
                    'birthdate' => $birthdate,
                    'timezone' => $timezone,
                    'isBirthday' => false,
                    'interval' => [
                        'y' => 0,
                        'm' => 0,
                        'd' => 0,
                        'h' => 22,
                        'i' => 0,
                        's' => 0,
                        'invert' => 0,
                    ],
                    'message' => "Ken Thompson is 78 years old in 0 months, 0 days in $timezone",
                ],
            ]
        ];

        yield 'birthday remaining in 24 hours' => [
            'persons' => [$person],
            'date' => new DateTime('2021-02-04', $timeZoneNewYork),
            'result' => [
                [
                    'name' => $name,
                    'birthdate' => $birthdate,
                    'timezone' => $timezone,
                    'isBirthday' => true,
                    'interval' => [
                        'y' => 0,
                        'm' => 0,
                        'd' => 1,
                        'h' => 0,
                        'i' => 0,
                        's' => 0,
                        'invert' => 1,
                    ],
                    'message' => "Ken Thompson is 78 years old today (24 hours remaining in $timezone)",
                ],
            ],
        ];

        yield 'birthday remaining in 14 hours' => [
            'persons' => [$person],
            'date' => new DateTime('2021-02-04 10:00', $timeZoneNewYork),
            'result' => [
                [
                    'name' => $name,
                    'birthdate' => $birthdate,
                    'timezone' => $timezone,
                    'isBirthday' => true,
                    'interval' => [
                        'y' => 0,
                        'm' => 0,
                        'd' => 0,
                        'h' => 14,
                        'i' => 0,
                        's' => 0,
                        'invert' => 1,
                    ],
                    'message' => "Ken Thompson is 78 years old today (14 hours remaining in $timezone)",
                ],
            ],
        ];

        yield 'birthday remaining less than an hour' => [
            'persons' => [$person],
            'date' => new DateTime('2021-02-04 23:10', $timeZoneNewYork),
            'result' => [
                [
                    'name' => $name,
                    'birthdate' => $birthdate,
                    'timezone' => $timezone,
                    'isBirthday' => true,
                    'interval' => [
                        'y' => 0,
                        'm' => 0,
                        'd' => 0,
                        'h' => 0,
                        'i' => 50,
                        's' => 0,
                        'invert' => 1,
                    ],
                    'message' => "Ken Thompson is 78 years old today (0 hours remaining in $timezone)",
                ],
            ],
        ];

        yield 'birthday remaining in different timezones' => [
            'persons' => [$person],
            'date' => new DateTime('2021-02-04 10:00', $timeZoneUTC),
            'result' => [
                [
                    'name' => $name,
                    'birthdate' => $birthdate,
                    'timezone' => $timezone,
                    'isBirthday' => true,
                    'interval' => [
                        'y' => 0,
                        'm' => 0,
                        'd' => 0,
                        'h' => 19,
                        'i' => 0,
                        's' => 0,
                        'invert' => 1,
                    ],
                    'message' => "Ken Thompson is 78 years old today (19 hours remaining in $timezone)",
                ],
            ],
        ];

        yield 'birthday in next year' => [
            'persons' => [$person],
            'date' => new DateTime('2021-10-20', $timeZoneNewYork),
            'result' => [
                [
                    'name' => $name,
                    'birthdate' => $birthdate,
                    'timezone' => $timezone,
                    'isBirthday' => false,
                    'interval' => [
                        'y' => 0,
                        'm' => 3,
                        'd' => 15,
                        'h' => 0,
                        'i' => 0,
                        's' => 0,
                        'invert' => 0,
                    ],
                    'message' => "Ken Thompson is 79 years old in 3 months, 15 days in $timezone",
                ],
            ],
        ];

        $birthdate = '2021-10-19';
        $person = new Person([
            'name' => $name,
            'birthdate' => $birthdate,
            'timezone' => $timezone,
        ]);

        yield 'birthday was yesterday' => [
            'persons' => [$person],
            'date' => new DateTime('2021-10-20', $timeZoneNewYork),
            'result' => [
                [
                    'name' => $name,
                    'birthdate' => $birthdate,
                    'timezone' => $timezone,
                    'isBirthday' => false,
                    'interval' => [
                        'y' => 0,
                        'm' => 11,
                        'd' => 29,
                        'h' => 0,
                        'i' => 0,
                        's' => 0,
                        'invert' => 0,
                    ],
                    'message' => "Ken Thompson is 1 year old in 11 months, 29 days in $timezone",
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->createMock(PersonRepositoryInterface::class);
        $this->service = new UpcomingBirthdaysService(
            $this->repository,
            new CalculateService(),
            new MessageFormatter(),
        );
    }
}

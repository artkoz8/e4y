<?php

namespace App\Tests\Functional\Szkolenia;

use App\Tests\Functional\AbstractWebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ListingCourseWebTest extends AbstractWebTestCase
{
    public function testGetAllCourses(): void
    {
        $client = static::createClient();
        $client->request('GET', '/courses');
;
        self::assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        self::assertEquals([
            'coursesCount' => 5,
            'courses' => [
                1 => [
                    'courseId' => 1,
                    'name' => 'szkolenie z testów',
                    'courseLeader' => 'Zenek Benbenek',
                    'price' => [
                        'amount' => 9.35,
                        'currency' => 'PLN'
                    ],
                    'dateOfCourse' => '2029-01-01 11:00'
                ],
                2 => [
                    'courseId' => 2,
                    'name' => 'szkolenie z hr',
                    'courseLeader' => 'Zenek Benbenek-Leoniak',
                    'price' => [
                        'amount' => 899.99,
                        'currency' => 'PLN'
                    ],
                    'dateOfCourse' => '2028-01-01 11:00'
                ],
                3 => [
                    'courseId' => 3,
                    'name' => 'szkolenie z word',
                    'courseLeader' => 'Zenek Benbenek',
                    'price' => [
                        'amount' => 899.99,
                        'currency' => 'PLN'
                    ],
                    'dateOfCourse' => '2028-01-01 11:00'
                ],
                4 => [
                    'courseId' => 4,
                    'name' => 'szkolenie z scrum',
                    'courseLeader' => 'Zenek Benbenek',
                    'price' => [
                        'amount' => 899.99,
                        'currency' => 'PLN'
                    ],
                    'dateOfCourse' => '2028-11-01 11:00'
                ],
                5 => [
                    'courseId' => 5,
                    'name' => 'szkolenie z scrum master',
                    'courseLeader' => 'Zenek Benbenek-Leoniak',
                    'price' => [
                        'amount' => 404.9,
                        'currency' => 'PLN'
                    ],
                    'dateOfCourse' => '2026-11-01 11:00'
                ]
            ]
        ], json_decode($client->getResponse()->getContent(), true));
    }

    public function testFindCoursesByCourseLeader(): void
    {
        $client = static::createClient();
        $client->request(
            method: 'GET',
            uri: '/courses?' . http_build_query([
                "courseLeaderName" => "Zenek",
                "courseLeaderSurname" => "Benbenek-Leoniak"
            ]));
;
        self::assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        self::assertEquals([
            'coursesCount' => 2,
            'courses' => [
                2 => [
                    'courseId' => 2,
                    'name' => 'szkolenie z hr',
                    'courseLeader' => 'Zenek Benbenek-Leoniak',
                    'price' => [
                        'amount' => 899.99,
                        'currency' => 'PLN'
                    ],
                    'dateOfCourse' => '2028-01-01 11:00'
                ],
                5 => [
                    'courseId' => 5,
                    'name' => 'szkolenie z scrum master',
                    'courseLeader' => 'Zenek Benbenek-Leoniak',
                    'price' => [
                        'amount' => 404.9,
                        'currency' => 'PLN'
                    ],
                    'dateOfCourse' => '2026-11-01 11:00'
                ]
            ]
        ], json_decode($client->getResponse()->getContent(), true));
    }

    public function testFindCoursesByCourseLeaderAndName(): void
    {
        $client = static::createClient();
        $client->request(
            method: 'GET',
            uri: '/courses?' . http_build_query([
                "courseLeaderName" => "Zenek",
                "courseLeaderSurname" => "Benbenek-Leoniak",
                "courseName" => "szkolenie z scrum master"
            ])
        );
;
        self::assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        self::assertEquals([
            'coursesCount' => 1,
            'courses' => [
                5 => [
                    'courseId' => 5,
                    'name' => 'szkolenie z scrum master',
                    'courseLeader' => 'Zenek Benbenek-Leoniak',
                    'price' => [
                        'amount' => 404.9,
                        'currency' => 'PLN'
                    ],
                    'dateOfCourse' => '2026-11-01 11:00'
                ]
            ]
        ], json_decode($client->getResponse()->getContent(), true));
    }

    public function testFindCoursesByDate(): void
    {
        $client = static::createClient();
        $client->request(
            method: 'GET',
            uri: '/courses?' . http_build_query([
                "startDate" => "2028-01-01 10:00",
                "endDate" => "2028-11-01 11:10"
            ])
        );
;
        self::assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        self::assertEquals([
            'coursesCount' => 3,
            'courses' => [
                2 => [
                    'courseId' => 2,
                    'name' => 'szkolenie z hr',
                    'courseLeader' => 'Zenek Benbenek-Leoniak',
                    'price' => [
                        'amount' => 899.99,
                        'currency' => 'PLN'
                    ],
                    'dateOfCourse' => '2028-01-01 11:00'
                ],
                3 => [
                    'courseId' => 3,
                    'name' => 'szkolenie z word',
                    'courseLeader' => 'Zenek Benbenek',
                    'price' => [
                        'amount' => 899.99,
                        'currency' => 'PLN'
                    ],
                    'dateOfCourse' => '2028-01-01 11:00'
                ],
                4 => [
                    'courseId' => 4,
                    'name' => 'szkolenie z scrum',
                    'courseLeader' => 'Zenek Benbenek',
                    'price' => [
                        'amount' => 899.99,
                        'currency' => 'PLN'
                    ],
                    'dateOfCourse' => '2028-11-01 11:00'
                ]
            ]
        ], json_decode($client->getResponse()->getContent(), true));
    }
}
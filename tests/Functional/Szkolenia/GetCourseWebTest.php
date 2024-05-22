<?php

namespace App\Tests\Functional\Szkolenia;

use App\Tests\Functional\AbstractWebTestCase;
use Symfony\Component\HttpFoundation\Response;

class GetCourseWebTest extends AbstractWebTestCase
{
    public function testCourseNotFound(): void
    {
        $client = static::createClient();
        $client->request('GET', "/course/365974");

        $response = $client->getResponse();
        self::assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
        self::assertEquals(['errorMessage' => 'Course with id 365974 not found.'], json_decode($client->getResponse()->getContent(), true));
    }

    public function testGetCourse(): void
    {
        $client = static::createClient();
        $client->request('GET', "/course/5");

        self::assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        self::assertEquals(
            [
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
            ],
            json_decode($client->getResponse()->getContent(), true));
    }
}
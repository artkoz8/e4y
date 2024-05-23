<?php

namespace App\Tests\Functional\Szkolenia;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class DayListingCourseWebTest extends WebTestCase
{
    public function testListing(): void
    {
        $client = static::createClient();
        $client->request('GET', '/courses/days?' . http_build_query(['date' => '2028-01-01']));
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
            ]
        ], json_decode($client->getResponse()->getContent(), true));
    }
}
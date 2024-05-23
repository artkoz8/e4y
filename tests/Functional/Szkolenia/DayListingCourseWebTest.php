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

        self::assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        self::assertEquals(['coursesCount' => 2], json_decode($client->getResponse()->getContent(), true));
    }
}
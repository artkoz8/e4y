<?php

namespace App\Tests\Functional\Szkolenia;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GetSzkoleniaWebTest extends WebTestCase
{
    public function testAddSzkolenie(): void
    {
        $szkoleniaId = 123;

        $client = static::createClient();
        $client->request('GET', "/szkolenia/$szkoleniaId");

        $response = $client->getResponse();
        self::assertEquals(200, $response->getStatusCode());
    }
}
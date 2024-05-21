<?php

namespace App\Tests\Functional\Szkolenia;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ListingSzkoleniaWebTest extends WebTestCase
{
    public function testAddSzkolenie(): void
    {
        $client = static::createClient();
        $client->request('GET', '/szkolenia');

        $response = $client->getResponse();
        self::assertEquals(200, $response->getStatusCode());
    }
}
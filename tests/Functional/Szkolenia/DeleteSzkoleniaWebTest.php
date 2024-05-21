<?php

namespace App\Tests\Functional\Szkolenia;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DeleteSzkoleniaWebTest extends WebTestCase
{
    public function testAddSzkolenie(): void
    {
        $szkoleniaId = 123;

        $client = static::createClient();
        $client->request('DELETE', "/szkolenia/$szkoleniaId");

        $response = $client->getResponse();
        self::assertEquals(200, $response->getStatusCode());
    }
}
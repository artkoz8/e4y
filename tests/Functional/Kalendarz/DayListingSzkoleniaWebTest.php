<?php

namespace App\Tests\Functional\Kalendarz;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DayListingSzkoleniaWebTest extends WebTestCase
{
    public function testAddSzkolenie(): void
    {
        $client = static::createClient();
        $client->request('GET', '/kalendarz/szkolenia');

        $response = $client->getResponse();
        self::assertEquals(200, $response->getStatusCode());
    }
}
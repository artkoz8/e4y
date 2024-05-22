<?php

namespace App\Tests\Functional\Szkolenia;

use App\Tests\Functional\AbstractWebTestCase;
use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\Response;

class DeleteCourseWebTest extends AbstractWebTestCase
{
    public function testCourseNotFound(): void
    {
        $courseId = 1987402;

        $client = static::createClient();
        $client->request('DELETE', "/course/$courseId");

        self::assertEquals(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());
        self::assertEquals(['errorMessage' => 'Course with id 1987402 not found.'], json_decode($client->getResponse()->getContent(), true));
    }
    public function testCourseDelete(): void
    {
        $courseId = 2;

        $client = static::createClient();
        $client->request('DELETE', "/course/$courseId");

        /** @var Connection $connection */
        $connection = self::$kernel->getContainer()->get('database_connection');

        self::assertCount(0, $connection->fetchAllAssociative(
            "select * from training t where t.id = :courseId",
            ['courseId' => $courseId]
        ));
        self::assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        self::assertEquals(["message" => "Deleted course (courseId: $courseId)"], json_decode($client->getResponse()->getContent(), true));
    }
}
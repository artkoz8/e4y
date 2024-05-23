<?php

namespace App\Tests\Functional\Szkolenia;

use App\Tests\Functional\AbstractWebTestCase;
use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\Response;

class CreateCourseWebTest extends AbstractWebTestCase
{
    public function testCreateCourse(): void
    {
        $payload = [
            'name' => 'szkolenie z podstaw księgowosci',
            'courseLeader' => [
                'name' => 'Zenek',
                'surname' => 'Benbenek-Leoniak'
            ],
            'dateOfCourse' => '2024-07-12 10:00:00',
            'price' => 10.33
        ];

        $client = static::createClient();
        $client->request(
            method: 'POST',
            uri: '/courses',
            content: json_encode($payload)
        );


        /** @var Connection $connection */
        $connection = self::$kernel->getContainer()->get('database_connection');
        $courseId = $connection->fetchOne("select id from course t where t.name = :name", ['name' => $payload['name']]);

        self::assertEquals(Response::HTTP_CREATED, $client->getResponse()->getStatusCode());
        self::assertTrue($courseId > 0);
        self::assertEquals(['courseId' => $courseId], json_decode($client->getResponse()->getContent(), true));
    }

    public function testCreateCourseWithNotFoundCourseLeader(): void
    {
        $client = static::createClient();

        /** @var Connection $connection */
        $connection = self::$kernel->getContainer()->get('database_connection');
        $countCoursesBefore = $connection->fetchOne("select count(id) from course");

        $payload = [
            'name' => 'szkolenie z podstaw księgowosci',
            'courseLeader' => [
                'name' => 'Zenek',
                'surname' => 'Benbenek-Leoniak NotFound'
            ],
            'dateOfCourse' => '2024-07-12 10:00:00',
            'price' => 10.33
        ];

        $client->request(
            method: 'POST',
            uri: '/courses',
            content: json_encode($payload)
        );

        self::assertEquals(Response::HTTP_INTERNAL_SERVER_ERROR, $client->getResponse()->getStatusCode());
        self::assertEquals($countCoursesBefore, $connection->fetchOne("select count(id) from course"));
        self::assertEquals(['errorMessage' => 'CourseLeader "Zenek Benbenek-Leoniak NotFound" not found.'], json_decode($client->getResponse()->getContent(), true));
    }

    public function testCreateCourseDoubleCourse(): void
    {
        $client = static::createClient();

        /** @var Connection $connection */
        $connection = self::$kernel->getContainer()->get('database_connection');
        $countCoursesBefore = $connection->fetchOne("select count(id) from course");

        $payload = [
            'name' => 'szkolenie z scrum master',
            'courseLeader' => [
                'name' => 'Zenek',
                'surname' => 'Benbenek'
            ],
            'dateOfCourse' => '2025-07-12 10:00:00',
            'price' => 299.33
        ];

        $client->request(
            method: 'POST',
            uri: '/courses',
            content: json_encode($payload)
        );

        self::assertEquals(Response::HTTP_INTERNAL_SERVER_ERROR, $client->getResponse()->getStatusCode());
        self::assertEquals($countCoursesBefore, $connection->fetchOne("select count(id) from course"));
        self::assertEquals(['errorMessage' => 'Course "szkolenie z scrum master" already exists'], json_decode($client->getResponse()->getContent(), true));
    }

    public function testCreateCourseWrongPayload(): void
    {
        $client = static::createClient();

        /** @var Connection $connection */
        $connection = self::$kernel->getContainer()->get('database_connection');
        $countCoursesBefore = $connection->fetchOne("select count(id) from course");

        $client->request(
            method: 'POST',
            uri: '/courses',
            content: 'string...'
        );

        self::assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
        self::assertEquals($countCoursesBefore, $connection->fetchOne("select count(id) from course"));
        self::assertEquals(['errorMessage' => 'Invalid JSON'], json_decode($client->getResponse()->getContent(), true));
    }

    public function testCreateCourseWithoutRequiredParameters(): void
    {
        $client = static::createClient();

        /** @var Connection $connection */
        $connection = self::$kernel->getContainer()->get('database_connection');
        $countCoursesBefore = $connection->fetchOne("select count(id) from course");

        $client->request(
            method: 'POST',
            uri: '/courses',
            content: json_encode([])
        );

        self::assertEquals(Response::HTTP_PRECONDITION_FAILED, $client->getResponse()->getStatusCode());
        self::assertEquals($countCoursesBefore, $connection->fetchOne("select count(id) from course"));
        self::assertEquals(['errorMessage' => "'name' jest wymagana"], json_decode($client->getResponse()->getContent(), true));
    }
}
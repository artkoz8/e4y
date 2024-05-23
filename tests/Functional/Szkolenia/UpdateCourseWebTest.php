<?php

namespace App\Tests\Functional\Szkolenia;

use App\Tests\Functional\AbstractWebTestCase;
use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\Response;

class UpdateCourseWebTest extends AbstractWebTestCase
{
    public function testCourseToUpdateNotFound(): void
    {
        $courseId = 135554;

        $client = static::createClient();
        $client->request(
            method: 'PUT',
            uri: "/courses/$courseId",
            content: json_encode([])
        );

        self::assertEquals(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());
        self::assertEquals(['errorMessage' => 'Course with id 135554 not found.'], json_decode($client->getResponse()->getContent(), true));
    }

    public function testUpdateInvalidContent(): void
    {
        $courseId = 4;

        $client = static::createClient();
        $client->request(
            method: 'PUT',
            uri: "/courses/$courseId",
            content: "string..."
        );

        self::assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
        self::assertEquals(['errorMessage' => 'Invalid JSON'], json_decode($client->getResponse()->getContent(), true));
    }

    public function testUpdateNotFoundCourseLeader(): void
    {
        $courseId = 4;
        $payload = [
            'courseLeader' => [
                'name' => 'Tomasz',
                'surname' => 'Zeler'
            ]
        ];

        $client = static::createClient();
        $client->request(
            method: 'PUT',
            uri: "/courses/$courseId",
            content: json_encode($payload)
        );

        self::assertEquals(Response::HTTP_INTERNAL_SERVER_ERROR, $client->getResponse()->getStatusCode());
        self::assertEquals(['errorMessage' => 'CourseLeader "Tomasz Zeler" not found.'], json_decode($client->getResponse()->getContent(), true));
    }

    public function testUpdateCourseLeader(): void
    {
        $courseId = 4;
        $payload = [
            'courseLeader' => [
                'name' => 'Zenek',
                'surname' => 'Benbenek-Leoniak'
            ]
        ];

        $client = static::createClient();
        $client->request(
            method: 'PUT',
            uri: "/courses/$courseId",
            content: json_encode($payload)
        );

        /** @var Connection $connection */
        $connection = self::$kernel->getContainer()->get('database_connection');
        $course = $connection->fetchAllAssociative(
            "select * from training t where t.id = :courseId and t.course_leader_id = :courseLeaderId",
            ['courseId' => $courseId, 'courseLeaderId' => 1]
        );

        self::assertEquals(1, count($course));
        self::assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        self::assertEquals(['message' => 'Course updated.', 'courseId' => 4], json_decode($client->getResponse()->getContent(), true));
    }

    public function testDoubleCourseNameError(): void
    {
        $courseId = 4;
        $payload = [
            'name' => 'szkolenie z testów',
            'courseLeader' => [
                'name' => 'Zenek',
                'surname' => 'Benbenek-Leoniak'
            ]
        ];

        $client = static::createClient();
        /** @var Connection $connection */
        $connection = self::$kernel->getContainer()->get('database_connection');
        $courseBefore = $connection->fetchAllAssociative(
            "select * from training t where t.id = :courseId",
            ['courseId' => $courseId]
        );

        $client->request(
            method: 'PUT',
            uri: "/courses/$courseId",
            content: json_encode($payload)
        );

        $courseAfter = $connection->fetchAllAssociative(
            "select * from training t where t.id = :courseId",
            ['courseId' => $courseId]
        );

        self::assertEquals($courseBefore, $courseAfter);
        self::assertEquals(Response::HTTP_INTERNAL_SERVER_ERROR, $client->getResponse()->getStatusCode());
        self::assertEquals(['errorMessage' => 'Course "szkolenie z testów" already exists'], json_decode($client->getResponse()->getContent(), true));
    }

    public function testUpdateCourseName(): void
    {
        $courseId = 4;
        $payload = [
            'name' => 'szkolenie z scrum - new'
        ];

        $client = static::createClient();
        $client->request(
            method: 'PUT',
            uri: "/courses/$courseId",
            content: json_encode($payload)
        );

        /** @var Connection $connection */
        $connection = self::$kernel->getContainer()->get('database_connection');
        $course = $connection->fetchAllAssociative(
            "select * from training t where t.id = :courseId and t.name = :name",
            ['courseId' => $courseId, 'name' => $payload['name']]
        );

        self::assertEquals(1, count($course));
        self::assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        self::assertEquals(['message' => 'Course updated.', 'courseId' => 4], json_decode($client->getResponse()->getContent(), true));
    }

    public function testUpdateCoursePrice(): void
    {
        $courseId = 4;
        $payload = [
            'price' => 197.97
        ];

        $client = static::createClient();
        $client->request(
            method: 'PUT',
            uri: "/courses/$courseId",
            content: json_encode($payload)
        );

        /** @var Connection $connection */
        $connection = self::$kernel->getContainer()->get('database_connection');
        $course = $connection->fetchAllAssociative(
            "select * from training t where t.id = :courseId and t.price_amount = :price",
            ['courseId' => $courseId, 'price' => $payload['price']*100]
        );

        self::assertEquals(1, count($course));
        self::assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        self::assertEquals(['message' => 'Course updated.', 'courseId' => 4], json_decode($client->getResponse()->getContent(), true));
    }
}
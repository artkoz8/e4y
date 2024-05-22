<?php

namespace App\Szkolenia\Ports\Rest;

use App\Common\Exception\ApplicationException;
use App\Common\Ports\Rest\AbstractRestController;
use App\Szkolenia\Application\Command\DeleteCourseCommand;
use App\Szkolenia\Application\Exception\CourseNotFoundException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;

class DeleteCourse extends AbstractRestController
{
    use HandleTrait;

    public function __construct(
        protected LoggerInterface   $logger,
        private MessageBusInterface $messageBus,
    )
    {
        parent::__construct($logger);
    }

    #[Route('/course/{courseId}', name: 'deleteCourse', methods: ['DELETE'], requirements: ['courseId' => '\d+'])]
    public function __invoke(int $courseId): Response
    {
        try {
            $this->handle(new DeleteCourseCommand($courseId));
            return new JsonResponse(["message" => "Deleted course (courseId: $courseId)"]);
        } catch (HandlerFailedException $exception) {
            if ($exception->getPrevious() instanceof CourseNotFoundException) {
                return $this->resourceNotFoundResponse(throwable: $exception->getPrevious(), message: $exception->getPrevious()->getMessage());
            }
            return $this->internalServerErrorResponse($exception->getPrevious());
        } catch (\Throwable $exception) {
            return $this->internalServerErrorResponse($exception);
        }
    }
}
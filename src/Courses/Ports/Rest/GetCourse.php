<?php

namespace App\Courses\Ports\Rest;

use App\Common\Exception\ApplicationException;
use App\Common\Ports\Rest\AbstractRestController;
use App\Courses\Application\Command\GetCourseCommand;
use App\Courses\Application\Exception\CourseNotFoundException;
use App\Courses\Domain\CourseToDisplayCollection;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;

class GetCourse extends AbstractRestController
{
    use HandleTrait;

    public function __construct(
        protected LoggerInterface   $logger,
        private MessageBusInterface $messageBus,
    )
    {
        parent::__construct($logger);
    }

    #[Route('/course/{courseId}', name: 'getCourse', methods: ['GET'], requirements: ['courseId' => '\d+'])]
    public function __invoke(int $courseId): Response
    {
        try {
            /** @var CourseToDisplayCollection $courses */
            $courses = $this->handle(new GetCourseCommand($courseId));
            return new JsonResponse($courses->toArray(), Response::HTTP_OK);
        } catch (BadRequestHttpException $exception) {
            return $this->badRequestResponse($exception);
        } catch (HandlerFailedException $exception) {
            if ($exception->getPrevious() instanceof CourseNotFoundException) {
                return $this->resourceNotFoundResponse(throwable: $exception->getPrevious(), message: $exception->getPrevious()->getMessage());
            }
            if ($exception->getPrevious() instanceof ApplicationException) {
                return $this->internalServerErrorResponse(throwable: $exception->getPrevious(), message: $exception->getPrevious()->getMessage());
            }
            return $this->internalServerErrorResponse($exception->getPrevious());
        } catch (\Throwable $exception) {
            return $this->internalServerErrorResponse($exception);
        }
    }
}
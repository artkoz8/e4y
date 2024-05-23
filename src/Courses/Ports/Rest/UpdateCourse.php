<?php

namespace App\Courses\Ports\Rest;

use App\Common\Exception\ApplicationException;
use App\Common\Ports\Rest\AbstractRestController;
use App\Courses\Application\Command\UpdateCourseCommand;
use App\Courses\Application\Exception\CourseNotFoundException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;

class UpdateCourse extends AbstractRestController
{
    use HandleTrait;

    public function __construct(
        protected LoggerInterface   $logger,
        private MessageBusInterface $messageBus,
    )
    {
        parent::__construct($logger);
    }

    #[Route('/courses/{courseId}', name: 'updateCourse', methods: ['PUT'], requirements: ['courseId' => '\d+'])]
    public function __invoke(Request $request, int $courseId): Response
    {
        try {
            $content = parent::validateAndDecodeJsonContent($request);

            $this->handle(new UpdateCourseCommand(
                courseId: $courseId,
                name: $content['name']??null,
                courseLeaderName: $content['courseLeader']['name']??null,
                courseLeaderSurname: $content['courseLeader']['surname']??null,
                price: $content['price']??null,
                dateOfCourse: isset($content['dateOfCourse']) ? new \DateTime($content['dateOfCourse']) : null,
            ));

            return new JsonResponse(["message" => "Course updated.", "courseId" => $courseId], Response::HTTP_OK);
        } catch (BadRequestHttpException $exception) {
            return $this->badRequestResponse($exception);
        } catch (HandlerFailedException $exception) {
            if ($exception->getPrevious() instanceof CourseNotFoundException) {
                return $this->resourceNotFoundResponse(throwable: $exception->getPrevious(), message: $exception->getPrevious()->getMessage());
            }
            if ($exception->getPrevious() instanceof ApplicationException) {
                return $this->internalServerErrorResponse(throwable: $exception->getPrevious(), message: $exception->getPrevious()->getMessage());
            }
            return $this->internalServerErrorResponse($exception);
        } catch (\Throwable $exception) {
            return $this->internalServerErrorResponse($exception);
        }
    }
}
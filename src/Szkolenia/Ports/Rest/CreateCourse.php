<?php

namespace App\Szkolenia\Ports\Rest;

use App\Common\Exception\ApplicationException;
use App\Common\Ports\Rest\AbstractRestController;
use App\Szkolenia\Application\Command\CreateCourseCommand;
use DateTime;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\PreconditionFailedHttpException;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;

class CreateCourse extends AbstractRestController
{
    use HandleTrait;

    public function __construct(
        protected LoggerInterface $logger,
        private MessageBusInterface $messageBus,
    )
    {
        parent::__construct($logger);
    }


    #[Route('/course', name: 'createCourse', methods: ['POST'])]
    public function __invoke(Request $request): Response
    {
        try {
            $content = parent::validateAndDecodeJsonContent($request);

            if (!isset($content['name'])) {
                throw new PreconditionFailedHttpException("'name' jest wymagana", code: Response::HTTP_PRECONDITION_FAILED);
            }

            if (!isset($content['dateOfCourse'])) {
                throw new PreconditionFailedHttpException("'dateOfCourse' jest wymagana", code: Response::HTTP_PRECONDITION_FAILED);
            }

            if (!isset($content['price'])) {
                throw new PreconditionFailedHttpException("'price' jest wymagana", code: Response::HTTP_PRECONDITION_FAILED);
            }

            if (!isset($content['courseLeader']['name'])) {
                throw new PreconditionFailedHttpException("'courseLeader.name' jest wymagana", code: Response::HTTP_PRECONDITION_FAILED);
            }

            if (!isset($content['courseLeader']['surname'])) {
                throw new PreconditionFailedHttpException("'courseLeader.name' jest wymagana", code: Response::HTTP_PRECONDITION_FAILED);
            }

            $courseId = $this->handle(new CreateCourseCommand(
                name: $content['name'],
                dateOfCourse: new DateTime($content['dateOfCourse']),
                price: $content['price'],
                courseLeaderName: $content['courseLeader']['name'],
                courseLeaderSurname: $content['courseLeader']['surname'],
            ));

            return new JsonResponse(['courseId' => $courseId], Response::HTTP_CREATED);
        } catch (PreconditionFailedHttpException $exception) {
            return $this->preconditionResponse($exception, $content);
        } catch (BadRequestHttpException $exception) {
            return $this->badRequestResponse($exception);
        } catch (HandlerFailedException $exception) {
            if ($exception->getPrevious() instanceof ApplicationException) {
                return $this->internalServerErrorResponse(throwable: $exception->getPrevious(), message: $exception->getPrevious()->getMessage());
            }
            return $this->internalServerErrorResponse($exception);
        } catch (\Throwable $exception) {
            return $this->internalServerErrorResponse($exception);
        }
    }
}
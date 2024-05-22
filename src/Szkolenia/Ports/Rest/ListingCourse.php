<?php

namespace App\Szkolenia\Ports\Rest;

use App\Common\Exception\ApplicationException;
use App\Common\Ports\Rest\AbstractRestController;
use App\Szkolenia\Application\Command\GetCourseListingCommand;
use App\Szkolenia\Domain\CourseToDisplayCollection;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;

class ListingCourse extends AbstractRestController
{
    use HandleTrait;

    public function __construct(
        protected LoggerInterface   $logger,
        private MessageBusInterface $messageBus,
    )
    {
        parent::__construct($logger);
    }

    #[Route('/course', name: 'listingCourse', methods: ['GET'])]
    public function __invoke(): Response
    {
        try {
            /** @var CourseToDisplayCollection $courses */
            $courses = $this->handle(new GetCourseListingCommand());
            return new JsonResponse($courses->toArray(), Response::HTTP_OK);
        } catch (BadRequestHttpException $exception) {
            return $this->badRequestResponse($exception);
        } catch (HandlerFailedException $exception) {
            if ($exception->getPrevious() instanceof ApplicationException) {
                return $this->internalServerErrorResponse(throwable: $exception->getPrevious(), message: $exception->getPrevious()->getMessage());
            }
            return $this->internalServerErrorResponse($exception->getPrevious());
        } catch (\Throwable $exception) {
            return $this->internalServerErrorResponse($exception);
        }
    }
}
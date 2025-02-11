<?php

namespace App\Courses\Ports\Rest;

use App\Common\Exception\ApplicationException;
use App\Common\Ports\Rest\AbstractRestController;
use App\Courses\Application\Command\GetCourseListingCommand;
use App\Courses\Domain\CourseToDisplayCollection;
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

    #[Route('/courses', name: 'listingCourse', methods: ['GET'])]
    public function __invoke(Request $request): Response
    {
        try {
            $query = $request->query;

            if ($request->query->get('startDate') !== null) {
                $startDate = DateTime::createFromFormat('Y-m-d H:i', $request->query->get('startDate'));
                if ($startDate === false) {
                    throw new BadRequestHttpException("Query parameter 'startDate' is invalid",);
                }
            }

            if ($request->query->get('endDate') !== null) {
                $endDate = DateTime::createFromFormat('Y-m-d H:i', $request->query->get('endDate'));
                if ($endDate === false) {
                    throw new BadRequestHttpException("Query parameter 'endDate' is invalid",);
                }
            }

            /** @var CourseToDisplayCollection $courses */
            $courses = $this->handle(new GetCourseListingCommand(
                name: $query->get("courseName"),
                courseLeaderName: $query->get("courseLeaderName"),
                courseLeaderSurname: $query->get("courseLeaderSurname"),
                startDate: $startDate??null,
                endDate: $endDate??null,
            ));
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
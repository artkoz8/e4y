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

class DayListingCourse extends AbstractRestController
{
    use HandleTrait;

    public function __construct(
        protected LoggerInterface   $logger,
        private MessageBusInterface $messageBus,
    )
    {
        parent::__construct($logger);
    }

    #[Route('/courses/days', name: 'dayLlistingCourse', methods: ['GET'])]
    public function __invoke(Request $request): Response
    {
        try {
            if ($request->query->get('date') === null) {
                throw new PreconditionFailedHttpException("Query parameter 'date' is required");
            }

            $startDate = DateTime::createFromFormat('Y-m-d H:i', $request->query->get('date') . ' 00:00');
            $endDate = DateTime::createFromFormat('Y-m-d H:i:s.u', $request->query->get('date') . ' 23:59:59.999999');
            if ($startDate === false || $endDate === false) {
                throw new PreconditionFailedHttpException("Query parameter 'date' is invalid", );
            }

            /** @var CourseToDisplayCollection $courses */
            $courses = $this->handle(new GetCourseListingCommand(
                startDate: $startDate,
                endDate: $endDate,
            ));
            return new JsonResponse($courses->toArray(), Response::HTTP_OK);
        } catch (BadRequestHttpException $exception) {
            return $this->badRequestResponse($exception);
        }  catch (PreconditionFailedHttpException $exception) {
            return $this->preconditionResponse($exception);
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
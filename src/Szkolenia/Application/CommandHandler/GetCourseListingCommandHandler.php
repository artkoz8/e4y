<?php

namespace App\Szkolenia\Application\CommandHandler;

use App\Entity\Training;
use App\Szkolenia\Application\Command\GetCourseListingCommand;
use App\Szkolenia\Application\Factory\CourseToDisplayCollectionFactory;
use App\Szkolenia\Application\Query\CourseFindByIdQuery;
use App\Szkolenia\Application\Query\CourseFindAllQuery;
use App\Szkolenia\Domain\CourseToDisplayCollection;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Throwable;

#[AsMessageHandler]
class GetCourseListingCommandHandler
{
    use HandleTrait;

    public function __construct(
        private MessageBusInterface $messageBus,
        private CourseToDisplayCollectionFactory $courseToDisplayCollectionFactory
    ) {}

    public function __invoke(GetCourseListingCommand $command): CourseToDisplayCollection
    {
        try {
            /** @var Training[] $courses */
            $courses = $this->handle(new CourseFindAllQuery());
            return $this->courseToDisplayCollectionFactory->createCollection($courses);
        } catch (HandlerFailedException $exception) {
            throw $exception->getPrevious();
        } catch (Throwable $exception) {
            throw $exception;
        }
    }
}